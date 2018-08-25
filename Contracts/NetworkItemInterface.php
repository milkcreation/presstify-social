<?php

namespace tiFy\Plugins\Social\Contracts;

use Illuminate\Support\Arr;
use tiFy\App\AppInterface;
use tiFy\Contracts\App\Item\AppItemInterface;
use tiFy\Kernel\Templates\EngineInterface;
use tiFy\Options\Options;

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
     * Récupération de la classe de rappel des gabarits d'affichage.
     *
     * @return EngineInterface
     */
    public function getView();

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
     * Vérification de l'existance d'une url vers la page du compte du réseau.
     *
     * @return bool
     */
    public function hasUri();

    /**
     * Vérification de l'activation de la prise en charge du réseau.
     *
     * @return bool
     */
    public function isActive();

    /**
     * Formulaire d'administration des attributs de configuration depuis l'interface d'administration des options de presstiFy.
     *
     * @param Options $options Instance de la classe des options de presstiFy.
     *
     * @return void
     */
    public function optionsForm($options);

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