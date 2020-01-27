<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Partial;

use Illuminate\Support\Collection;
use tiFy\Plugins\Social\Contracts\ChannelDriver as ChannelDriverContract;
use tiFy\Plugins\Social\Contracts\Social;
use tiFy\Partial\PartialDriver;

class SocialMenu extends PartialDriver
{
    /**
     * Instance du gestionnaire de réseaux sociaux.
     * @var Social
     */
    protected $social;

    /**
     * CONSTRUCTEUR.
     *
     * @param Social $social
     *
     * @return void
     */
    public function __construct(Social $social)
    {
        $this->social = $social;
    }

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        parent::boot();

        $this->set(
            'viewer.directory', $this->social->getResources()->path('/views/partial/menu')
        );
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        if (!$this->get('items', [])) {
            $this->set([
                'items' => (new Collection($this->social->getChannels()))
                    ->filter(function (ChannelDriverContract $channel) {
                        return $channel->isActive() && $channel->hasUri();
                    })
                    ->sortBy(function (ChannelDriverContract $channel) {
                        return $channel->getOrder();
                    })->all(),
            ]);
        }

        return parent::render();
    }
}