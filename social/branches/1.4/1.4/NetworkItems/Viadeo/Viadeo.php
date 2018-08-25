<?php

namespace tiFy\Plugins\Social\NetworkItems\Viadeo;

use tiFy\Kernel\Tools;
use tiFy\Plugins\Social\NetworkItems\AbstractNetworkItem;

class Viadeo extends AbstractNetworkItem
{
    /**
     * {@inheritdoc}
     */
    public function getIcon()
    {
        $icon = Tools::File()->svgGetContents(__DIR__ . '/img/logo.svg');

        return $icon;
    }
}