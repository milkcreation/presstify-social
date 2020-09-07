<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Partial;

use Illuminate\Support\Collection;
use tiFy\Contracts\Partial\PartialDriver as PartialDriverContract;
use tiFy\Plugins\Social\SocialAwareTrait;
use tiFy\Plugins\Social\Contracts\{ChannelDriver as ChannelDriverContract};
use tiFy\Partial\PartialDriver;

class SocialMenuPartial extends PartialDriver
{
    use SocialAwareTrait;

    /**
     * Alias de qualification dans le gestionnaire.
     * @var string
     */
    private $alias = 'social-menu';

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        parent::boot();

        $this->set(
            'viewer.directory', $this->social()->getResources()->path('/views/partial/menu')
        );
    }

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'classes' => [

            ]
        ]);
    }

    /**
     * @inheritDoc
     */
    public function parse(): PartialDriverContract
    {
        parent::parse();

        $defaultClasses = [
            'item'    => 'Social-menuChannel',
            'items'   => 'Social-menuChannels',
            'link'    => '%s'
        ];

        foreach ($defaultClasses as $k => $v) {
            $this->set("classes.{$k}", sprintf($this->get("classes.{$k}", '%s'), $v));
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        if (!$this->get('items', [])) {
            $this->set([
                'items' => (new Collection($this->social()->getChannels()))
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