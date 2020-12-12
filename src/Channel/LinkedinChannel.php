<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Plugins\Social\Contracts\LinkedinChannel as LinkedinChannelContract;
use tiFy\Plugins\Social\Contracts\SocialChannelDriver as SocialChannelDriverContract;
use tiFy\Support\Proxy\Url;
use tiFy\Support\Str;

class LinkedinChannel extends SocialChannelDriver implements LinkedinChannelContract
{
    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = 'linkedin';

    /**
     * Url de partage et indicateur de partage possible sur le réseau.
     * @see https://docs.microsoft.com/en-us/linkedin/consumer/integrations/self-serve/share-on-linkedin?context=linkedin/consumer/context
     * @var string
     */
    protected $sharer = 'https://www.linkedin.com/shareArticle';

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
    public function setPostShare($post): SocialChannelDriverContract
    {
        $this->share_params = [
            'mini'    => 'true',
            'url'     => $post->getPermalink(),
            'text'    => strip_tags($post->getTitle()),
            'summary' => Str::limit($post->getExcerpt() ?: $post->getContent(), 256, ''),
            'source'  => home_url('/'),
        ];

        return $this;
    }
}