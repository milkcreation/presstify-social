<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Networks;

use tiFy\Plugins\Social\Contracts\{Social, NetworkFactory};
use tiFy\Metabox\MetaboxDriver;

class NetworkMetabox extends MetaboxDriver
{
    /**
     * Instance du gestionnaire du plugin social
     * @var Social|null
     */
    protected $social;

    /**
     * @inheritDoc
     */
    public function setSocial(Social $social): self
    {
        $this->social = $social;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setNetwork(NetworkFactory $network): self
    {
        $this->set('network', $network);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function viewer(?string $view = null, array $data = [])
    {
        if (!$this->viewer) {
            $this->viewer = $this->social->resolve('networks.viewer', $this->get('network'));
            $this->viewer->setDirectory($this->viewer->getDirectory() . '/metabox');
        }

        if (func_num_args() === 0) {
            return $this->viewer;
        }

        return $this->viewer->make("_override::{$view}", $data);
    }
}