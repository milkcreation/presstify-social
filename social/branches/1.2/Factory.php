<?php

namespace tiFy\Plugins\Social;

class Factory extends \tiFy\App\FactoryConstructor
{
    /**
     * Liste des options
     * @var array
     */
    private $Options = [];

    /**
     * CONSTRUCTEUR
     *
     * @return void
     */
    public function __construct($id, $attrs = [])
    {
        parent::__construct($id, $attrs);

        // Définition des options
        $this->Options[$this->getId()] = isset(Social::$Options[$this->getId()]) ? Social::$Options[$this->getId()] : [];
    }

    /**
     * CONTROLEURS
     */
    /**
     * Récupération d'options
     *
     * @param null|string $name Intitulé de l'option à retourner. Si null, retourne la liste complète des options.
     * @param string $default Valeur de retour par défaut
     *
     * @return mixed|string
     */
    public function getOption($name = null, $default = '')
    {
        if (!$name) :
            return $this->Options[$this->getId()];
        elseif (isset($this->Options[$this->getId()][$name])) :
            return $this->Options[$this->getId()][$name];
        else :
            return $default;
        endif;
    }
}