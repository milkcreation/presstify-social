<?php declare(strict_types=1);

namespace tiFy\Plugins\Social;

use Exception;
use InvalidArgumentException;
use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Filesystem\LocalFilesystem;
use tiFy\Plugins\Social\Partial\SocialMenuPartial;
use tiFy\Plugins\Social\Partial\SocialSharePartial;
use tiFy\Support\ParamsBag;
use tiFy\Support\Proxy\Metabox;
use tiFy\Support\Proxy\Partial;
use tiFy\Support\Proxy\Storage;
use tiFy\Plugins\Social\Channel\ChannelDriver;
use tiFy\Plugins\Social\Contracts\ChannelDriver as ChannelDriverContract;
use tiFy\Plugins\Social\Contracts\Social as SocialContract;
use tiFy\Support\Proxy\View;

class Social implements SocialContract
{
    /**
     * Instance de la classe.
     * @var static|null
     */
    private static $instance;

    /**
     * Indicateur d'initialisation.
     * @var bool
     */
    private $booted = false;

    /**
     * Liste des nom de qualification des réseaux disponibles
     * @var string[]
     */
    private $defaultsChannels = [
        'dailymotion',
        'facebook',
        'google-plus',
        'instagram',
        'linkedin',
        'pinterest',
        'twitter',
        'viadeo',
        'vimeo',
        'youtube',
    ];

    /**
     * Liste des services par défaut fournis par conteneur d'injection de dépendances.
     * @var array
     */
    private $defaultProviders = [

    ];

    /**
     * Instance du gestionnaire des ressources
     * @var LocalFilesystem|null
     */
    private $resources;

    /**
     * Instances des réseaux déclarés.
     * @var ChannelDriverContract[]|array
     */
    protected $channels = [];

    /**
     * Instance du gestionnaire de configuration.
     * @var ParamsBag
     */
    protected $config;

    /**
     * Instance du conteneur d'injection de dépendances.
     * @var \Psr\Container\ContainerInterface|null
     */
    protected $container;


    protected $view;

    /**
     * @param array $config
     * @param \Psr\Container\ContainerInterface|null $container
     *
     * @return void
     */
    public function __construct(array $config = [], Container $container = null)
    {
        $this->setConfig($config);

        if (!is_null($container)) {
            $this->setContainer($container);
        }

        if (!self::$instance instanceof static) {
            self::$instance = $this;
        }
    }

    /**
     * @inheritDoc
     */
    public static function instance(): SocialContract
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }

        throw new Exception('Unavailable Social instance');
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
            /** @var ChannelDriverContract $channel */
            $channel = $this->getContainer()->get("social.channel.{$driver}");
            $channel->set($attrs);
        } else {
            $channel = (new ChannelDriver($name, $attrs))->setSocial($this);
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
    public function boot(): SocialContract
    {
        if (!$this->booted) {
            Metabox::add('Social', [
                'name'  => 'tify_social_share',
                'title' => __('Réseaux sociaux', 'tify'),
            ])->setScreen('tify_options@options')->setContext('tab');


            $registered = [];
            if ($channels = $this->config('social.channel', [])) {
                foreach ($channels as $k => $v) {
                    $registered[] = is_numeric($k) ? $v : $k;
                }

                $registered = array_intersect($registered, $this->defaultsChannels);
            } else {
                $registered = $this->defaultsChannels;
            }

            if ($registered) {
                foreach ($registered as $name) {
                    $this->addChannel($name, array_merge([
                        'driver' => $name
                    ], $channels[$name] ?? []));
                }
            }

            Partial::register('social-menu', (new SocialMenuPartial())->setSocial($this));
            Partial::register('social-share', (new SocialSharePartial())->setSocial($this));

            $this->booted = true;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function config($key = null, $default = null)
    {
        if (!isset($this->config) || is_null($this->config)) {
            $this->config = new ParamsBag();
        }

        if (is_string($key)) {
            return $this->config->get($key, $default);
        } elseif (is_array($key)) {
            return $this->config->set($key);
        } else {
            return $this->config;
        }
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
    public function getChannelLink(string $name, array $attrs = []): string
    {
        return ($channel = $this->getChannel($name)) ? $channel->pageLink($attrs) : '';
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
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function getProvider(string $name)
    {
        return $this->config("providers.{$name}", $this->defaultProviders[$name] ?? null);
    }

    /**
     * @inheritDoc
     */
    public function resolve(string $alias)
    {
        return ($container = $this->getContainer()) ? $container->get("social.{$alias}") : null;
    }

    /**
     * @inheritDoc
     */
    public function resolvable(string $alias): bool
    {
        return ($container = $this->getContainer()) && $container->has("social.{$alias}");
    }

    /**
     * @inheritDoc
     */
    public function resources(?string $path = null)
    {
        if (!isset($this->resources) ||is_null($this->resources)) {
            $this->resources = Storage::local(dirname(__DIR__));
        }

        return is_null($path) ? $this->resources : $this->resources->path($path);
    }

    /**
     * @inheritDoc
     */
    public function view(?string $name = null, array $data = [])
    {
        if (is_null($this->view)) {
            $this->view = $this->resolve('view');
        }

        if (func_num_args() === 0) {
            return $this->view;
        }

        return $this->view->render($name, $data);
    }

    /**
     * @inheritDoc
     */
    public function setConfig(array $attrs): SocialContract
    {
        $this->config($attrs);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setContainer(Container $container): SocialContract
    {
        $this->container = $container;

        return $this;
    }
}
