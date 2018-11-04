<?php

namespace tiFy\Plugins\Social;

use tiFy\Contracts\View\ViewController;
use tiFy\Contracts\View\ViewEngine;

trait SocialResolverTrait
{
    /**
     * Instance du gestionnaire de gabarits d'affichage.
     * @var ViewEngine
     */
    protected $viewer;

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
    public function viewer($view = null, $data = [])
    {
        if (!$this->viewer) :
            $default_dir = __DIR__ . '/Resources/views';
            $this->viewer = view()
                ->setDirectory($default_dir)
                ->setOverrideDir($default_dir);
        endif;

        if (func_num_args() === 0) :
            return $this->viewer;
        endif;

        return $this->viewer->make("_override::{$view}", $data);
    }
}
