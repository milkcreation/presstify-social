<?php declare(strict_types=1);

namespace tiFy\Plugins\Social;

use tiFy\Plugins\Social\Contracts\Social;

trait SocialAwareTrait
{
    /**
     * Instance du gestionnaire de réseaux sociaux.
     * @var Social|null
     */
    protected $social;

    /**
     * Récupération de l'instance du gestionnaire de réseaux sociaux.
     *
     * @return Social|null
     */
    public function social(): ?Social
    {
        return $this->social;
    }

    /**
     * Définition du gestionnaire de réseaux sociaux.
     *
     * @param Social $social
     *
     * @return static
     */
    public function setSocial(Social $social): self
    {
        $this->social = $social;

        return $this;
    }
}
