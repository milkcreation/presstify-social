<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Plugins\Social\Contracts\Social;
use tiFy\Support\Proxy\Url;

class LinkedinChannel extends ChannelDriver
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
        parent::__construct('linkedin', $attrs, $social);
    }

    /**
     * @inheritDoc
     */
    public function getDeeplink(): string
    {
        $uri = Url::set($this->get('uri'))->get();
        $id = $uri->getPath();

        if ($this->isAndroidOS()) {
            return "intent://www.linkedin.com/company{$id}#Intent;package=com.linkedin.android;scheme=https;end";
        } elseif ($this->isIOS()) {
            return $this->get('uri'); //"linkedin://company{$id}";
        }

        return '';
    }
}