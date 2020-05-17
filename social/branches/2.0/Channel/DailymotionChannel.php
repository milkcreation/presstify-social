<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

class DailymotionChannel extends ChannelDriver
{
    /**
     * CONSTRUCTEUR.
     *
     * @param array $attrs Attributs de configuration.
     *
     * @return void
     */
    public function __construct(array $attrs)
    {
        parent::__construct('dailymotion', $attrs);
    }
}