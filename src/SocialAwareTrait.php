<?php declare(strict_types=1);

namespace tiFy\Plugins\Social;

use Exception;
use tiFy\Plugins\Social\Contracts\Social as SocialContract;

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
        if (is_null($this->social)) {
            try {
                $this->social = Social::instance();
            } catch (Exception $e) {
                $this->social;
            }
        }

        return $this->social;
    }

    /**
     * Définition du gestionnaire de réseaux sociaux.
     *
     * @param SocialContract $social
     *
     * @return static
     */
    public function setSocial(SocialContract $social): self
    {
        $this->social = $social;

        return $this;
    }
}
