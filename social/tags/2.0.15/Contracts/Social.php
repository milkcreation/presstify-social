<?php

namespace tiFy\Plugins\Social\Contracts;

use tiFy\Contracts\Support\Collection;
use tiFy\Contracts\View\ViewController;
use tiFy\Contracts\View\ViewEngine;

interface Social extends Collection
{
    /**
     * Récupération de la liste des réseaux pris en charge affichable dans le menu par ordre d'affichage.
     * {@internal le réseaux doit être actif, son url renseignée. La liste est triée par ordre d'affichage.}
     *
     * @return NetworkFactory[]
     */
    public function getMenuItems();

    /**
     * Récupération de l'icône d'un réseau.
     *
     * @param string $name Nom de qualification du réseau
     *
     * @return string
     */
    public function getNetworkIcon($name);

    /**
     * Affichage d'un menu de la liste des liens vers la page des comptes des réseaux.
     *
     * @param array $attrs Liste des attributs de configuration.
     *
     * @return string
     */
    public function menuRender($attrs = []);

    /**
     * Affichage d'un lien vers la page du compte d'un réseau.
     *
     * @param string $alias Nom de qualification du réseau.
     * @param array $attrs Liste des attributs de configuration personnalisé.
     *
     * @return string
     */
    public function pageLinkRender($alias, $attrs = []);

    /**
     * Récupération d'une instance d'un service du plugin.
     *
     * @return object
     */
    public function resolve($alias, ...$args);

    /**
     * Instance du gestionnaire des gabarits d'affichage ou instance d'un gabarit d'affichage.
     * {@internal Si aucun argument n'est passé  la méthode, retourne l'intance du controleur principal.}
     * {@internal Sinon récupère le gabarit d'affichage et passe les variables en argument.}
     *
     * @param null|string view Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return ViewController|ViewEngine
     */
    public function viewer($view = null, $data = []);
}
