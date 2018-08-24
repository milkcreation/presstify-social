<?php

namespace tiFy\Plugins\Social;

use tiFy\App\Container\AppServiceProvider;
use tiFy\Plugins\Social\Social;
use tiFy\Plugins\Social\Contracts\NetworkItemInterface;

class SocialServiceProvider extends AppServiceProvider
{
    /**
     * Liste des réseaux déclarés.
     * @var array
     */
    protected $networkItems = [];

    /**
     * {@inheritdoc}
     */
    protected $singletons = [
        Social::class
    ];

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $social = $this->app->resolve(Social::class);

        foreach (config('social.networks', []) as $name => $attrs) :
            if (!$abstract = $social->getAbstract($name)) :
                continue;
            endif;

            $concrete = $this->getContainer()
                ->singleton($abstract)
                ->build([$name, $attrs, $this->app]);

            $this->networkItems[$name] = $concrete;
        endforeach;
    }

    /**
     * Récupération de la liste des réseaux déclarés.
     *
     * @return NetworkItemInterface[]
     */
    public function getNetworkItems()
    {
        return $this->networkItems;
    }
}