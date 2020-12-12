<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Plugins\Social\Contracts\PinterestChannel as PinterestChannelContract;

class PinterestChannel extends SocialChannelDriver implements PinterestChannelContract
{
    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = 'pinterest';
}