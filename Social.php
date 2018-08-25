<?php

/**
 * @name Social
 * @desc Gestion des réseaux sociaux
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package presstify-plugins/social
 * @namespace \tiFy\Plugins\Social
 * @version 2.0.0
 */

namespace tiFy\Plugins\Social;

use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use tiFy\App\Dependency\AbstractAppDependency;
use tiFy\Options\Options;
use tiFy\Plugins\Social\Contracts\NetworkItemInterface;
use tiFy\Plugins\Social\SocialServiceProvider;

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
        $this->app->appAddAction('wp_enqueue_scripts', [$this, 'wp_enqueue_scripts']);
        $this->app->appAddAction('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        $this->app->appAddAction('tify_options_register', [$this, 'optionsTab']);

        require_once __DIR__ . '/helpers.php';
    }

    /**
     * Mise en file automatique des scripts de l'interface client.
     *
     * @return null
     */
    public function wp_enqueue_scripts()
    {
        if (config('social.wp_enqueue_scripts', true)) :

        endif;
    }

    /**
     * Mise en file des scripts de l'interface d'administration.
     *
     * @return null
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
                return ($item->isActive() === true) && ($item->hasUri() === true);
            })
            ->sortBy(function ($item) {
                return $item->getOrder();
            })
            ->all();
    }

    /**
     * Affichage d'un menu de la liste des liens vers la page des comptes des réseaux.
     *
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
            ->render('menu::list', $args);
    }

    /**
     * Affichage d'un lien vers la page du compte d'un réseau.
     *
     * @return string
     */
    public function pageLinkRender($alias)
    {
        /** @var NetworkItemInterface $networkItem */
        $networkItem = container($this->getAbstract($alias));

        return $networkItem->pageLink();
    }

    /**
     * Déclaration de la boîte à onglets d'administration des options des réseaux sociaux déclarés.
     *
     * @param Options $options Instance de la classe des options de presstiFy.
     *
     * @return void
     */
    public function optionsTab($options)
    {
        $options->register(
            [
                'name'    => 'tiFySocial',
                'title' => __('Réseaux sociaux', 'tify'),
            ]
        );

        \register_setting('tify_options', 'tify_social_share');
    }
}
