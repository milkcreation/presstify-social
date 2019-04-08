<?php

namespace tiFy\Plugins\Social\Admin\Metabox\Options\NetworkOptions;

use tiFy\Metabox\MetaboxWpOptionsController;
use tiFy\Plugins\Social\Networks\NetworkViewer;

class NetworkOptions extends MetaboxWpOptionsController
{
    /**
     * @inheritdoc
     */
    public function boot()
    {
        $this->viewer()
            ->setController(NetworkViewer::class)
            ->set('network', $this->get('network'));
    }

    /**
     * {@inheritdoc}
     */
    public function content($args = null, $null1 = null, $null2 = null)
    {
        return $this->viewer('content', $this->get('network')->all());
    }

    /**
     * {@inheritdoc}
     */
    public function header($args = null, $null1 = null, $null2 = null)
    {
        return $this->viewer('header', $this->get('network')->all());
    }
}