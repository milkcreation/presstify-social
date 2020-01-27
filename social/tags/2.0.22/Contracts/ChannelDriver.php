<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Contracts;

use tiFy\Contracts\{Support\ParamsBag, View\PlatesEngine};

interface ChannelDriver extends ParamsBag
{
    /**
     * Récupération de l'icône représentative.
     *
     * @return string
     */
    public function getIcon(): string;

    /**
     * Récupération du nom de qualification.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Récupération de l'intitulé de qualification.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Ordre d'affichage dans la liste des réseaux pris en charge.
     *
     * @return int
     */
    public function getOrder(): int;

    /**
     * Récupération du nom d'enregistrement des données en base.
     *
     * @return string
     */
    public function getOptionName(): string;

    /**
     * Récupération de la clé de qualification du nom d'enregistrement des données en base.
     *
     * @return string
     */
    public function getOptionNameKey(): string;

    /**
     * Récupération du statut d'affichage du réseau.
     *
     * @return string online|warning|offline
     */
    public function getStatus(): string;

    /**
     * Vérification d'activation de l'administrabilité du réseau.
     *
     * @return bool
     */
    public function hasAdmin(): bool;

    /**
     * Vérification de l'existance d'une url vers la page du compte du réseau.
     *
     * @return bool
     */
    public function hasUri(): bool;

    /**
     * Vérification d'activation de la prise en charge du réseau.
     *
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Lien vers la page de profil du réseau social.
     *
     * @param array $attrs Liste des attributs de configuration du lien.
     *
     * @return string
     */
    public function pageLink(array $attrs = []): string;

    /**
     * Instance du gestionnaire de gabarits d'affichage ou instance du gabarit d'affichage.
     *
     * @param string|null $name Nom de qualification du gabarit d'affichage.
     * @param array $data Liste des paramètres passés en arguments au gabarit d'affichage.
     *
     * @return PlatesEngine|string
     */
    public function view(?string $name = null, array $data = []);
}