<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

class ViadeoChannel extends ChannelDriver
{
    /**
     * @param array $attrs Attributs de configuration.
     *
     * @return void
     */
    public function __construct(array $attrs = [])
    {
        parent::__construct('viadeo', $attrs);
    }
}