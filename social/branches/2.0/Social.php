<?php

namespace tiFy\Plugins\Social;

use Illuminate\Support\Arr;
use Psr\Container\ContainerInterface;
use tiFy\Contracts\View\ViewEngine;
use tiFy\Kernel\Tools;
use tiFy\Support\Collection;
use tiFy\Plugins\Social\Contracts\NetworkFactory;
use tiFy\Plugins\Social\Contracts\Social as SocialContract;

/**
 * Class Social
 *
 * @desc Extension PresstiFy de gestion des réseaux sociaux.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package tiFy\Plugins\Social
 * @version 2.0.11
 *
 * USAGE :
 * Activation
 * ---------------------------------------------------------------------------------------------------------------------
 * Dans config/app.php ajouter \tiFy\Plugins\Social\SocialServiceProvider à la liste des fournisseurs de services.
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
 * Configuration
 * ---------------------------------------------------------------------------------------------------------------------
 * Dans le dossier de config, créer le fichier social.php
 * @see /vendor/presstify-plugins/social/Resources/config/social.php
 */
class Social extends Collection implements SocialContract
{
    /**
     * Instance du conteneur d'injection de dépendances.
     * @var ContainerInterface
     */
    protected $container;

    /**
     * CONSTRUCTEUR.
     *
     * @param ContainerInterface $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        add_action('admin_enqueue_scripts', function () {
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
            ) {
                wp_enqueue_style('Social-adminOptions');
            }
        });
    }

    /**
     * @inheritdoc
     */
    public function getMenuItems()
    {
        return $this->collect()
            ->filter(function (NetworkFactory $item) {
                return ($item->isActive() === true) && ($item->hasUri() === true);
            })
            ->sortBy(function (NetworkFactory $item) {
                return $item->getOrder();
            });
    }

    /**
     * @inheritdoc
     */
    public function getNetworkIcon($name)
    {
        return Tools::File()->svgGetContents(__DIR__ . "/Resources/assets/networks/{$name}/img/icon.svg") ? : '';
    }

    /**
     * @inheritdoc
     */
    public function menuRender($attrs = [])
    {
        Arr::set($attrs, 'attrs.class', sprintf(
            Arr::get($attrs, 'attrs.class', '%s'), 'Social-menu'
        ));

        $attrs['items'] = $this->getMenuItems();

        return $this->viewer('menu', $attrs);
    }

    /**
     * @inheritdoc
     */
    public function pageLinkRender($network, $attrs = [])
    {
        return $this->resolve("networks.factory.{$network}")->pageLink($attrs);
    }

    /**
     * @inheritdoc
     */
    public function resolve($alias, ...$args)
    {
        return $this->container->get("social.{$alias}", $args);
    }

    /**
     * @inheritdoc
     */
    public function viewer($view = null, $data = [])
    {
        /** @var ViewEngine $viewer */
        $viewer = $this->resolve('viewer');

        if (func_num_args() === 0) {
            return $viewer;
        }

        return $viewer->make("_override::{$view}", $data);
    }

    /**
     * @inheritdoc
     */
    public function walk($item, $key = null)
    {
        if ($item instanceof NetworkFactory) {
            $this->items[$key] = $item;
        }
    }
}
