<?php

namespace tiFy\Plugins\Social\NetworkItems;

use Illuminate\Support\Arr;
use tiFy\App\Item\AbstractAppItemController;
use tiFy\Contracts\App\AppInterface;
use tiFy\Kernel\Tools;
use tiFy\Plugins\Social\Contracts\NetworkItemInterface;

abstract class AbstractNetworkItem extends AbstractAppItemController implements NetworkItemInterface
{
    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * Liste des attributs de configuration.
     * @var array {
     *      @var bool $active Activation de la prise en charge.
     *      @var bool $active Activation de l'administrabilité.
     *      @var string $icon Icone représentative.
     *      @var array page_link_attrs Liste des attributs de configuration du lien vers la page du compte.
     *      @var string $option_name Nom d'enregistrement des attributs en base.
     *      @var int $order Ordre d'affichage du lien vers la page du compte dans le menu.
     *      @var string $title Intitulé de qualification du réseau.
     *      @var string $uri Lien vers la page du compte
     * }
     */
    protected $attributes = [
        'active'          => false,
        'admin'           => true,
        'icon'            => '',
        'page_link_attrs' => [],
        'option_name'     => '',
        'order'           => 0,
        'title'           => '',
        'uri'             => '',
    ];

    /**
     * Classe de rappel des gabarits d'affichage.
     * @var string
     */
    protected $view;

    /**
     * CONSTRUCTEUR.
     *
     * @param string Nom de qualification.
     * @param array $attrs Attributs de configuration.
     * @param AppInterface $app Instance de l'application.
     *
     * @return void
     */
    public function __construct($name, $attrs = [], AppInterface $app)
    {
        $this->name = $name;

        parent::__construct($attrs, $app);
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->setView();
    }

    /**
     * {@inheritdoc}
     */
    public function defaults()
    {
        return array_merge(
            $this->attributes,
            [
                'option_name' => $this->getOptionName(),
                'title'       => ucfirst($this->name),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getIcon()
    {
        return Tools::File()->svgGetContents(class_info($this)->getDirname() . '/icon.svg') ?: '';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->get('title');
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return $this->get('order');
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionName()
    {
        return "tify_social_share[{$this->getOptionNameKey()}]";
    }

    /**
     * {@inheritdoc}
     */
    public function getOptionNameKey()
    {
        return $this->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->isActive() && $this->hasUri() ? 'online' : ($this->isActive() ? 'warning' : 'offline');
    }

    /**
     * {@inheritdoc}
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * {@inheritdoc}
     */
    public function hasUri()
    {
        return !empty($this->get('uri', ''));
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        $active = $this->get('active', false);

        return $active && $active !== 'off';
    }

    /**
     * {@inheritdoc}
     */
    public function isAdmin()
    {
        return $this->get('admin', true);
    }

    /**
     * {@inheritdoc}
     */
    public function pageLink($attrs = [])
    {
        if (!$this->isActive() || (!$uri = $this->get('uri', ''))) :
            return '';
        endif;

        $attrs = array_merge(
            $this->get('page_link_attrs'),
            $attrs
        );

        if (!Arr::has($attrs, 'tag')) :
            Arr::set($attrs, 'tag', 'a');
        endif;

        if (!Arr::has($attrs, 'content')) :
            Arr::set($attrs, 'content',
                (Arr::get($attrs, 'icon', true) ? $this->getIcon() : '') .
                (Arr::get($attrs, 'title', true) ? $this->getTitle() : '')
            );
        endif;

        Arr::set($attrs, 'attrs.href', $this->get('uri', ''));

        if (!Arr::has($attrs, 'attrs.class')) :
            Arr::set($attrs, 'attrs.class', 'tiFySocial-link tiFySocial-link--' . $this->getName());
        endif;

        if (!Arr::has($attrs, 'attrs.title')) :
            Arr::set($attrs, 'attrs.title', sprintf(__('Accéder à la page %s', 'tify'), $this->getTitle()));
        endif;

        if (!Arr::has($attrs, 'attrs.target')) :
            Arr::set($attrs, 'attrs.target', '_blank');
        endif;

        return $this->getView()
            ->render('page::link', $attrs);
    }

    /**
     * {@inheritdoc}
     */
    public function parse($attrs = [])
    {
        parent::parse(
            array_merge(
                $attrs,
                Arr::get(
                    get_option('tify_social_share'),
                    $this->getOptionNameKey(),
                    []
                )
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setView()
    {
        if (!$this->view) :
            return $this->view = view()
                ->setDirectory(__DIR__ . '/../Resources/views')
                ->setController(NetworkItemBaseTemplate::class)
                ->registerFunction('isActive', [$this, 'isActive'])
                ->addFolder('options', __DIR__ . '/../Resources/views')
                ->addFolder('page', __DIR__ . '/../Resources/views');
        endif;

        return $this->view;
    }
}