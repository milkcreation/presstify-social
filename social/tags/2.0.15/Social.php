<?php declare(strict_types=1);

namespace tiFy\Plugins\Social;

use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\View\ViewEngine;
use tiFy\Wordpress\Proxy\Field;
use tiFy\Support\Proxy\Storage;
use tiFy\Support\{Arr, Collection};
use tiFy\Plugins\Social\Contracts\{NetworkFactory, Social as SocialContract};

/**
 * Class Social
 *
 * @desc Extension PresstiFy de gestion des réseaux sociaux.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package tiFy\Plugins\Social
 * @version 2.0.15
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
     * @var Container
     */
    protected $container;

    /**
     * Instance du systeme de fichier de stockage des ressources
     * @var
     */
    protected $resources;

    /**
     * CONSTRUCTEUR.
     *
     * @param Container $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->resources = Storage::local(__DIR__ . '/Resources');

        add_action('admin_enqueue_scripts', function () {
            wp_register_style(
                'Social-adminOptions',
                class_info($this)->getUrl() . '/Resources/assets/css/admin.css',
                [],
                180822,
                'screen'
            );

            if (
                (get_current_screen()->id === 'settings_page_tify_options') &&
                config('social.admin_enqueue_scripts', true)
            ) {
                Field::get('toggle-switch')->enqueue();

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
        $path = "/assets/networks/{$name}/img/icon.svg";

        return $this->resources->has($path) ? (string) call_user_func($this->resources, $path) : '';
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
