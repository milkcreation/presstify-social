<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Partial;

use tiFy\Contracts\Partial\Partial as PartialManagerContract;
use tiFy\Plugins\Social\SocialAwareTrait;
use tiFy\Plugins\Social\Contracts\Social as SocialManagerContract;
use tiFy\Plugins\Social\Contracts\SocialPartialDriver as SocialPartialDriverContract;
use tiFy\Partial\PartialDriver;

abstract class AbstractSocialPartialDriver extends PartialDriver implements SocialPartialDriverContract
{
    use SocialAwareTrait;

    /**
     * @param SocialManagerContract $socialManager
     * @param PartialManagerContract $partialManager
     */
    public function __construct(SocialManagerContract $socialManager, PartialManagerContract $partialManager)
    {
        $this->setSocialManager($socialManager);

        parent::__construct($partialManager);
    }
}