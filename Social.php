<?php declare(strict_types=1);

namespace tiFy\Plugins\Social;

use InvalidArgumentException;
use tiFy\Contracts\{Container\Container, Filesystem\LocalFilesystem};
use tiFy\Support\Proxy\Metabox;
use tiFy\Support\Proxy\Storage;
use tiFy\Plugins\Social\Channel\ChannelDriver;
use tiFy\Plugins\Social\Contracts\{ChannelDriver as ChannelDriverContract, Social as SocialContract};
use tiFy\Support\Proxy\View;

/**
 * @desc Extension PresstiFy de gestion des réseaux sociaux.
 * @author Jordy Manner <jordy@milkcreation.fr>
 * @package tiFy\Plugins\Social
 * @version 2.0.22
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
class Social implements SocialContract
{
    /**
     * Instances des réseaux déclarés.
     * @var ChannelDriverContract[]|array
     */
    protected $channels = [];

    /**
     * Instance du conteneur d'injection de dépendances.
     * @var Container
     */
    protected $container;

    /**
     * Instance du systeme de fichier de stockage des ressources
     * @var LocalFilesystem
     */
    protected $resources;

    protected $view;

    /**
     * CONSTRUCTEUR.
     *
     * @param Container|null $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(?Container $container = null)
    {
        $this->container = $container;
        $this->resources = Storage::local(__DIR__ . '/Resources');

        Metabox::add('Social', [
            'name'     => 'tify_social_share',
            'title'    => __('Réseaux sociaux', 'tify')
        ])->setScreen('tify_options@options')->setContext('tab');
    }

    /**
     * @inheritDoc
     */
    public function addChannel(string $name, $attrs): ChannelDriverContract
    {
        if (is_array($attrs)) {
            $driver = $attrs['driver'] ?? null;
            unset($attrs['driver']);
        } else {
            $driver = $attrs;
        }

        if (!$driver) {
            $driver = ChannelDriver::class;
        }

        if (is_object($driver)) {
            $channel = $driver->set($attrs);
        } elseif (class_exists($driver)) {
            $channel = new $driver($name, $attrs, $this);
        } elseif (is_string($driver) && $this->getContainer()) {
            $channel = $this->getContainer()->get("social.channel.{$driver}", [$attrs]);
        } else {
            $channel = new ChannelDriver($name, $attrs, $this);
        }

        if ($driver instanceof ChannelDriverContract) {
            throw new InvalidArgumentException(
                sprintf(__('Impossible de définir le pilote associé au réseau social [%s].', 'tify'), $name)
            );
        } else {
            $channel->boot();

            return $this->channels[$name] = $channel->parse();
        }
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function getChannel(string $name): ?ChannelDriverContract
    {
        return $this->channels[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getChannels(): array
    {
        return $this->channels;
    }

    /**
     * @inheritDoc
     */
    public function getResources(?string $path = null)
    {
        if (is_null($path)) {
            return $this->resources;
        } else {
            return $this->resources->has($path) ? (string)call_user_func($this->resources, $path) : '';
        }
    }

    /**
     * @inheritDoc
     */
    public function channelLink(string $name, array $attrs = []): string
    {
        return ($channel = $this->getChannel($name)) ? $channel->pageLink($attrs) : '';
    }

    /**
     * @inheritDoc
     */
    public function view(?string $name = null, array $data = [])
    {
        if (is_null($this->view)) {
            $this->view = View::getPlatesEngine([
                'directory' => __DIR__ . '/Resources/views',
            ]);
        }

        if (func_num_args() === 0) {
            return $this->view;
        }

        return $this->view->render($name, $data);
    }
}
