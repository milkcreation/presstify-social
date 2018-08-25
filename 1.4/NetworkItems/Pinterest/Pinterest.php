<?php

namespace tiFy\Plugins\Social\NetworkItems\Pinterest;

use tiFy\Plugins\Social\NetworkItems\AbstractNetworkItem;

class Pinterest extends AbstractNetworkItem
{
    /**
     * {@inheritdoc}
     */
    public function getIcon()
    {
        return "<i class=\"ion ion-logo-pinterest\"></i>";
    }
}