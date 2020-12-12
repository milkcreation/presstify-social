<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Partial;

use Illuminate\Support\Collection;
use tiFy\Contracts\Partial\PartialDriver as PartialDriverContract;
use tiFy\Plugins\Social\Contracts\SocialChannelDriver;
use tiFy\Plugins\Social\Contracts\SocialSharePartial as SocialSharePartialContract;
use tiFy\Wordpress\Query\QueryPost;

class SocialSharePartial extends AbstractSocialPartialDriver implements SocialSharePartialContract
{
    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(parent::defaultParams(), [
            'post'    => null,
            /**
             * Paramètres personnalisé de l'url de partage selon le réseau .
             * @var array
             */
            'channel' => [],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): PartialDriverContract
    {
        $this->set('viewer.directory', $this->socialManager()->resources('/views/partial/menu'));

        return parent::parseParams();
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        if ($post = ($p = $this->get('post', null)) instanceof QueryPost ? $p : QueryPost::create($p)) {
            $this->set([
                'items' => (new Collection($this->socialManager()->getChannels()))
                    ->filter(function (SocialChannelDriver $channel) use ($post) {
                        if ($channel->isActive() && $channel->hasShare()) {
                            $channel->setPostShare($post);

                            return true;
                        }

                        return false;
                    })
                    ->sortBy(function (SocialChannelDriver $channel) {
                        return $channel->getOrder();
                    })->all(),
                'post'  => $post,
            ]);

            return parent::render();
        } else {
            return '';
        }
    }
}