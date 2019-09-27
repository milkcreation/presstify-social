<?php

namespace tiFy\Plugins\Social\Networks;

use Illuminate\Support\Arr;
use tiFy\Plugins\Social\Contracts\NetworkFactory as NetworkFactoryContract;
use tiFy\Plugins\Social\Contracts\NetworkViewer;
use tiFy\Plugins\Social\Contracts\Social;
use tiFy\Support\ParamsBag;

abstract class NetworkFactory extends ParamsBag implements NetworkFactoryContract
{
    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * Instance du controleur principal.
     * @var Social
     */
    protected $social;

    /**
     * Instance du gestionnaire de gabarits d'affichage.
     * @var NetworkViewer
     */
    protected $viewer;

    /**
     * CONSTRUCTEUR.
     *
     * @param string Nom de qualification.
     * @param array $attrs Attributs de configuration.
     * @param Social $social Instance du controleur principal.
     *
     * @return void
     */
    public function __construct($name, $attrs, Social $social)
    {
        $this->name = $name;
        $this->social = $social;

        $this->set($attrs)->parse();
    }

    /**
     * @inheritdoc
     */
    public function boot()
    {

    }

    /**
     * Liste des attributs de configuration par défaut.
     * @return array {
     *      @var bool $active Activation de la prise en charge.
     *      @var bool $admin Activation de l'administrabilité.
     *      @var string $icon Icone représentative.
     *      @var array page_link_attrs Liste des attributs de configuration du lien vers la page du compte.
     *      @var string $option_name Nom d'enregistrement des attributs en base.
     *      @var int $order Ordre d'affichage du lien vers la page du compte dans le menu.
     *      @var string $title Intitulé de qualification du réseau.
     *      @var string $uri Lien vers la page du compte
     * }
     */
    public function defaults()
    {
        return [
            'active'          => false,
            'admin'           => true,
            'icon'            => '',
            'page_link_attrs' => [],
            'option_name'     => $this->getOptionName(),
            'order'           => 0,
            'title'           => ucfirst($this->name),
            'uri'             => ''
        ];
    }

    /**
     * @inheritdoc
     */
    public function getIcon()
    {
        return $this->get('icon') ? : $this->social->getNetworkIcon($this->getName());
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->get('title');
    }

    /**
     * @inheritdoc
     */
    public function getOrder()
    {
        return $this->get('order');
    }

    /**
     * @inheritdoc
     */
    public function getOptionName()
    {
        return "tify_social_share[{$this->getOptionNameKey()}]";
    }

    /**
     * @inheritdoc
     */
    public function getOptionNameKey()
    {
        return $this->getName();
    }

    /**
     * @inheritdoc
     */
    public function getStatus()
    {
        return $this->isActive() && $this->hasUri() ? 'online' : ($this->isActive() ? 'warning' : 'offline');
    }

    /**
     * @inheritdoc
     */
    public function hasUri()
    {
        return !empty($this->get('uri', ''));
    }

    /**
     * @inheritdoc
     */
    public function isActive()
    {
        $active = $this->get('active', false);

        return $active && ($active !== 'off');
    }

    /**
     * @inheritdoc
     */
    public function isAdmin()
    {
        return $this->get('admin', true);
    }

    /**
     * @inheritdoc
     */
    public function pageLink($attrs = [])
    {
        if (!$this->isActive() || (!$uri = $this->get('uri', ''))) {
            return '';
        }

        $attrs = array_merge(
            $this->get('page_link_attrs'),
            $attrs
        );

        if (!Arr::has($attrs, 'tag')) {
            Arr::set($attrs, 'tag', 'a');
        }

        if (!Arr::has($attrs, 'content')) {
            Arr::set($attrs, 'content',
                (Arr::get($attrs, 'icon', true) ? $this->getIcon() : '') .
                (Arr::get($attrs, 'title', true) ? $this->getTitle() : '')
            );
        }

        Arr::set($attrs, 'attrs.href', $this->get('uri', ''));

        if (!Arr::has($attrs, 'attrs.class')) {
            Arr::set($attrs, 'attrs.class', 'Social-link Social-link--' . $this->getName());
        }

        if (!Arr::has($attrs, 'attrs.title')) {
            Arr::set($attrs, 'attrs.title', sprintf(__('Accéder à la page %s', 'tify'), $this->getTitle()));
        }

        if (!Arr::has($attrs, 'attrs.target')) {
            Arr::set($attrs, 'attrs.target', '_blank');
        }

        return $this->viewer('page-link', $attrs);
    }

    /**
     * @inheritdoc
     */
    public function parse()
    {
        $this->attributes = array_merge($this->defaults(), $this->attributes, Arr::get(
            get_option('tify_social_share'),
            $this->getOptionNameKey(),
            []
        ));
    }

    /**
     * @inheritdoc
     */
    public function viewer($view = null, $data = [])
    {
        if (is_null($this->viewer)) {
            $this->viewer = $this->social->resolve('networks.viewer', $this);
        }

        if (func_num_args() === 0) {
            return $this->viewer;
        }

        return $this->viewer->make("_override::{$view}", $data);
    }
}