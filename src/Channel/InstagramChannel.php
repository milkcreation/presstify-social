<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Support\Proxy\Url;

class InstagramChannel extends ChannelDriver
{
    /**
     * @param array $attrs Attributs de configuration.
     *
     * @return void
     */
    public function __construct(array $attrs = [])
    {
        parent::__construct('instagram', $attrs);
    }

    /**
     * @inheritDoc
     */
    public function getDeeplink(): string
    {
        $uri = Url::set($this->get('uri'))->get();
        $id = ltrim(rtrim($uri->getPath(), '/'), '/');

        if ($this->isAndroidOS()) {
            return "intent://instagram.com/_u/{$id}/#Intent;package=com.instagram.android;scheme=https;end";
        } elseif ($this->isIOS()) {
            return "instagram://user?username={$id}";
        }

        return '';
    }
}