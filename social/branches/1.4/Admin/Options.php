<?php

namespace tiFy\Plugins\Social\Admin;

use tiFy\App\Dependency\AbstractAppDependency;
use tiFy\Core\Options\Options as tiFyOptions;
use tiFy\Plugins\Social\Social;

class Options extends AbstractAppDependency
{
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        add_action('tify_options_register_node', [$this, 'optionsTab']);
    }

    /**
     * Déclaration de la boîte à onglets d'administration des options des réseaux sociaux déclarés.
     *
     * @param tiFyOptions $optionsController Instance de la classe des options de presstiFy.
     *
     * @return void
     */
    public function optionsTab($optionsController)
    {
        /** @var Social $social */
        $social = app(Social::class);
        $has_item = false;

        foreach($social->getItems() as $item) :
            if ($item->isAdmin()) :
                $has_item = true;

                $optionsController::registerNode(
                    [
                        'id'     => "tiFySocial-{$item->getName()}",
                        'parent' => 'tiFySocial',
                        'title'  => "<span class=\"tiFySocial-tabIcon\">{$item->getIcon()}</span>" .
                            "<span class=\"tiFySocial-tabTitle\">{$item->getTitle()}</span>" .
                            "<span class=\"tiFySocial-tabStatus tiFySocial-tabStatus--{$item->getStatus()}\">&#x25cf;</span>",

                        'cb'    => function () use ($item) {
                            echo $item->view()
                                ->render('options::admin', $item->all());

                        },
                    ]
                );
            endif;
        endforeach;

        if ($has_item) :
            $optionsController::registerNode(
                [
                    'id'    => 'tiFySocial',
                    'title' => __('Réseaux sociaux', 'tify'),
                ]
            );
            \register_setting('tify_options', 'tify_social_share');
        endif;
    }
}