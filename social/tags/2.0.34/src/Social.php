<?php declare(strict_types=1);

namespace tiFy\Plugins\Social;

use InvalidArgumentException, RuntimeException;
use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Filesystem\LocalFilesystem;
use tiFy\Contracts\Partial\Partial as PartialManagerContract;
use tiFy\Contracts\View\Engine as ViewEngine;
use tiFy\Partial\Partial as PartialManager;
use tiFy\Plugins\Social\Contracts\DailymotionChannel as DailymotionChannelContract;
use tiFy\Plugins\Social\Contracts\FacebookChannel as FacebookChannelContract;
use tiFy\Plugins\Social\Contracts\GooglePlusChannel as GooglePlusChannelContract;
use tiFy\Plugins\Social\Contracts\InstagramChannel as InstagramChannelContract;
use tiFy\Plugins\Social\Contracts\LinkedinChannel as LinkedinChannelContract;
use tiFy\Plugins\Social\Contracts\PinterestChannel as PinterestChannelContract;
use tiFy\Plugins\Social\Contracts\TwitterChannel as TwitterChannelContract;
use tiFy\Plugins\Social\Contracts\ViadeoChannel as ViadeoChannelContract;
use tiFy\Plugins\Social\Contracts\VimeoChannel as VimeoChannelContract;
use tiFy\Plugins\Social\Contracts\YoutubeChannel as YoutubeChannelContract;
use tiFy\Support\Concerns\BootableTrait;
use tiFy\Support\Concerns\ContainerAwareTrait;
use tiFy\Support\ParamsBag;
use tiFy\Support\Proxy\Metabox;
use tiFy\Support\Proxy\Storage;
use tiFy\Support\Proxy\View;
use tiFy\Plugins\Social\Channel\SocialChannelDriver;
use tiFy\Plugins\Social\Contracts\SocialChannelDriver as SocialChannelDriverContract;
use tiFy\Plugins\Social\Contracts\Social as SocialManagerContract;
use tiFy\Plugins\Social\Contracts\SocialMenuPartial as SocialMenuPartialContract;
use tiFy\Plugins\Social\Contracts\SocialSharePartial as SocialSharePartialContract;
use tiFy\Plugins\Social\Partial\SocialMenuPartial;
use tiFy\Plugins\Social\Partial\SocialSharePartial;

class Social implements SocialManagerContract
{
    use BootableTrait, ContainerAwareTrait;

    /**
     * Instance de la classe.
     * @var static|null
     */
    private static $instance;

    /**
     * Liste des réseaux disponibles.
     * @var string[]
     */
    private $defaultsChannels = [
        'dailymotion' => DailymotionChannelContract::class,
        'facebook'    => FacebookChannelContract::class,
        'google-plus' => GooglePlusChannelContract::class,
        'instagram'   => InstagramChannelContract::class,
        'linkedin'    => LinkedinChannelContract::class,
        'pinterest'   => PinterestChannelContract::class,
        'twitter'     => TwitterChannelContract::class,
        'viadeo'      => ViadeoChannelContract::class,
        'vimeo'       => VimeoChannelContract::class,
        'youtube'     => YoutubeChannelContract::class,
    ];

    /**
     * Instances de pilotes de réseaux chargés.
     * @var SocialChannelDriverContract[]|array
     */
    private $channels = [];

    /**
     * Déclaration de réseaux à charger.
     * @var SocialChannelDriverContract[]|string[]|array
     */
    private $channelDefinitions = [];

    /**
     * Liste des services par défaut fournis par conteneur d'injection de dépendances.
     * @var array
     */
    private $defaultProviders = [];

    /**
     * Instance du gestionnaire des ressources
     * @var LocalFilesystem|null
     */
    private $resources;

    /**
     * Instance du gestionnaire de configuration.
     * @var ParamsBag
     */
    protected $config;

    /**
     * Moteur des gabarits d'affichage.
     * @var ViewEngine
     */
    protected $viewEngine;

    /**
     * @param array $config
     * @param Container|null $container
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
    public static function instance(): SocialManagerContract
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }
        throw new RuntimeException(sprintf('Unavailable %s instance', __CLASS__));
    }

    /**
     * @inheritDoc
     */
    public function boot(): SocialManagerContract
    {
        if (!$this->isBooted()) {
            register_setting('tify_options', 'tify_social_share');

            $registered = [];
            if ($channels = $this->config('channel', [])) {
                foreach ($channels as $k => $v) {
                    $registered[] = is_numeric($k) ? $v : $k;
                }
                $registered = array_intersect($registered, array_keys($this->defaultsChannels));
            } else {
                $registered = array_keys($this->defaultsChannels);
            }

            if ($registered) {
                foreach ($registered as $name) {
                    $this->registerChannel($name, array_merge([
                        'driver' => $this->defaultsChannels[$name],
                    ], $channels[$name] ?? []));
                }
            }

            if ($this->config('admin', true)) {
                Metabox::add('Social', [
                    'title' => __('Réseaux sociaux', 'tify'),
                ])->setScreen('tify_options@options')->setContext('tab');
            }

            $partialManager = ($this->containerHas(PartialManagerContract::class))
                ? $this->containerGet(PartialManagerContract::class) : new PartialManager();

            $partialManager->register('social-menu', $this->containerHas(SocialMenuPartialContract::class)
                ? SocialMenuPartialContract::class : new SocialMenuPartial($this, $partialManager));
            $partialManager->register('social-share', $this->containerHas(SocialSharePartialContract::class)
                ? SocialSharePartialContract::class : new SocialSharePartial($this, $partialManager));

            $this->setBooted();
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
    public function getChannel(string $name): ?SocialChannelDriverContract
    {
        return $this->loadChannel($name);
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
        return $this->loadChannels()->channels;
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
    public function loadChannel(string $name): ?SocialChannelDriverContract
    {
        if (isset($this->channels[$name])) {
            return $this->channels[$name];
        } elseif (!$def = $this->channelDefinitions[$name] ?? null) {
            throw new InvalidArgumentException(sprintf('SocialChannel [%s] not registered.', $name));
        }

        if (is_array($def)) {
            $driver = $def['driver'] ?? null;
            $params = $def;
            unset($def['driver']);
        } else {
            $driver = $def;
            $params = [];
        }

        if (!$driver) {
            $driver = SocialChannelDriver::class;
        }

        if (is_object($driver)) {
            $channel = $driver;
        } elseif (class_exists($driver)) {
            $channel = new $driver($this);
        } elseif (is_string($driver) && $this->containerHas($driver)) {
            $channel = $this->containerGet($driver);
        } else {
            $channel = new SocialChannelDriver($this);
        }

        if ($driver instanceof SocialChannelDriverContract) {
            throw new InvalidArgumentException(sprintf('Unable to boot SocialChannel [%s] .', $name));
        } else {
            if (!$channel->getName()) {
                $channel->setName($name);
            }

            return $this->channels[$name] = $channel->setParams($params)->boot();
        }
    }

    /**
     * @inheritDoc
     */
    public function loadChannels(): SocialManagerContract
    {
        foreach (array_keys($this->channelDefinitions) as $name) {
            $this->loadChannel($name);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function registerChannel(string $name, $channelDefinition): SocialManagerContract
    {
        unset($this->channels[$name]);
        $this->channelDefinitions[$name] = $channelDefinition;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function resources(?string $path = null)
    {
        if (!isset($this->resources) ||is_null($this->resources)) {
            $this->resources = Storage::local(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'resources');
        }

        return is_null($path) ? $this->resources : $this->resources->path($path);
    }

    /**
     * @inheritDoc
     */
    public function setConfig(array $attrs): SocialManagerContract
    {
        $this->config($attrs);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function view(?string $name = null, array $data = [])
    {
        if (is_null($this->viewEngine)) {
            $this->viewEngine = $this->containerHas('social.view-engine')
                ? $this->containerGet('social.view-engine') : View::getPlatesEngine();
        }

        if (func_num_args() === 0) {
            return $this->viewEngine;
        }

        return $this->viewEngine->render($name, $data);
    }
}
