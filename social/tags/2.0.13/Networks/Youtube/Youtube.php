<?php

namespace tiFy\Plugins\Social\Networks\YouTube;

use tiFy\Plugins\Social\Networks\NetworkFactory;
use tiFy\Plugins\Social\Contracts\Social;

class Youtube extends NetworkFactory
{
    /**
     * CONSTRUCTEUR.
     *
     * @param array $attrs Attributs de configuration.
     * @param Social $social Instance du controleur principal.
     *
     * @return void
     */
    public function __construct($attrs, Social $social)
    {
        parent::__construct('youtube', $attrs, $social);
    }
}