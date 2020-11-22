<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Metabox;

use BadMethodCallException, Exception;
use tiFy\Metabox\MetaboxView;
use tiFy\Plugins\Social\Contracts\ChannelView as ChannelViewContract;

/**
 * @method string getIcon()
 * @method string getStatus()
 * @method bool getTitle()
 * @method bool isActive()
 * @method bool isSharer()
 */
class ChannelMetaboxView extends MetaboxView implements ChannelViewContract
{
    /**
     * @inheritDoc
     */
    public function __call($name, $args)
    {
        $channelMixins = [
            'getIcon',
            'getStatus',
            'getTitle',
            'isActive',
            'isSharer'
        ];

        if (in_array($name, $channelMixins)) {
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