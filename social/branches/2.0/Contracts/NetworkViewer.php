<?php

namespace tiFy\Plugins\Social\Contracts;

use tiFy\Contracts\View\ViewController;

/**
 * Interface NetworkViewer
 * @package tiFy\Plugins\Social\Contracts
 *
 * @method string getIcon()
 * @method string getStatus()
 * @method bool getTitle()
 * @method bool isActive()
 */
interface NetworkViewer extends ViewController
{

}