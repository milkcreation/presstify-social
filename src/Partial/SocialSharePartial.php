<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Partial;

use Illuminate\Support\Collection;
use tiFy\Plugins\Social\Contracts\ChannelDriver as ChannelDriverContract;
use tiFy\Plugins\Social\SocialAwareTrait;
use tiFy\Partial\PartialDriver;
use tiFy\Wordpress\Query\QueryPost;

class SocialSharePartial extends PartialDriver
{
    use SocialAwareTrait;

    /**
     * Alias de qualification dans le gestionnaire.
     * @var string
     */
    private $alias = 'social-share';

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        parent::boot();

        $this->set('viewer.directory', $this->social()->resources('/views/partial/share'));
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'post'      => null,
            /**
             * Paramètres personnalisé de l'url de partage selon le réseau .
             * @var array
             */
            'channel'   => []
        ]);
    }

    public function render(): string
    {
        if ($post = ($p = $this->get('post', null)) instanceof QueryPost ? $p : QueryPost::create($p)) {
            $this->set([
                'items' => (new Collection($this->social()->getChannels()))
                    ->filter(function (ChannelDriverContract $channel) use ($post) {
                        if($channel->isActive() && $channel->hasShare()) {
                            $channel->setPostShare($post);

                            return true;
                        }

                        return false;
                    })
                    ->sortBy(function (ChannelDriverContract $channel) {
                        return $channel->getOrder();
                    })->all(),
                'post' => $post
            ]);

            return parent::render();
        } else {
            return '';
        }
    }
}