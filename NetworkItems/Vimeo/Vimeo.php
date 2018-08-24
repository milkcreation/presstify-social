<?php

namespace tiFy\Plugins\Social\NetworkItems\Vimeo;

use tiFy\Plugins\Social\NetworkItems\AbstractNetworkItem;

class Vimeo extends AbstractNetworkItem
{
    /**
     * {@inheritdoc}
     */
    public function getIcon()
    {
        return "<i class=\"ion ion-logo-vimeo\"></i>";
    }
}