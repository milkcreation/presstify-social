<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Plugins\Social\Contracts\Social;

class FacebookChannel extends ChannelDriver
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
        parent::__construct('facebook', $attrs, $social);
    }

    /**
     * @inheritDoc
     */
    public function getDeeplink(): string
    {
        if ($id = $this->get('profile_id', '')) {
            if ($this->isAndroidOS()) {
                return "intent://page/{$id}?referrer=app_link#Intent;package=com.facebook.katana;scheme=fb;end";
            } elseif ($this->isIOS()) {
                return "fb://page/?id={$id}";
            }
        }

        return '';
    }
}