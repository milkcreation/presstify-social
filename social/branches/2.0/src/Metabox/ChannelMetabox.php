<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Metabox;

use tiFy\Metabox\MetaboxDriver;
use tiFy\Plugins\Social\SocialAwareTrait;
use tiFy\Plugins\Social\Contracts\ChannelDriver;

class ChannelMetabox extends MetaboxDriver
{
    use SocialAwareTrait;

    /**
     * Instance du réseau associé.
     * @var ChannelDriver
     */
    protected $channel;

    /**
     * @inheritDoc
     */
    public function defaults(): array
    {
        return array_merge(parent::defaults(), [
            'name'     => $this->channel->getOptionName(),
            'title'    => $this->channel->getTitle(),
            'value'    => $this->channel->all()
        ]);
    }

    /**
     * Définition du réseau associé
     *
     * @param ChannelDriver $channel
     *
     * @return $this
     */
    public function setChannel(ChannelDriver $channel): self
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
        $directory = $this->social()->resources('views/metabox/' . $this->channel->getName());

        return is_dir($directory) ? $directory : $this->social()->resources('views/metabox');
    }
}