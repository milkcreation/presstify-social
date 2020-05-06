<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Support\Proxy\Url;
use tiFy\Plugins\Social\Contracts\Social;

class TwitterChannel extends ChannelDriver
{
    /**
     * CONSTRUCTEUR.
     *
     * @param array $attrs Attributs de configuration.
     * @param Social $social Instance du controleur principal.
     *
     * @return void
     */
    public function __construct(array $attrs, Social $social)
    {
        parent::__construct('twitter', $attrs, $social);
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
}