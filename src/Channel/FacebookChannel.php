<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Plugins\Social\Contracts\ChannelDriver as ChannelDriverContract;

class FacebookChannel extends ChannelDriver
{
    /**
     * Url de partage et indicateur de partage possible sur le réseau.
     * @var string
     */
    protected $sharer = 'https://www.facebook.com/sharer.php';

    /**
     * @param array $attrs Attributs de configuration.
     *
     * @return void
     */
    public function __construct(array $attrs = [])
    {
        parent::__construct('facebook', $attrs);
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

    /**
     * @inheritDoc
     */
    public function setPostShare($post): ChannelDriverContract
    {
        $this->share_params = [
            'u' => $post->getPermalink()
        ];

        return $this;
    }
}