<?php declare(strict_types=1);

namespace tiFy\Plugins\Social;

use tiFy\Container\ServiceProvider;
use tiFy\Plugins\Social\Contracts\Social as SocialContract;
use tiFy\Plugins\Social\Contracts\ChannelDriver as ChannelDriverContract;
use tiFy\Plugins\Social\Channel\DailymotionChannel;
use tiFy\Plugins\Social\Channel\FacebookChannel;
use tiFy\Plugins\Social\Channel\GooglePlusChannel;
use tiFy\Plugins\Social\Channel\InstagramChannel;
use tiFy\Plugins\Social\Channel\LinkedinChannel;
use tiFy\Plugins\Social\Channel\PinterestChannel;
use tiFy\Plugins\Social\Channel\TwitterChannel;
use tiFy\Plugins\Social\Channel\ViadeoChannel;
use tiFy\Plugins\Social\Channel\VimeoChannel;
use tiFy\Plugins\Social\Channel\YoutubeChannel;
use tiFy\Plugins\Social\Channel\ChannelView;
use tiFy\Support\Proxy\View;

class SocialServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        'social',
        'social.channel.dailymotion',
        'social.channel.facebook',
        'social.channel.google-plus',
        'social.channel.instagram',
        'social.channel.linkedin',
        'social.channel.pinterest',
        'social.channel.twitter',
        'social.channel.viadeo',
        'social.channel.vimeo',
        'social.channel.youtube',
        'social.channel-view',
        'social.view',
    ];

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        if (($wp = $this->getContainer()->get('wp')) && $wp->is()) {
            add_action('after_setup_theme', function () {
                $this->getContainer()->get('social')->boot();
            });
        }
    }

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share('social', function () {
            return new Social(config('social', []), $this->getContainer());
        });

        $this->registerChannels();

        $this->registerChannelView();

        $this->registerView();
    }

    /**
     * Déclaration des pilotes de canaux de réseaux sociaux.
     *
     * @return void
     */
    public function registerChannels(): void
    {
        $this->getContainer()->add('social.channel.dailymotion', function (): ChannelDriverContract {
            return (new DailymotionChannel())->setSocial($this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.facebook', function (): ChannelDriverContract {
            return (new FacebookChannel())->setSocial($this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.google-plus', function (): ChannelDriverContract {
            return (new GooglePlusChannel())->setSocial($this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.instagram', function (): ChannelDriverContract {
            return (new InstagramChannel())->setSocial($this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.linkedin', function (): ChannelDriverContract {
            return (new LinkedinChannel())->setSocial($this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.pinterest', function (): ChannelDriverContract {
            return (new PinterestChannel())->setSocial($this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.twitter', function (): ChannelDriverContract {
            return (new TwitterChannel())->setSocial($this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.viadeo', function (): ChannelDriverContract {
            return (new ViadeoChannel())->setSocial($this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.vimeo', function (): ChannelDriverContract {
            return (new VimeoChannel())->setSocial($this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.youtube', function (): ChannelDriverContract {
            return (new YoutubeChannel())->setSocial($this->getContainer()->get('social'));
        });
    }

    /**
     * Déclaration du gestionnaire d'affichage de canal de réseau social.
     *
     * @return void
     */
    public function registerChannelView(): void
    {
        $this->getContainer()->share('social.channel-view', function () {
            /** @var SocialContract $social */
            $social = $this->getContainer()->get('social');

            return View::getPlatesEngine(array_merge([
                'directory' => $social->resources('views/channel'),
                'factory'   => ChannelView::class,
            ],));
        });
    }

    /**
     * Déclaration du gestionnaire d'affichage.
     *
     * @return void
     */
    public function registerView(): void
    {
        $this->getContainer()->share('social.view', function () {
            /** @var SocialContract $social */
            $social = $this->getContainer()->get('social');

            return View::getPlatesEngine([
                'directory' => $social->resources('views'),
            ]);
        });
    }
}