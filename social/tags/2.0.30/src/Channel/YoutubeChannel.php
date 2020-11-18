<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Support\Proxy\Url;

class YoutubeChannel extends ChannelDriver
{
    /**
     * @param array $attrs Attributs de configuration.
     *
     * @return void
     */
    public function __construct(array $attrs = [])
    {
        parent::__construct('youtube', $attrs);
    }

    /**
     * @inheritDoc
     */
    public function getDeeplink(): string
    {
        $uri = Url::set($this->get('uri'))->get();
        $id = $uri->getPath();

        if ($this->isAndroidOS()) {
            return "intent://www.youtube.com{$id}#Intent;package=com.google.android.youtube;scheme=https;end";
        } elseif ($this->isIOS()) {
            return "vnd.youtube://www.youtube.com{$id}";
        }

        return '';
    }
}