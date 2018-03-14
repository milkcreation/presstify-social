<?php

namespace tiFy\Plugins\Social\Networks\YouTube;

use tiFy\Plugins\Social\Social;
use tiFy\Core\Options\Options;

class YouTube extends \tiFy\Plugins\Social\Factory
{
    /**
     * Clé d'index de qualification des options
     * @var string
     */
    protected static $OptionID = 'vimeo';

    /**
     * CONSTRUCTEUR
     *
     * @return void
     */
    public function __construct($attrs = [])
    {
        parent::__construct($attrs);

        // Déclaration des événements
        $this->appAddAction('tify_options_register_node');

        // Déclaration des fonctions d'aide à la saisie
        $this->appAddHelper('tify_social_youtube_page_link', 'pageLink');
    }

    /**
     * EVENEMENTS
     */
    /**
     * Déclaration de sections de boîte à onglets d'administration des options
     *
     * @return void
     */
    final public function tify_options_register_node()
    {
        Options::registerNode(
            [
                'id'     => 'tiFyPlugins-socialNetworks-youtube',
                'parent' => 'tiFyPlugins-socialNetworks',
                'title'  => "<i class=\"fa fa-youtube\"></i> " . __('YouTube', 'tify'),
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
         * @var \tiFy\Plugins\Social\Networks\YouTube\YouTube $network
         */
        $network = self::tFyAppGetContainer('tiFy\Plugins\Social\Networks\YouTube\YouTube');

        // Récupération des options
        $defaults = ['uri' => ''];
        $value = isset(Social::$Options['youtube']) ? wp_parse_args(Social::$Options['youtube'], $defaults) : $defaults;

        self::tFyAppGetTemplatePart('options', null, compact('network', 'value'));
    }

    /**
     * CONTROLEURS
     */
    /**
     * Lien vers la chaine YouTube
     *
     * @param array $args Attributs de configuration du lien de la page
     *
     * @return string|void
     */
    public static function pageLink($args = [])
    {
        if (empty(Social::$Options['youtube']['uri']))
            return;

        $defaults = [
            'class' => '',
            'text'  => '',
            'attrs' => [],
            'echo'  => true
        ];
        $args = wp_parse_args($args, $defaults);
        extract($args);

        $output = "<a href=\"" . Social::$Options['youtube']['uri'] . "\" class=\"$class\"";

        if (!isset($attrs['title']))
            $output .= " title=\"" . sprintf(__('Vers la chaîne YouTube du site %s', 'tify'), get_bloginfo('name')) . "\"";

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