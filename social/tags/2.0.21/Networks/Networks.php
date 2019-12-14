<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Networks;

use tiFy\Plugins\Social\{Contracts\NetworkFactory, Contracts\Social};
use tiFy\Support\Proxy\Metabox;

class Networks
{
    /**
     * Instance du plugin Social.
     * @var Social|null
     */
    protected $social;

    /**
     * CONSTRUCTEUR.
     *
     * @param Social $social Instance du controleur principal.
     *
     * @return void
     */
    public function __construct(Social $social)
    {
        $this->social = $social;

        $has_item = false;

        foreach ($this->social->all() as $item) {
            /** @var NetworkFactory $item */
            if ($item->isAdmin()) {
                $has_item = true;
                Metabox::add("Social-{$item->getName()}", [
                    'driver' => (new NetworkMetabox())->setSocial($this->social)->setNetwork($item),
                    'parent' => 'Social',
                ])
                    ->setScreen('tify_options@options')
                    ->setContext('tab');
            }
        }

        if ($has_item) {
            Metabox::add('Social', [
                'title'    => __('Réseaux sociaux', 'tify'),
                'position' => 12,
            ])
                ->setScreen('tify_options@options')
                ->setContext('tab');

            register_setting('tify_options', 'tify_social_share');
        }
    }
}