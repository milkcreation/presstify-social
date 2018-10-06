<?php

namespace tiFy\Plugins\Social\Admin;

use tiFy\App\Dependency\AbstractAppDependency;
use tiFy\Metabox\Metabox;
use tiFy\Plugins\Social\Admin\Metabox\Options\NetworkOptions\NetworkOptions;
use tiFy\Plugins\Social\Social;

class Options
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
        /** @var Metabox $metabox */
        $metabox = resolve(Metabox::class);
        $has_item = false;

        foreach ($social->getItems() as $item) :
            if ($item->isAdmin()) :
                $has_item = true;

                $metabox->add(
                    'tify_options@options',
                    [
                        'name'    => "Social-{$item->getName()}",
                        'parent'  => 'Social',
                        'content' => NetworkOptions::class,
                        'args'    => ['network' => $item]
                    ]
                );
            endif;
        endforeach;

        if ($has_item) :
            $metabox->add(
                'tify_options@options',
                [
                    'name'  => 'Social',
                    'title' => __('Réseaux sociaux', 'tify'),
                    'position' => 99
                ]
            );

            register_setting('tify_options', 'tify_social_share');
        endif;
    }
}