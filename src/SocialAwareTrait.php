<?php declare(strict_types=1);

namespace tiFy\Plugins\Social;

use Exception;
use tiFy\Plugins\Social\Contracts\Social as SocialManagerContract;

trait SocialAwareTrait
{
    /**
     * Instance du gestionnaire de réseaux sociaux.
     * @var Social|null
     */
    protected $socialManager;

    /**
     * Récupération de l'instance du gestionnaire de réseaux sociaux.
     *
     * @return SocialManagerContract|null
     */
    public function socialManager(): ?SocialManagerContract
    {
        if (is_null($this->socialManager)) {
            try {
                $this->socialManager = Social::instance();
            } catch (Exception $e) {
                $this->socialManager;
            }
        }

        return $this->socialManager;
    }

    /**
     * Définition du gestionnaire de réseaux sociaux.
     *
     * @param SocialManagerContract $socialManager
     *
     * @return static
     */
    public function setSocialManager(SocialManagerContract $socialManager): self
    {
        $this->socialManager = $socialManager;

        return $this;
    }
}
