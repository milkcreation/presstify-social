<?php

namespace tiFy\Plugins\Social\Networks\Pinterest;

use tiFy\Plugins\Social\Social;
use tiFy\Core\Options\Options;

class Pinterest extends \tiFy\Plugins\Social\Factory
{
    /**
     * Clé d'index de qualification des options
     * @var string
     */
    protected static $OptionID = 'pinterest';

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
        $this->appAddHelper('tify_social_pinterest_page_link', 'pageLink');
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
                'id'     => 'tiFyPlugins-socialNetworks-pinterest',
                'parent' => 'tiFyPlugins-socialNetworks',
                'title'  => "<i class=\"fa fa-pinterest\"></i> " . __('Pinterest', 'tify'),
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
         * @var \tiFy\Plugins\Social\Networks\Pinterest\Pinterest $network
         */
        $network = self::tFyAppGetContainer('tiFy\Plugins\Social\Networks\Pinterest\Pinterest');

        // Récupération des options
        $defaults = ['uri' => ''];
        $value = isset(Social::$Options['pinterest']) ? wp_parse_args(Social::$Options['pinterest'], $defaults) : $defaults;

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
        if (empty(Social::$Options['pinterest']['uri']))
            return;

        $defaults = [
            'class' => '',
            'text'  => '',
            'attrs' => [],
            'echo'  => true
        ];
        $args = wp_parse_args($args, $defaults);
        extract($args);

        $output = "<a href=\"" . Social::$Options['pinterest']['uri'] . "\" class=\"$class\"";

        if (!isset($attrs['title']))
            $output .= " title=\"" . sprintf(__('Vers le compte Pinterest du site %s', 'tify'), get_bloginfo('name')) . "\"";

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