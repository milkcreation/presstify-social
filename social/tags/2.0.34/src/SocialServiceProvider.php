<?php declare(strict_types=1);

namespace tiFy\Plugins\Social;

use tiFy\Container\ServiceProvider;
use tiFy\Contracts\Partial\Partial as PartialManagerContract;
use tiFy\Plugins\Social\Contracts\Social as SocialManagerContract;
use tiFy\Plugins\Social\Contracts\DailymotionChannel as DailymotionChannelContract;
use tiFy\Plugins\Social\Contracts\FacebookChannel as FacebookChannelContract;
use tiFy\Plugins\Social\Contracts\GooglePlusChannel as GooglePlusChannelContract;
use tiFy\Plugins\Social\Contracts\InstagramChannel as InstagramChannelContract;
use tiFy\Plugins\Social\Contracts\LinkedinChannel as LinkedinChannelContract;
use tiFy\Plugins\Social\Contracts\PinterestChannel as PinterestChannelContract;
use tiFy\Plugins\Social\Contracts\SocialMenuPartial as SocialMenuPartialContract;
use tiFy\Plugins\Social\Contracts\SocialSharePartial as SocialSharePartialContract;
use tiFy\Plugins\Social\Contracts\TwitterChannel as TwitterChannelContract;
use tiFy\Plugins\Social\Contracts\ViadeoChannel as ViadeoChannelContract;
use tiFy\Plugins\Social\Contracts\VimeoChannel as VimeoChannelContract;
use tiFy\Plugins\Social\Contracts\YoutubeChannel as YoutubeChannelContract;
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
use tiFy\Plugins\Social\Channel\SocialChannelView;
use tiFy\Plugins\Social\Partial\SocialMenuPartial;
use tiFy\Plugins\Social\Partial\SocialSharePartial;
use tiFy\Support\Proxy\View;

class SocialServiceProvider extends ServiceProvider
{
    /**
     * Liste des noms de qualification des services fournis.
     * {@internal Permet le chargement différé des services qualifié.}
     * @var string[]
     */
    protected $provides = [
        SocialManagerContract::class,
        DailymotionChannelContract::class,
        FacebookChannelContract::class,
        GooglePlusChannelContract::class,
        InstagramChannelContract::class,
        LinkedinChannelContract::class,
        PinterestChannelContract::class,
        TwitterChannelContract::class,
        ViadeoChannelContract::class,
        VimeoChannelContract::class,
        YoutubeChannelContract::class,
        'social.channel.view-engine',
        'social.view-engine',
    ];

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        events()->listen('wp.booted', function () {
            $this->getContainer()->get(SocialManagerContract::class)->boot();
        });
    }

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share(SocialManagerContract::class, function (): SocialManagerContract {
            return new Social(config('social', []), $this->getContainer());
        });

        $this->registerChannels();
        $this->registerChannelView();
        $this->registerPartials();
        $this->registerView();
    }

    /**
     * Déclaration des pilotes de canaux de réseaux sociaux.
     *
     * @return void
     */
    public function registerChannels(): void
    {
        $this->getContainer()->add(DailymotionChannelContract::class, function (): DailymotionChannelContract {
            return new DailymotionChannel($this->getContainer()->get(SocialManagerContract::class));
        });

        $this->getContainer()->add(FacebookChannelContract::class, function (): FacebookChannelContract {
            return new FacebookChannel($this->getContainer()->get(SocialManagerContract::class));
        });

        $this->getContainer()->add(GooglePlusChannelContract::class, function (): GooglePlusChannelContract {
            return new GooglePlusChannel($this->getContainer()->get(SocialManagerContract::class));
        });

        $this->getContainer()->add(InstagramChannelContract::class, function (): InstagramChannelContract {
            return new InstagramChannel($this->getContainer()->get(SocialManagerContract::class));
        });

        $this->getContainer()->add(LinkedinChannelContract::class, function (): LinkedinChannelContract {
            return new LinkedinChannel($this->getContainer()->get(SocialManagerContract::class));
        });

        $this->getContainer()->add(PinterestChannelContract::class, function (): PinterestChannelContract {
            return new PinterestChannel($this->getContainer()->get(SocialManagerContract::class));
        });

        $this->getContainer()->add(TwitterChannelContract::class, function (): TwitterChannelContract {
            return new TwitterChannel($this->getContainer()->get(SocialManagerContract::class));
        });

        $this->getContainer()->add(ViadeoChannelContract::class, function (): ViadeoChannelContract {
            return new ViadeoChannel($this->getContainer()->get(SocialManagerContract::class));
        });

        $this->getContainer()->add(VimeoChannelContract::class, function (): VimeoChannelContract {
            return new VimeoChannel($this->getContainer()->get(SocialManagerContract::class));
        });

        $this->getContainer()->add(YoutubeChannelContract::class, function (): YoutubeChannelContract {
            return new YoutubeChannel($this->getContainer()->get(SocialManagerContract::class));
        });
    }

    /**
     * Déclaration du gestionnaire d'affichage de canal de réseau social.
     *
     * @return void
     */
    public function registerChannelView(): void
    {
        $this->getContainer()->share('social.channel.view-engine', function () {
            /** @var SocialManagerContract $social */
            $social = $this->getContainer()->get(SocialManagerContract::class);

            return View::getPlatesEngine(array_merge([
                'directory' => $social->resources('views/channel'),
                'factory'   => SocialChannelView::class,
            ]));
        });
    }

    /**
     * Déclaration des pilotes de portions d'affichage.
     *
     * @return void
     */
    public function registerPartials(): void
    {
        $this->getContainer()->add(SocialMenuPartialContract::class, function (): SocialMenuPartialContract {
            return new SocialMenuPartial(
                $this->getContainer()->get(SocialManagerContract::class),
                $this->getContainer()->get(PartialManagerContract::class)
            );
        });

        $this->getContainer()->add(SocialSharePartialContract::class, function (): SocialSharePartialContract {
            return new SocialSharePartial(
                $this->getContainer()->get(SocialManagerContract::class),
                $this->getContainer()->get(PartialManagerContract::class)
            );
        });
    }

    /**
     * Déclaration du gestionnaire d'affichage.
     *
     * @return void
     */
    public function registerView(): void
    {
        $this->getContainer()->share('social.view-engine', function () {
            /** @var SocialManagerContract $social */
            $social = $this->getContainer()->get(SocialManagerContract::class);

            return View::getPlatesEngine([
                'directory' => $social->resources('views'),
            ]);
        });
    }
}