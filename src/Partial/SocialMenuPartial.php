<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Partial;

use Illuminate\Support\Collection;
use tiFy\Contracts\Partial\PartialDriver as PartialDriverContract;
use tiFy\Plugins\Social\Contracts\SocialChannelDriver;
use tiFy\Plugins\Social\Contracts\SocialMenuPartial as SocialMenuPartialContract;

class SocialMenuPartial extends AbstractSocialPartialDriver implements SocialMenuPartialContract
{
    /**
     * @inheritDoc
     */
    public function defaultParams(): array
    {
        return array_merge(parent::defaultParams(), [
            'classes' => []
        ]);
    }

    /**
     * @inheritDoc
     */
    public function parseParams(): PartialDriverContract
    {
        $this->set('viewer.directory', $this->socialManager()->resources('/views/partial/menu'));

        parent::parseParams();

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
                'items' => (new Collection($this->socialManager()->getChannels()))
                    ->filter(function (SocialChannelDriver $channel) {
                        return $channel->isActive() && $channel->hasUri();
                    })
                    ->sortBy(function (SocialChannelDriver $channel) {
                        return $channel->getOrder();
                    })->all(),
            ]);
        }

        return parent::render();
    }
}