<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Contracts\Metabox\MetaboxDriver as MetaboxDriverContract;
use tiFy\Metabox\MetaboxDriver;
use tiFy\Plugins\Social\SocialAwareTrait;
use tiFy\Plugins\Social\Contracts\ChannelDriver;

class ChannelMetaboxDriver extends MetaboxDriver
{
    use SocialAwareTrait;

    /**
     * Instance du réseau associé.
     * @var ChannelDriver
     */
    protected $channel;

    /**
     * @param ChannelDriver $channel Instance du réseau associé.
     *
     * @return void
     */
    public function __construct(ChannelDriver $channel)
    {
        $this->set('channel', $this->channel = $channel);
    }

    /**
     * @inheritDoc
     */
    public function parse(): MetaboxDriverContract
    {
        $directory = $this->social()->resources('views/metabox/' . $this->channel->getName());
        $this->set('viewer.directory', is_dir($directory)
            ? $directory : $this->social()->resources('views/metabox')
        );

        return parent::parse();
    }
}