<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Proxy;

use tiFy\Support\Proxy\AbstractProxy;
use tiFy\Plugins\Social\Contracts\ChannelDriver;
use tiFy\Plugins\Social\Contracts\Social as SocialContract;

/**
 * @method static ChannelDriver addChannel(string $name, ChannelDriver|array|string $attrs)
 * @method static ChannelDriver|null getChannel(string $name)
 * @method static string getChannelLink(string $name, array $attrs = [])
 * @method static ChannelDriver[]|array getChannels()
 */
class Social extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return SocialContract
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @inheritDoc
     */
    public static function getInstanceIdentifier(): string
    {
        return 'social';
    }
}