<?php declare(strict_types=1);

namespace tiFy\Plugins\Social;

use tiFy\Container\ServiceProvider;
use tiFy\Plugins\Social\Contracts\NetworkFactory;
use tiFy\Plugins\Social\Networks\Networks;
use tiFy\Plugins\Social\Networks\Dailymotion\Dailymotion;
use tiFy\Plugins\Social\Networks\Facebook\Facebook;
use tiFy\Plugins\Social\Networks\GooglePlus\GooglePlus;
use tiFy\Plugins\Social\Networks\Instagram\Instagram;
use tiFy\Plugins\Social\Networks\Linkedin\Linkedin;
use tiFy\Plugins\Social\Networks\Pinterest\Pinterest;
use tiFy\Plugins\Social\Networks\Twitter\Twitter;
use tiFy\Plugins\Social\Networks\Viadeo\Viadeo;
use tiFy\Plugins\Social\Networks\Vimeo\Vimeo;
use tiFy\Plugins\Social\Networks\Youtube\Youtube;
use tiFy\Plugins\Social\Networks\NetworkViewer;

class SocialServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'social',
        'social.networks',
        'social.networks.factory.dailymotion',
        'social.networks.factory.facebook',
        'social.networks.factory.google-plus',
        'social.networks.factory.instagram',
        'social.networks.factory.linkedin',
        'social.networks.factory.pinterest',
        'social.networks.factory.twitter',
        'social.networks.factory.viadeo',
        'social.networks.factory.vimeo',
        'social.networks.factory.youtube',
        'social.networks.viewer',
        'social.viewer'
    ];

    /**
     * Liste des nom de qualification des réseaux disponibles
     * @var string[]
     */
    private $availableNetworks = [
        'dailymotion',
        'facebook',
        'google-plus',
        'instagram',
        'linkedin',
        'pinterest',
        'twitter',
        'viadeo',
        'vimeo',
        'youtube'
    ];

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('after_setup_theme', function () {
            $social = $this->getContainer()->get('social');

            $actives = [];
            if ($networks = config('social.networks', [])) {
                foreach($networks as $k => $v) {
                    if (is_numeric($k)) {
                        $actives[] = $v;
                    } else {
                        $actives[] = $k;
                    }
                }
                $actives = array_intersect($actives, $this->availableNetworks);
            } else {
                $actives = $this->availableNetworks;
            }

            if ($actives) {
                $items = [];
                foreach ($actives as $alias) {
                    $items[$alias] = $this->getContainer()->get("social.networks.factory.{$alias}");
                }
                $social->set($items);

                $this->getContainer()->get('social.networks');
            }
        });
    }

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('social', function () {
            return new Social($this->getContainer());
        });

        $this->registerNetworks();

        $this->registerOptions();

        $this->registerViewers();
    }

    /**
     * Déclaration des controleurs de réseaux
     *
     * @return void
     */
    public function registerNetworks(): void
    {
        $this->getContainer()->share('social.networks.factory.dailymotion', function () {
            return new Dailymotion(config('social.networks.dailymotion', []), $this->getContainer()->get('social'));
        });

        $this->getContainer()->share('social.networks.factory.facebook', function () {
            return new Facebook(config('social.networks.facebook', []), $this->getContainer()->get('social'));
        });

        $this->getContainer()->share('social.networks.factory.google-plus', function () {
            return new GooglePlus(config('social.networks.google-plus', []), $this->getContainer()->get('social'));
        });

        $this->getContainer()->share('social.networks.factory.instagram', function () {
            return new Instagram(config('social.networks.instagram', []), $this->getContainer()->get('social'));
        });

        $this->getContainer()->share('social.networks.factory.linkedin', function () {
            return new Linkedin(config('social.networks.linkedin', []), $this->getContainer()->get('social'));
        });

        $this->getContainer()->share('social.networks.factory.pinterest', function () {
            return new Pinterest(config('social.networks.pinterest', []), $this->getContainer()->get('social'));
        });

        $this->getContainer()->share('social.networks.factory.twitter', function () {
            return new Twitter(config('social.networks.twitter', []), $this->getContainer()->get('social'));
        });

        $this->getContainer()->share('social.networks.factory.viadeo', function () {
            return new Viadeo(config('social.networks.viadeo', []), $this->getContainer()->get('social'));
        });

        $this->getContainer()->share('social.networks.factory.vimeo', function () {
            return new Vimeo(config('social.networks.vimeo', []), $this->getContainer()->get('social'));
        });

        $this->getContainer()->share('social.networks.factory.youtube', function () {
            return new Youtube(config('social.networks.youtube', []), $this->getContainer()->get('social'));
        });
    }

    /**
     * Déclaration des controleurs de gestion des options.
     *
     * @return void
     */
    public function registerOptions(): void
    {
        $this->getContainer()->share('social.networks', function () {
            return new Networks($this->getContainer()->get('social'));
        });
    }

    /**
     * Déclaration du controleur de gabarit d'affichage.
     *
     * @return void
     */
    public function registerViewers(): void
    {
        $this->getContainer()->add('social.networks.viewer', function (NetworkFactory $network) {
            $default_dir = __DIR__ . '/Resources/views/network';
            return view()
                ->setDirectory($default_dir)
                ->setController(NetworkViewer::class)
                ->setOverrideDir(
                    (($override_dir = $network->get('viewer.override_dir')) && is_dir($override_dir))
                        ? $override_dir
                        : $default_dir
                )
                ->setParam('network', $network);
        });

        $this->getContainer()->share('social.viewer', function () {
            $default_dir = __DIR__ . '/Resources/views';
            return view()->setDirectory($default_dir)->setOverrideDir($default_dir);
        });
    }
}