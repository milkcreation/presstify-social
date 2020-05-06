<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Support\Proxy\Url;
use tiFy\Plugins\Social\Contracts\Social;

class YoutubeChannel extends ChannelDriver
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
        parent::__construct('youtube', $attrs, $social);
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