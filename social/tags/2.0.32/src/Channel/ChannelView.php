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
    public function __call($name, $args)
    {
        if (in_array($name, $this->mixins)) {
            try {
                return call_user_func_array([$this->engine->params('channel'), $name], $args);
            } catch (Exception $e) {
                throw new BadMethodCallException(sprintf(
                    __CLASS__ . ' throws an exception during the method call [%s] with message : %s',
                    $name, $e->getMessage()
                ));
            }
        } else {
            return parent::__call($name, $args);
        }
    }
}