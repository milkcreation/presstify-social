<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Metabox;

use tiFy\Metabox\MetaboxDriver;
use tiFy\Plugins\Social\SocialAwareTrait;
use tiFy\Plugins\Social\Contracts\SocialChannelDriver;

class ChannelMetabox extends MetaboxDriver
{
    use SocialAwareTrait;

    /**
     * Instance du réseau associé.
     * @var SocialChannelDriver
     */
    protected $channel;

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'name'     => $this->channel->getOptionName(),
            'title'    => $this->channel->getTitle()
        ]);
    }

    /**
     * @inheritDoc
     */
    public function defaultValue(): array
    {
        return $this->channel->all();
    }

    /**
     * Définition du réseau associé
     *
     * @param SocialChannelDriver $channel
     *
     * @return $this
     */
    public function setChannel(SocialChannelDriver $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function view(?string $view = null, array $data = [])
    {
        if (is_null($this->viewEngine)) {
            $this->viewEngine = parent::view();

            $this->viewEngine->params([
                'factory' => ChannelMetaboxView::class,
                'channel' => $this->channel
            ]);
        }

        if (func_num_args() === 0) {
            return $this->viewEngine;
        }

        return $this->viewEngine->render($view, $data);
    }

    /**
     * @inheritDoc
     */
    public function viewDirectory(): string
    {
        $directory = $this->socialManager()->resources('views/metabox/' . $this->channel->getName());

        return is_dir($directory) ? $directory : $this->socialManager()->resources('views/metabox');
    }
}