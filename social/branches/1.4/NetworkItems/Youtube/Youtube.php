<?php

namespace tiFy\Plugins\Social\NetworkItems\YouTube;

use tiFy\Plugins\Social\NetworkItems\AbstractNetworkItem;

class Youtube extends AbstractNetworkItem
{
    /**
     * {@inheritdoc}
     */
    public function getIcon()
    {
        return "<i class=\"ion ion-logo-youtube\"></i>";
    }
}