<?php

namespace tiFy\Plugins\Social\Networks;

use tiFy\Contracts\Metabox\MetaboxManager;
use tiFy\Plugins\Social\Admin\Metabox\Options\NetworkOptions\NetworkOptions;
use tiFy\Plugins\Social\Contracts\NetworkFactory;
use tiFy\Plugins\Social\Contracts\Social;

class NetworksOptions
{
    /**
     * CONSTRUCTEUR.
     *
     * @param Social $social Instance du controleur principal.
     *
     * @return void
     */
    public function __construct(Social $social)
    {
        /** @var MetaboxManager $metabox */
        $metabox = app('metabox');
        $has_item = false;

        foreach ($social->all() as $item) {
            /** @var NetworkFactory $item */
            if ($item->isAdmin()) {
                $has_item = true;

                $metabox->add("Social-{$item->getName()}", 'tify_options@options', [
                    'parent'  => 'Social',
                    'content' => NetworkOptions::class,
                    'args'    => ['network' => $item]
                ]);
            }
        }

        if ($has_item) {
            $metabox->add('Social', 'tify_options@options', [
                'title'    => __('Réseaux sociaux', 'tify'),
                'position' => 99
            ]);

            register_setting('tify_options', 'tify_social_share');
        }
    }
}