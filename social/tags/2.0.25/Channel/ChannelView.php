<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use BadMethodCallException;
use Exception;
use tiFy\Plugins\Social\Contracts\ChannelView as ChannelViewContract;
use tiFy\View\Factory\PlatesFactory;

/**
 * @method string getIcon()
 * @method string getStatus()
 * @method bool getTitle()
 * @method bool isActive()
 */
class ChannelView extends PlatesFactory implements ChannelViewContract
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
     * @inheritDoc
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, $this->mixins)) {
            try {
                return call_user_func_array([$this->engine->params('channel'), $method], $parameters);
            } catch (Exception $e) {
                throw new BadMethodCallException(
                    sprintf(
                        __('La méthode [%s] de l\'instance du réseau social n\'est pas disponible.', 'tify'),
                        $method
                    )
                );
            }
        } else {
            return parent::__call($method, $parameters);
        }
    }
}