<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Plugins\Social\Contracts\GooglePlusChannel as GooglePlusChannelContract;

class GooglePlusChannel extends SocialChannelDriver implements GooglePlusChannelContract
{
    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = 'google-plus';
}