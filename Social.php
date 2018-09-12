<?php

/**
 * @name Social
 * @desc Gestion des réseaux sociaux.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package presstify-plugins/social
 * @namespace \tiFy\Plugins\Social
 * @version 1.4.4
 */

namespace tiFy\Plugins\Social;

use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use tiFy\App\Dependency\AbstractAppDependency;
use tiFy\Plugins\Social\Contracts\NetworkItemInterface;

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
class Social extends AbstractAppDependency
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);

        require_once __DIR__ . '/helpers.php';
    }

    /**
     * Mise en file des scripts de l'interface d'administration.
     *
     * @return void
     */
    public function admin_enqueue_scripts()
    {
        field('toggle-switch')->enqueue_scripts();

        wp_register_style(
            'tiFySocial-adminOptions',
            class_info($this)->getUrl() . '/Resources/dist/css/admin-options.css',
            [],
            180822,
            'screen'
        );

        if (
            (\get_current_screen()->id === 'settings_page_tify_options') &&
            config('social.admin_enqueue_scripts', true)
        ) :
            \wp_enqueue_style('tiFySocial-adminOptions');
        endif;
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
        $serviceProvider = app(SocialServiceProvider::class);

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
     * Affichage d'un menu de la liste des liens vers la page des comptes des réseaux.
     *
     * @param array $args
     * @return string
     */
    public function menuRender($args = [])
    {
        Arr::set(
            $args,
            'attrs.class',
            sprintf(
                Arr::get($args, 'attrs.class', '%s'), 'tiFyPluginSocial-menu'
            )
        );

        $args['items'] = $this->getItems();

        return view()
            ->setDirectory(__DIR__ . '/Resources/views')
            ->addFolder('menu', __DIR__ . '/Resources/views')
            ->make('menu::list', $args);
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
