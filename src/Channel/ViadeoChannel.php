<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Plugins\Social\Contracts\ViadeoChannel as ViadeoChannelContract;

class ViadeoChannel extends SocialChannelDriver implements ViadeoChannelContract
{
    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = 'viadeo';
}