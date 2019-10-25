<?php

namespace tiFy\Plugins\Social\Networks\Viadeo;

use tiFy\Plugins\Social\Networks\NetworkFactory;
use tiFy\Plugins\Social\Contracts\Social;

class Viadeo extends NetworkFactory
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
        parent::__construct('viadeo', $attrs, $social);
    }
}