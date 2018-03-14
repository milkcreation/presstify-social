<?php

namespace tiFy\Plugins\Social\Networks\Twitter;

use tiFy\Plugins\Social\Social;
use tiFy\Core\Options\Options;

class Twitter extends \tiFy\Plugins\Social\Factory
{
    /**
     * Clé d'index de qualification des options
     * @var string
     */
    protected static $OptionID = 'twitter';

    /**
     * CONSTRUCTEUR
     *
     * @return void
     */
    public function __construct($attrs = [])
    {
        parent::__construct($attrs);

        // Déclaration des événements
        $this->appAddAction('init');
        $this->appAddAction('wp_enqueue_scripts');
        $this->appAddAction('tify_options_register_node');

        // Déclaration des fonctions d'aide à la saisie
        $this->appAddHelper('tify_social_twitter_share', 'shareButton');
        $this->appAddHelper('tify_social_twitter_page_link', 'pageLink');
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
            'tiFyPlugins-socialNetworksTwitter-share',
            self::tFyAppUrl() . '/Share.css',
            [],
            160511
        );
        \wp_register_script(
            'twitter-widgets',
            '//platform.twitter.com/widgets.js',
            [],
            150109,
            true
        );
        \wp_register_script(
            'tiFyPlugins-socialNetworksTwitter-share',
            self::tFyAppUrl() . '/Share.js',
            ['jquery', 'twitter-widgets'],
            160629,
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
        \wp_enqueue_style('tiFyPlugins-socialNetworksTwitter-share');
        \wp_enqueue_script('tiFyPlugins-socialNetworksTwitter-share');
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
                'id'     => 'tiFyPlugins-socialNetworks-twitter',
                'parent' => 'tiFyPlugins-socialNetworks',
                'title'  => "<i class=\"fa fa-twitter\"></i> " . __('Twitter', 'tify'),
                'cb'     => [$this, 'tabooxOptionsForm'],
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
         * @var \tiFy\Plugins\Social\Networks\Twitter\Twitter $network
         */
        $network = self::tFyAppGetContainer('tiFy\Plugins\Social\Networks\Twitter\Twitter');

        // Récupération des options
        $defaults = ['uri' => ''];
        $value = isset(Social::$Options['tweet']) ? wp_parse_args(Social::$Options['tweet'], $defaults) : $defaults;

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


        $output = "<a href=\"https://twitter.com/intent/tweet?url=" . esc_attr($url) . "&text=" . esc_attr($text) . "\"" .
            " class=\"$class\"" .
            " data-tiFyPluginSocialNetwork=\"twitter\">" .
            "{$icon}{$text}" .
            "</a>";

        if ($echo) :
            echo $output;
        else :
            return $output;
        endif;
    }

    /**
     * Lien vers la page/compte Twitter
     *
     * @param array $args Attributs de configuration du lien de la page
     *
     * @return string|void
     */
    public static function pageLink($args = [])
    {
        if (empty(Social::$Options['tweet']['uri']))
            return;

        $defaults = [
            'class' => '',
            'text'  => __('Compte Twitter', 'tify'),
            'attrs' => [],
            'echo'  => true
        ];
        $args = wp_parse_args($args, $defaults);
        extract($args);

        $output = "<a href=\"" . (Social::$Options['tweet']['uri']) . "\" class=\"$class\"";
        if (!isset($attrs['title']))
            $output .= " title=\"" . sprintf(__('Vers le compte Twitter du site %s', 'tify'), get_bloginfo('name')) . "\"";
        if (!isset($attrs['target']))
            $output .= " target=\"_blank\"";
        foreach ((array)$attrs as $key => $value)
            $output .= " {$key}=\"{$value}\"";
        $output .= ">{$text}</a>";

        if ($echo)
            echo $output;
        else
            return $output;
    }
}