<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Contracts;

use InvalidArgumentException;
use tiFy\Contracts\{Container\Container, Filesystem\LocalFilesystem, View\Engine as ViewEngine};

interface Social
{
    /**
     * Ajout d'un réseau à liste des réseaux déclarés.
     *
     * @return ChannelDriver
     *
     * @throws InvalidArgumentException
     */
    public function addChannel(string $name, $attrs): ChannelDriver;

    /**
     * Récupération de l'instance d'un réseau déclaré.
     *
     * @param string $name Nom de qualification du réseau.
     *
     * @return ChannelDriver|null
     */
    public function getChannel(string $name): ?ChannelDriver;

    /**
     * Récupération de la liste des instances de réseaux déclarés.
     *
     * @return ChannelDriver[]|array
     */
    public function getChannels(): array;

    /**
     * Récupération de l'instance du conteneur d'injection de dépendances.
     *
     * @return Container|null
     */
    public function getContainer(): ?Container;

    /**
     * Récupération de l'instance de stockage des ressources ou Contenu d'une ressource selon son chemin.
     *
     * @param string|null $path Chemin relatif vers la resource.
     *
     * @return LocalFilesystem|string
     */
    public function getResources(?string $path = null);

    /**
     * Rendu d'affichage d'un lien vers la page du compte d'un réseau.
     *
     * @param string $name Nom de qualification du réseau.
     * @param array $attrs Liste des attributs de configuration personnalisé.
     *
     * @return string
     */
    public function channelLink(string $name, array $attrs = []): string;

    /**
     * Instance du gestionnaire de gabarits d'affichage ou rendu du gabarit d'affichage.
     *
     * @param string|null $name Nom de qualification du gabarit.
     * @param array $data Liste des variables passées en argument.
     *
     * @return ViewEngine|string
     */
    public function view(?string $name = null, array $data = []);
}
