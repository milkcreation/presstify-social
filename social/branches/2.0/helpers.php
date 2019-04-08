<?php

use tiFy\Plugins\Social\Social;

if (!function_exists('social')) {
    /**
     * Récupération de l'instance du controleur de plugin social.
     *
     * @return Social
     */
    function social(): Social
    {
        return app('social');
    }
}

if (!function_exists('social_menu')) {
    /**
     * Affichage de la liste des liens vers les pages des réseaux pris en charge et actifs.
     * {@internal un réseau est considéré actif lorsque la prise en charge est activé
     * et que l'url de la page est renseignée.}
     *
     * @param array $attrs Liste des attributs de configuration personnalisés.
     *
     * @return string
     */
    function social_menu($attrs = [])
    {
        return social()->menuRender($attrs);
    }
}

if (!function_exists('social_page_link')) {
    /**
     * Affichage d'un lien vers la page d'un réseau pris en charge et actifs.
     * {@internal un réseau est considéré actif lorsque la prise en charge est activé
     * et que l'url de la page est renseignée.}
     *
     * @param string $name Nom de qualification du réseau.
     * @param array $attrs Liste des attributs de configuration personnalisés.
     *
     * @return string
     */
    function social_page_link($name, $attrs = [])
    {
        return social()->pageLinkRender($name, $attrs);
    }
}