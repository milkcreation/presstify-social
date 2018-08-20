<?php

namespace tiFy\Plugins\Social\Networks\Facebook;

use tiFy\Plugins\Social\Social;
use tiFy\Core\Options\Options;

class Facebook extends \tiFy\Plugins\Social\Factory
{
    /**
     * CONSTRUCTEUR
     *
     * @return void
     */
    public function __construct($attrs = [])
    {
        parent::__construct('fb', $attrs);

        // Déclaration des événements
        $this->appAddAction('init');
        $this->appAddAction('wp_enqueue_scripts');
        $this->appAddAction('wp_ajax_tify_fb_post2feed_callback', 'wp_ajax');
        $this->appAddAction('wp_ajax_nopriv_tify_fb_post2feed_callback', 'wp_ajax');
        $this->appAddAction('tify_options_register_node');

        // Déclaration des fonctions d'aide à la saisie
        $this->appAddHelper('tify_social_facebook_share', 'shareButton');
        $this->appAddHelper('tify_social_facebook_page_link', 'pageLink');
    }

    /**
     * EVENEMENTS
     */
    /**
     * Initialisation globale
     *
     * @return void
     */
    final public function init()
    {
        // Initialisation des scripts
        \wp_register_style(
            'tiFyPlugins-socialNetworksFacebook-share',
            self::tFyAppUrl() . '/Share.css',
            [],
            160511
        );
        \wp_register_script(
            'tiFyPlugins-socialNetworksFacebook-share',
            self::tFyAppUrl() . '/Share.js',
            ['jquery'],
            160511,
            true
        );
    }

    /**
     * Mise en file des scripts de l'interface utilisateur
     *
     * @return void
     */
    final public function wp_enqueue_scripts()
    {
        \wp_enqueue_style('tiFyPlugins-socialNetworksFacebook-share');
        \wp_enqueue_script('tiFyPlugins-socialNetworksFacebook-share');
    }

    /**
     * Requête Ajax
     * Rappel de traitement de partage
     */
    final public function wp_ajax()
    {
        $output = apply_filters('tify_fb_post2feed_callback_handle', '', $_POST['response'], $_POST['attrs']);

        wp_die($output);
    }

    /**
     * Déclaration de sections de boîte à onglets d'administration des options
     *
     * @return void
     */
    final public function tify_options_register_node()
    {
        Options::registerNode(
            [
                'id'     => 'tiFyPlugins-socialNetworks-facebook',
                'parent' => 'tiFyPlugins-socialNetworks',
                'title'  => "<i class=\"fa fa-facebook-official\"></i> " . __('Facebook', 'tify'),
                'cb'     => [$this, 'tabooxOptionsForm'],
                'args'   => ['share' => $this->getAttr('share', false)]
            ]
        );
    }

    /**
     * Formulaire de saisie des options de réseau social
     *
     * @return void
     */
    final public function tabooxOptionsForm()
    {
        /**
         * @var \tiFy\Plugins\Social\Networks\Facebook\Facebook $network
         */
        $network = self::tFyAppGetContainer('tiFy\Plugins\Social\Networks\Facebook\Facebook');

        // Récupération des options
        $defaults = [
            'appId' => '',
            'uri'   => ''
        ];
        $options = isset(Social::$Options['fb']) ? Social::$Options['fb'] : [];
        $value = wp_parse_args($options, $defaults);

        self::tFyAppGetTemplatePart('options', null, compact('network', 'value'));
    }

    /**
     * CONTROLEURS
     */
    /**
     * Lien de partage
     * @see https://developers.facebook.com/docs/sharing/reference/feed-dialog/v2.6
     *
     * @param int $post_id Identifiant de qualification du post à partager
     * @param array $args Attributs de configuration du partage
     * @param bool $echo Activation de l'affichage
     *
     * @return null|string
     */
    public static function shareButton($post = 0, $args = [], $echo = true)
    {
        static $Instance;
        $Instance++;

        /**
         * @var \tiFy\Plugins\Social\Networks\Facebook\Facebook $network
         */
        $network = self::tFyAppGetContainer('tiFy\Plugins\Social\Networks\Facebook\Facebook');

        // Initialisation de Facebook
        $appId = $network->getOption('appId');
        $fb = new \tiFy\Lib\Facebook\Facebook($appId);
        $fb->JSInit();

        if (!($post = get_post($post)) && is_singular()) :
            $post = get_post();
        endif;

        $defaults = [
            'id'     => '',
            'class'  => '',
            'href'   => '',
            'text'   => false,
            'icon'   => true,
            'params' => [
                'method' => 'share',
            ],
        ];
        $args = wp_parse_args($args, $defaults);
        extract($args);

        if ($icon && is_bool($icon)) :
            $icon = \tiFy\Lib\Utils::get_svg(self::tFyAppDirname() . '/assets/logo.svg', false);
        endif;

        if ($params['method'] === 'feed') :
            $defaults = [
                #'app_id'           => $appId,
                #'redirect_uri'     => '',
                #'display'          => '',
                #'from'             => (is_singular() && ! empty($post)) ? get_author_name($post->author_id) : '',
                #'to'               => '',
                'link'        => !empty($post) ? get_permalink($post->ID) : $href,
                'picture'     => (!empty($post) && ($attachment_id = get_post_thumbnail_id($post->ID))) ? wp_get_attachment_url($attachment_id) : '',
                #'source'           => ''
                'name'        => !empty($post) ? $post->post_title : get_bloginfo('name'),
                'caption'     => '',
                'description' => !empty($post) ? $post->post_excerpt : get_bloginfo('description'),
                #'ref'              => '',
                #'callback_attrs'   => array()
            ];
            $params = is_array($params) ? wp_parse_args($params, $defaults) : $defaults;

            if ($params['picture']) :
                foreach (\tiFy\Lib\Facebook\Facebook::$OGImageSizes as $size) :
                    // Bypass
                    if (!$picture = \tiFy\Lib\Utils::get_context_img_src($params['picture'], $size[0], $size[1],
                        true)) {
                        continue;
                    }

                    $params['picture'] = $picture;
                    break;
                endforeach;
            endif;

            if (!$href && $params['link']) :
                $href = $params['link'];
            endif;
        else :
            /** @see https://developers.facebook.com/docs/sharing/reference/share-dialog * */
            if (!$href) :
                global $wp;
                $href = home_url(add_query_arg([], $wp->request));
            endif;

            $params['href'] = !empty($post) ? get_permalink($post->ID) : $href;
        endif;

        $output = "<a";
        $output .= " href=\"{$href}\"";
        $output .= " class=\"{$class}\"";
        $output .= " data-tiFyPluginSocialNetwork=\"facebook\"";
        $output .= " data-action=\"tify-fb-api_share_button\"";
        $output .= " data-params=\"" . (htmlentities(json_encode($params))) . "\"";
        $output .= ">{$icon}{$text}</a>";

        if ($echo) :
            echo $output;
        else :
            return $output;
        endif;
    }

    /**
     * Lien vers la page/compte Facebook
     *
     * @param array $args Attributs de configuration du lien de la page
     *
     * @return string|void
     */
    public static function pageLink($args = [])
    {
        if (empty(Social::$Options['fb']['uri']))
            return;

        $defaults = [
            'class' => '',
            'text'  => __('Page Facebook', 'tify'),
            'attrs' => [],
            'echo'  => true
        ];
        $args = wp_parse_args($args, $defaults);
        extract($args);

        $output = "<a href=\"" . (Social::$Options['fb']['uri']) . "\" class=\"$class\"";
        if (!isset($attrs['title']))
            $output .= " title=\"" . sprintf(__('Vers la page Facebook du site %s', 'tify'), get_bloginfo('name')) . "\"";
        if (!isset($attrs['target']))
            $output .= " target=\"_blank\"";

        foreach ((array)$attrs as $key => $value)
            $output .= " {$key}=\"{$value}\"";
        $output .= ">{$text}</a>";

        if ($echo) :
            echo $output;
        else :
            return $output;
        endif;
    }
}