<?php

namespace tiFy\Plugins\Social\Networks;

use tiFy\View\ViewController;
use tiFy\Plugins\Social\Contracts\NetworkViewer as NetworkViewerContract;

/**
 * Class NetworkItemBaseTemplate
 * @package tiFy\Plugins\Social\NetworkItems
 *
 * @method string getIcon()
 * @method string getStatus()
 * @method bool getTitle()
 * @method bool isActive()
 */
class NetworkViewer extends ViewController implements NetworkViewerContract
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
     * Délégation d'appel des méthodes du réseau associé.
     *
     * @param string $name Nom de la méthode à appeler.
     * @param array $arguments Liste des variables passées en argument.
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (in_array($name, $this->mixins)) {
            return call_user_func_array(
                [$this->engine->get('network'), $name],
                $arguments
            );
        }
        return null;
    }
}