<?php

namespace tiFy\Plugins\Social\Contracts;

use tiFy\Contracts\App\Item\AppItemInterface;
use tiFy\Kernel\Templates\EngineInterface;

interface NetworkItemInterface extends AppItemInterface
{
    /**
     * Récupération de l'icône représentative.
     *
     * @return string
     */
    public function getIcon();

    /**
     * Récupération du nom de qualification.
     *
     * @return string
     */
    public function getName();

    /**
     * Récupération de l'intitulé de qualification.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Ordre d'affichage dans la liste des réseaux pris en charge.
     *
     * @return int
     */
    public function getOrder();

    /**
     * Récupération du nom d'enregistrement des données en base.
     *
     * @return string
     */
    public function getOptionName();

    /**
     * Récupération de la clé de qualification du nom d'enregistrement des données en base.
     *
     * @return string
     */
    public function getOptionNameKey();

    /**
     * Récupération du statut d'affichage du réseau.
     *
     * @return string online|warning|offline
     */
    public function getStatus();

    /**
     * Récupération de la classe de rappel des gabarits d'affichage.
     *
     * @return EngineInterface
     */
    public function getView();

    /**
     * Vérification de l'existance d'une url vers la page du compte du réseau.
     *
     * @return bool
     */
    public function hasUri();

    /**
     * Vérification d'activation de la prise en charge du réseau.
     *
     * @return bool
     */
    public function isActive();

    /**
     * Vérification d'activation de l'administrabilité du réseau.
     *
     * @return bool
     */
    public function isAdmin();

    /**
     * Lien vers la page de profil du réseau social.
     *
     * @param array $attrs Liste des attributs de configuration du lien.
     *
     * @return string|void
     */
    public function pageLink($attrs = []);

    /**
     * Définition de la classe de rappel des gabarits d'affichage.
     *
     * @return EngineInterface
     */
    public function setView();
}