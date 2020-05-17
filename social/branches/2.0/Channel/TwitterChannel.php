<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Plugins\Social\Contracts\ChannelDriver as ChannelDriverContract;
use tiFy\Support\Proxy\Url;

class TwitterChannel extends ChannelDriver
{
    /**
     * Url de partage et indicateur de partage possible sur le réseau.
     * @see https://developer.twitter.com/en/docs/twitter-for-websites/tweet-button/guides/web-intent
     * @var string
     */
    protected $sharer = 'https://twitter.com/intent/tweet';

    /**
     * CONSTRUCTEUR.
     *
     * @param array $attrs Attributs de configuration.
     *
     * @return void
     */
    public function __construct(array $attrs)
    {
        parent::__construct('twitter', $attrs);
    }

    /**
     * @inheritDoc
     */
    public function getDeeplink(): string
    {
        $uri = Url::set($this->get('uri'))->get();
        $id = ltrim(rtrim($uri->getPath(), '/'), '/');

        if ($this->isAndroidOS()) {
            return "intent://twitter.com/{$id}#Intent;package=com.twitter.android;scheme=https;end";
        } elseif ($this->isIOS()) {
            return "twitter://user?screen_name={$id}";
        }

        return '';
    }

    /**
     * @inheritDoc
     */
    public function setPostShare($post): ChannelDriverContract
    {
        $this->share_params = [
            'text' => str_replace('|', '', strip_tags($post->getTitle())),
            'url'  => $post->getPermalink(),
        ];

        return $this;
    }
}