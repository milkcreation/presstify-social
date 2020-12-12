<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Contracts;

use tiFy\Contracts\View\PlatesFactory;

/**
 * @method string getIcon()
 * @method string getStatus()
 * @method bool getTitle()
 * @method bool isActive()
 */
interface SocialChannelView extends PlatesFactory
{

}