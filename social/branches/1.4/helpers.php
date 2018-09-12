<?php

use tiFy\Plugins\Social\Social;

if (!function_exists('social_menu')) :
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
        /** @var Social $social */
        $social = app(Social::class);

        return $social->menuRender($attrs);
    }
endif;

if (!function_exists('social_page_link')) :
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
    function social_page_link($name, $args = [])
    {
        /** @var Social $social */
        $social = app(Social::class);

        return $social->pageLinkRender($name, $attrs = []);
    }
endif;