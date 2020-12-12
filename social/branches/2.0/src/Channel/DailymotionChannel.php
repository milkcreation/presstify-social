<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Plugins\Social\Contracts\DailymotionChannel as DailymotionChannelContract;

class DailymotionChannel extends SocialChannelDriver implements DailymotionChannelContract
{
    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = 'dailymotion';
}