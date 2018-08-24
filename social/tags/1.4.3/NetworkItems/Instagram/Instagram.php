<?php

namespace tiFy\Plugins\Social\NetworkItems\Instagram;

use tiFy\Plugins\Social\NetworkItems\AbstractNetworkItem;

class Instagram extends AbstractNetworkItem
{
    /**
     * {@inheritdoc}
     */
    public function getIcon()
    {
        return "<i class=\"ion ion-logo-instagram\"></i>";
    }
}