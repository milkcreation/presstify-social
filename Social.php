<?php

/**
 * @name Social
 * @desc Gestion des réseaux sociaux
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package presstify-plugins/social
 * @namespace \tiFy\Plugins\Social
 * @version 1.4.2
 */

namespace tiFy\Plugins\Social;

use tiFy\App\Plugin;
use tiFy\Core\Options\Options;

class Social extends Plugin
{
    /**
     * Liste des options
     * @var array
     */
    public static $Options = [];

    /**
     * CONSTRUCTEUR
     *
     * @return void
     */
    public function appBoot()
    {
        // Récupération des options enregistrées
        self::$Options = get_option('tify_social_share', []);

        // Chargement des réseaux
        foreach ((array)self::tFyAppConfig('Networks') as $name => $attrs) :
            $classname = "tiFy\\Plugins\\Social\\Networks\\" . ucfirst($name) . "\\" . ucfirst($name);

            // Bypass - Le réseau n'existe pas
            if (!class_exists($classname)) {
                continue;
            }
            self::tFyAppShareContainer($classname, new $classname($attrs));
        endforeach;

        // Déclaration des événements
        $this->appAddAction('admin_enqueue_scripts');
        $this->appAddAction('tify_options_register_node');
    }

    /**
     * EVENEMENTS
     */
    /**
     * Mise en file des scripts de l'interface d'administration
     *
     * @return void
     */
    public function admin_enqueue_scripts()
    {
        \wp_enqueue_style('font-awesome');
    }

    /**
     * Déclaration de la boîte à onglets d'administration des options des réseaux sociaux déclarés
     *
     * @return void
     */
    public function tify_options_register_node()
    {
        Options::registerNode(
            [
                'id'    => 'tiFyPlugins-socialNetworks',
                'title' => __('Réseaux sociaux', 'tify'),
            ]
        );

        \register_setting('tify_options', 'tify_social_share');
    }
}
