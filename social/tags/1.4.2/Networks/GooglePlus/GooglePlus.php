<?php

namespace tiFy\Plugins\Social\Networks\GooglePlus;

use tiFy\Plugins\Social\Social;
use tiFy\Core\Options\Options;

class GooglePlus extends \tiFy\Plugins\Social\Factory
{
    /**
     * CONSTRUCTEUR
     *
     * @return void
     */
    public function __construct($attrs = [])
    {
        parent::__construct('googleplus', $attrs);

        // Déclaration des événements
        $this->appAddAction('init');
        $this->appAddAction('wp_enqueue_scripts');
        $this->appAddAction('wp_footer', null, 99);
        $this->appAddAction('tify_options_register_node');

        // Déclaration des fonctions d'aide à la saisie
        $this->appAddHelper('tify_social_googleplus_share', 'shareButton');
        $this->appAddHelper('tify_social_googleplus_page_link', 'pageLink');
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
            'tiFyPlugins-socialNetworksGooglePlus-share',
            self::tFyAppUrl() . '/Share.css',
            [],
            160511
        );
        \wp_register_script(
            'tiFyPlugins-socialNetworksGooglePlus-share',
            self::tFyAppUrl() . '/Share.js',
            ['jquery'],
            '160629',
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
        \wp_enqueue_style('tiFyPlugins-socialNetworksGooglePlus-share');
        \wp_enqueue_script('tiFyPlugins-socialNetworksGooglePlus-share');
    }

    /**
     * Pied de page de l'interface utilisateurs
     *
     * @return string
     */
    final public function wp_footer()
    {
        ?>
        <script type="text/javascript">/* <![CDATA[ */
            window.___gcfg = {
                lang: '<?php echo get_locale();?>'
            };
            (function () {
                var po = document.createElement('script');
                po.type = 'text/javascript';
                po.async = true;
                po.src = 'https://apis.google.com/js/plusone.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(po, s);
            })();
            /* ]]> */</script><?php
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
                'id'     => 'tiFyPlugins-socialNetworks-google_plus',
                'parent' => 'tiFyPlugins-socialNetworks',
                'title'  => "<i class=\"fa fa-google-plus\"></i> " . __('Google Plus', 'tify'),
                'cb'     => [$this, 'tabooxOptionsForm']
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
         * @var \tiFy\Plugins\Social\Networks\GooglePlus\GooglePlus $network
         */
        $network = self::tFyAppGetContainer('tiFy\Plugins\Social\Networks\GooglePlus\GooglePlus');

        // Récupération des options
        $defaults = ['uri' => ''];
        $value = isset(Social::$Options['gplus']) ? wp_parse_args(Social::$Options['gplus'], $defaults) : $defaults;

        self::tFyAppGetTemplatePart('options', null, compact('network', 'value'));
    }

    /**
     * CONTROLEURS
     */
    /**
     * Lien de partage
     *
     * @param int $post_id Identifiant de qualification du post à partager
     * @param array $args Attributs de configuration du partage
     * @param bool $echo Activation de l'affichage
     *
     * @return null|string
     */
    public static function shareButton($post = 0, $args = [], $echo = true)
    {
        global $wp;

        static $Instance;
        $Instance++;

        if (!($post = get_post($post)) && is_singular()) :
            $post = get_post();
        endif;

        $defaults = [
            'id'    => '',
            'class' => '',
            'text'  => false,
            'icon'  => true,
            'url'   => !empty($post) ? wp_get_shortlink($post->ID) : home_url(add_query_arg([], $wp->request)),
        ];
        $args = wp_parse_args($args, $defaults);
        extract($args);

        if ($icon && is_bool($icon)) :
            $icon = \tiFy\Lib\Utils::get_svg(self::tFyAppDirname() . '/assets/logo.svg', false);
        endif;

        $output = "<a href=\"https://plus.google.com/share?url=" . esc_attr($url) . "\"" .
            " onclick=\"javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;\"" .
            " class=\"$class\"" .
            " data-tiFyPluginSocialNetwork=\"googleplus\">" .
            "{$icon}{$text}" .
            "</a>";

        if ($echo) :
            echo $output;
        else :
            return $output;
        endif;
    }

    /**
     * Lien vers la page/compte GooglePlus
     *
     * @param array $args Attributs de configuration du lien de la page
     *
     * @return string|void
     */
    public static function pageLink($args = [])
    {
        if (empty(Social::$Options['gplus']['uri']))
            return;

        $defaults = [
            'class' => '',
            'text'  => '',
            'attrs' => [],
            'echo'  => true
        ];
        $args = wp_parse_args($args, $defaults);
        extract($args);

        $output = "<a href=\"" . Social::$Options['gplus']['uri'] . "\" class=\"$class\"";
        if (!isset($attrs['title']))
            $output .= " title=\"" . sprintf(__('Vers la page Google+ du site %s', 'tify'), get_bloginfo('name')) . "\"";

        if (!isset($attrs['target']))
            $output .= " target=\"_blank\"";

        foreach ((array)$attrs as $key => $value)
            $output .= " {$key}=\"{$value}\"";

        $output .= ">$text</a>";

        if ($echo)
            echo $output;
        else
            return $output;
    }
}