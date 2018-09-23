<?php

namespace tiFy\Plugins\Social\NetworkItems;

use tiFy\Kernel\Templates\TemplateController;
use tiFy\Plugins\Social\Contracts\NetworkItemViewInterface;

/**
 * Class NetworkItemBaseTemplate
 * @package tiFy\Plugins\Social\NetworkItems
 *
 * @method string getIcon()
 * @method string getStatus()
 * @method bool getTitle()
 * @method bool isActive()
 */
class NetworkItemView extends TemplateController implements NetworkItemViewInterface
{
    /**
     * Liste des méthodes héritées.
     * @var array
     */
    protected $mixins = [
        'getIcon',
        'getStatus',
        'getTitle',
        'isActive'
    ];

    /**
     * Translation d'appel des méthodes de l'application associée.
     *
     * @param string $name Nom de la méthode à appeler.
     * @param array $arguments Liste des variables passées en argument.
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (in_array($name, $this->mixins)) :
            return call_user_func_array(
                [$this->engine->get('item'), $name],
                $arguments
            );
        endif;
    }
}