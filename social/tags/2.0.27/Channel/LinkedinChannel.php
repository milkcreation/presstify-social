<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Plugins\Social\Contracts\ChannelDriver as ChannelDriverContract;
use tiFy\Support\{Str, Proxy\Url};

class LinkedinChannel extends ChannelDriver
{
    /**
     * Url de partage et indicateur de partage possible sur le réseau.
     * @see https://docs.microsoft.com/en-us/linkedin/consumer/integrations/self-serve/share-on-linkedin?context=linkedin/consumer/context
     * @var string
     */
    protected $sharer = 'https://www.linkedin.com/shareArticle';

    /**
     * CONSTRUCTEUR.
     *
     * @param array $attrs Attributs de configuration.
     *
     * @return void
     */
    public function __construct(array $attrs)
    {
        parent::__construct('linkedin', $attrs);
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

    /**
     * @inheritDoc
     */
    public function setPostShare($post): ChannelDriverContract
    {
        $this->share_params = [
            'mini'   => 'true',
            'url'    => $post->getPermalink(),
            'text'   => strip_tags($post->getTitle()),
            'summary'=> Str::limit($post->getExcerpt() ? : $post->getContent(), 256, ''),
            'source' => home_url('/'),
        ];

        return $this;
    }
}