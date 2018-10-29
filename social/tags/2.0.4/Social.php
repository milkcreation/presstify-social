<?php

/**
 * @name Social
 * @desc Gestion des réseaux sociaux.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package presstify-plugins/social
 * @namespace \tiFy\Plugins\Social
 * @version 2.0.4
 */

namespace tiFy\Plugins\Social;

use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use tiFy\App\Dependency\AbstractAppDependency;
use tiFy\Kernel\Tools;
use tiFy\Plugins\Social\Contracts\NetworkItemInterface;
use tiFy\Plugins\Social\SocialResolverTrait;

/**
 * Class Social
 * @package tiFy\Plugins\Social
 *
 * Activation :
 * ----------------------------------------------------------------------------------------------------
 * Dans config/app.php ajouter \tiFy\Plugins\Social\SocialServiceProvider à la liste des fournisseurs de services
 *     chargés automatiquement par l'application.
 * ex.
 * <?php
 * ...
 * use tiFy\Plugins\Social\SocialServiceProvider;
 * ...
 *
 * return [
 *      ...
 *      'providers' => [
 *          ...
 *          SocialServiceProvider::class
 *          ...
 *      ]
 * ];
 *
 * Configuration :
 * ----------------------------------------------------------------------------------------------------
 * Dans le dossier de config, créer le fichier social.php
 * @see /vendor/presstify-plugins/social/Resources/config/social.php Exemple de configuration
 */
class Social
{
    use SocialResolverTrait;

    /**
     * CONSTRUCTEUR.
     *
     * @return void
     */
    public function __construct()
    {
        add_action(
            'admin_enqueue_scripts',
            function () {
                field('toggle-switch')->enqueue_scripts();

                wp_register_style(
                    'Social-adminOptions',
                    class_info($this)->getUrl() . '/Resources/assets/css/admin-options.css',
                    [],
                    180822,
                    'screen'
                );

                if (
                    (get_current_screen()->id === 'settings_page_tify_options') &&
                    config('social.admin_enqueue_scripts', true)
                ) :
                    wp_enqueue_style('Social-adminOptions');
                endif;
            });
    }

    /**
     * Récupération du nom de la classe d'un controleur de réseau.
     *
     * @return string
     */
    public function getAbstract($alias)
    {
        $name = Str::studly($alias);
        $abstract = "tiFy\\Plugins\\Social\\NetworkItems\\{$name}\\{$name}";

        if (class_exists($abstract)) :
            return $abstract;
        endif;

        return '';
    }

    /**
     * Récupération de la liste des réseaux déclarés.
     *
     * @return NetworkItemInterface[]
     */
    public function getItems()
    {
        /** @var SocialServiceProvider $serviceProvider */
        $serviceProvider = resolve(SocialServiceProvider::class);

        return $serviceProvider->getNetworkItems();
    }

    /**
     * Récupération de la liste des réseaux pris en charge affichable dans le menu par ordre d'affichage.
     * {@internal le réseaux doit être actif, son url renseignée. La liste est triée par ordre d'affichage.}
     *
     * @return NetworkItemInterface[]
     */
    public function getMenuItems()
    {
        return (new Collection($this->getItems()))
            ->filter(function ($item) {
                /** @var NetworkItemInterface $item */
                return ($item->isActive() === true) && ($item->hasUri() === true);
            })
            ->sortBy(function ($item) {
                /** @var NetworkItemInterface $item */
                return $item->getOrder();
            })
            ->all();
    }

    /**
     * Récupération de l'icône d'un réseau.
     *
     * @param string $name Nom de qualification du réseau
     *
     * @return string
     */
    public function getNetworkIcon($name)
    {
        return Tools::File()->svgGetContents(__DIR__ . "/Resources/assets/networks/{$name}/img/icon.svg")
            ? : '';
    }

    /**
     * Affichage d'un menu de la liste des liens vers la page des comptes des réseaux.
     *
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return string
     */
    public function menuRender($attrs = [])
    {
        Arr::set(
            $attrs,
            'attrs.class',
            sprintf(
                Arr::get($attrs, 'attrs.class', '%s'), 'Social-menu'
            )
        );

        $attrs['items'] = $this->getMenuItems();

        return $this->viewer('menu', $attrs);
    }

    /**
     * Affichage d'un lien vers la page du compte d'un réseau.
     *
     * @param string $alias Nom de qualification du réseau.
     * @param array $attrs Liste des attributs de configuration personnalisé.
     *
     * @return string
     */
    public function pageLinkRender($alias, $attrs = [])
    {
        /** @var NetworkItemInterface $networkItem */
        $networkItem = container($this->getAbstract($alias));

        return $networkItem->pageLink($attrs);
    }
}
