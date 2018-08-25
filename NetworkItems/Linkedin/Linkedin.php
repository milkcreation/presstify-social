<?php

namespace tiFy\Plugins\Social\NetworkItems\Linkedin;

use tiFy\Plugins\Social\NetworkItems\AbstractNetworkItem;

class Linkedin extends AbstractNetworkItem
{
    /**
     * {@inheritdoc}
     */
    public function getIcon()
    {
        return "<i class=\"ion ion-logo-linkedin\"></i>";
    }
}