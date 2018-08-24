<?php

use tiFy\Plugins\Social\Social;

if (!function_exists('social_menu')) :
    /**
     * Affichage de la liste des liens vers les pages des réseaux pris en charge et actifs.
     * {@internal un réseau est considéré actif lorsque la prise en charge est activé et que l'url de la page est renseignée.}
     *
     * @param array $attrs Liste des attributs de configuration personnalisés.
     *
     * @return string
     */
    function social_menu($attrs = [])
    {
        return app(Social::class)->menuRender($attrs);
    }
endif;

if (!function_exists('tify_social_page_link')) :
    /**
     * Affichage d'un lien vers la page d'un réseau pris en charge et actifs.
     * {@internal un réseau est considéré actif lorsque la prise en charge est activé et que l'url de la page est renseignée.}
     *
     * @param string $name Nom de qualification du réseau.
     * @param array $attrs Liste des attributs de configuration personnalisés.
     *
     * @return string
     */
    function tify_social_page_link($name, $args = [])
    {
        return app(Social::class)->pageLinkRender($name);
    }
endif;