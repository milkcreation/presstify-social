<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Proxy;

use tiFy\Support\Proxy\AbstractProxy;
use tiFy\Plugins\Social\Contracts\SocialChannelDriver;
use tiFy\Plugins\Social\Contracts\Social as SocialManager;

/**
 * @method static SocialChannelDriver addChannel(string $name, SocialChannelDriver|array|string $attrs)
 * @method static SocialChannelDriver|null getChannel(string $name)
 * @method static string getChannelLink(string $name, array $attrs = [])
 * @method static SocialChannelDriver[]|array getChannels()
 */
class Social extends AbstractProxy
{
    /**
     * {@inheritDoc}
     *
     * @return SocialManager|object
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
        return SocialManager::class;
    }
}