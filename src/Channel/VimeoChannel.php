<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Plugins\Social\Contracts\VimeoChannel as VimeoChannelContract;

class VimeoChannel extends SocialChannelDriver implements VimeoChannelContract
{
    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = 'vimeo';
}