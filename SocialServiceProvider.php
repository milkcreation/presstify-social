<?php

namespace tiFy\Plugins\Social;

use tiFy\App\Container\AppServiceProvider;
use tiFy\Plugins\Social\Social;
use tiFy\Plugins\Social\Admin\Options;
use tiFy\Plugins\Social\Contracts\NetworkItemInterface;

class SocialServiceProvider extends AppServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $singletons = [
        Social::class,
        Options::class
    ];

    /**
     * Liste des réseaux déclarés.
     * @var array
     */
    protected $networkItems = [];

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $social = $this->app->resolve(Social::class);

        $networkItems = config()->has('social.networks')
            ? config('social.networks', [])
            : [
                'dailymotion' => [],
                'facebook'    => [],
                'google-plus' => [],
                'instagram'   => [],
                'linkedin'    => [],
                'pinterest'   => [],
                'twitter'     => [],
                'viadeo'      => [],
                'vimeo'       => [],
                'youtube'     => [],
            ];

        foreach ($networkItems as $name => $attrs) :
            if (!$abstract = $social->getAbstract($name)) :
                continue;
            endif;

            $concrete = $this->app
                ->singleton($abstract)
                ->build([$name, $attrs, $social]);

            $this->networkItems[$name] = $concrete;
        endforeach;

        $this->app->resolve(Options::class, [$social]);
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