<?php declare(strict_types=1);

namespace tiFy\Plugins\Social;

use tiFy\Container\ServiceProvider;
use tiFy\Plugins\Social\Contracts\ChannelDriver as ChannelDriverContract;
use tiFy\Plugins\Social\Partial\SocialMenu;
use tiFy\Plugins\Social\Channel\{
    DailymotionChannel,
    FacebookChannel,
    GooglePlusChannel,
    InstagramChannel,
    LinkedinChannel,
    PinterestChannel,
    TwitterChannel,
    ViadeoChannel,
    VimeoChannel,
    YoutubeChannel
};
use tiFy\Support\Proxy\Partial;

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
    ];

    /**
     * Liste des nom de qualification des réseaux disponibles
     * @var string[]
     */
    private $availableChannels = [
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
     * @inheritDoc
     */
    public function boot(): void
    {
        add_action('after_setup_theme', function () {
            /** @var Social $social */
            $social = $this->getContainer()->get('social');

            $registered = [];
            if ($channels = config('social.channel', [])) {
                foreach ($channels as $k => $v) {
                    $registered[] = is_numeric($k) ? $v : $k;
                }
                $registered = array_intersect($registered, $this->availableChannels);
            } else {
                $registered = $this->availableChannels;
            }

            if ($registered) {
                foreach ($registered as $name) {
                    $social->addChannel($name, array_merge(['driver' => $name], config("social.channel.{$name}", [])));
                }
            }

            Partial::register('social-menu', new SocialMenu($social));
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

        $this->registerChannels();
    }

    /**
     * Déclaration des controleurs de réseaux.
     *
     * @return void
     */
    public function registerChannels(): void
    {
        $this->getContainer()->add('social.channel.dailymotion', function (array $attrs): ChannelDriverContract {
            return new DailymotionChannel($attrs, $this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.facebook', function (array $attrs): ChannelDriverContract {
            return new FacebookChannel($attrs, $this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.google-plus', function (array $attrs): ChannelDriverContract {
            return new GooglePlusChannel($attrs, $this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.instagram', function (array $attrs): ChannelDriverContract {
            return new InstagramChannel($attrs, $this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.linkedin', function (array $attrs): ChannelDriverContract {
            return new LinkedinChannel($attrs, $this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.pinterest', function (array $attrs): ChannelDriverContract {
            return new PinterestChannel($attrs, $this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.twitter', function (array $attrs): ChannelDriverContract {
            return new TwitterChannel($attrs, $this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.viadeo', function (array $attrs): ChannelDriverContract {
            return new ViadeoChannel($attrs, $this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.vimeo', function (array $attrs): ChannelDriverContract {
            return new VimeoChannel($attrs, $this->getContainer()->get('social'));
        });

        $this->getContainer()->add('social.channel.youtube', function (array $attrs): ChannelDriverContract {
            return new YoutubeChannel($attrs, $this->getContainer()->get('social'));
        });
    }
}