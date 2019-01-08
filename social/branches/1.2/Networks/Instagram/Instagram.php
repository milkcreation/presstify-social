<?php

namespace tiFy\Plugins\Social\Networks\Instagram;

use tiFy\Plugins\Social\Social;
use tiFy\Core\Options\Options;

class Instagram extends \tiFy\Plugins\Social\Factory
{
    /**
     * Clé d'index de qualification des options
     * @var string
     */
    protected static $OptionID = 'instagram';

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
        $this->appAddHelper('tify_social_instagram_page_link', 'pageLink');
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
                'id'     => 'tiFyPlugins-socialNetworks-instagram',
                'parent' => 'tiFyPlugins-socialNetworks',
                'title'  => "<i class=\"fa fa-instagram\"></i> " . __('Instagram', 'tify'),
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
         * @var \tiFy\Plugins\Social\Networks\Instagram\Instagram $network
         */
        $network = self::tFyAppGetContainer('tiFy\Plugins\Social\Networks\Instagram\Instagram');

        // Récupération des options
        $defaults = ['uri' => ''];
        $value = isset(Social::$Options['instagram']) ? wp_parse_args(Social::$Options['instagram'], $defaults) : $defaults;

        self::tFyAppGetTemplatePart('options', null, compact('network', 'value'));
    }

    /**
     * CONTROLEURS
     */
    /**
     * Lien vers la page/compte Pinterest
     *
     * @param array $args Attributs de configuration du lien de la page
     *
     * @return string|void
     */
    public static function pageLink($args = [])
    {
        if (empty(Social::$Options['instagram']['uri']))
            return;

        $defaults = [
            'class' => '',
            'text'  => '',
            'attrs' => [],
            'echo'  => true
        ];
        $args = wp_parse_args($args, $defaults);
        extract($args);

        $output = "<a href=\"" . Social::$Options['instagram']['uri'] . "\" class=\"$class\"";

        if (!isset($attrs['title']))
            $output .= " title=\"" . sprintf(__('Vers le compte Instagram du site %s', 'tify'), get_bloginfo('name')) . "\"";

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