<?php
/**
 * Exemple de configuration.
 */

return [
    // Mise en file automatique des scripts de l'interface client.
    'wp_enqueue_scripts' => true,
    // Mise en file automatique des scripts de l'interface d'administration.
    'admin_enqueue_scripts' => true,
    // Attributs de configuration des réseaux sociaux
    'networks' => [
        'dailymotion' => [
            'page_link_attrs' => [
                'title' => false
            ]
        ],
        'facebook' => [
            'page_link_attrs' => [
                'title' => false
            ]
        ],
        'google-plus' => [
            'page_link_attrs' => [
                'title' => false
            ]
        ],
        'instagram' => [
            'page_link_attrs' => [
                'title' => false
            ]
        ],
        'linkedin' => [
            'page_link_attrs' => [
                'title' => false
            ]
        ],
        'pinterest' => [
            'page_link_attrs' => [
                'title' => false
            ]
        ],
        'viadeo' => [
            'page_link_attrs' => [
                'title' => false
            ]
        ],
        'vimeo' => [
            'page_link_attrs' => [
                'title' => false
            ]
        ],
        'twitter' => [
            'page_link_attrs' => [
                'title' => false
            ]
        ],
        'youtube' => [
            'page_link_attrs' => [
                'title' => false
            ]
        ]
    ]
];