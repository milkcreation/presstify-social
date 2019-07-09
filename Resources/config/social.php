<?php

return [
    /**
     * Mise en file automatique des scripts de l'interface d'administration.
     * {@internal Chargement webpack: "import 'presstify-plugins/social/Resources/assets/admin';"}
     * @var boolean
     */
    'admin_enqueue_scripts' => true,

    /**
     * Attributs de configuration des réseaux sociaux.
     * @var array
     */
    'networks'              => [
        'dailymotion',
        'facebook',
        'google-plus',
        'linkedin',
        'pinterest',
        'viadeo',
        'vimeo',
        'twitter',
        'youtube',
    ],
];