<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Contracts\View\Engine as ViewEngine;
use tiFy\Plugins\Social\Contracts\{ChannelDriver as ChannelDriverContract, Social};
use tiFy\Support\ParamsBag;
use tiFy\Support\Proxy\Metabox;
use tiFy\Support\Proxy\View;

class ChannelDriver extends ParamsBag implements ChannelDriverContract
{
    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * Instance du gestionnaire.
     * @var Social
     */
    protected $social;

    /**
     * Instance du gestionnaire de gabarits d'affichage.
     * @var ViewEngine
     */
    protected $view;

    /**
     * CONSTRUCTEUR.
     *
     * @param string Nom de qualification.
     * @param array $attrs Attributs de configuration.
     * @param Social $social Instance du controleur principal.
     *
     * @return void
     */
    public function __construct(string $name, array $attrs, Social $social)
    {
        $this->name = $name;
        $this->social = $social;

        $this->set($attrs);
    }

    /**
     * @inheritDoc
     */
    public function boot(): void { }

    /**
     * Liste des attributs de configuration par défaut.
     *
     * @return array {
     * @var bool $active Activation de la prise en charge.
     * @var bool $admin Activation de l'administrabilité.
     * @var string $icon Icone représentative.
     * @var array page_link_attrs Liste des attributs de configuration du lien vers la page du compte.
     * @var string $option_name Nom d'enregistrement des attributs en base.
     * @var int $order Ordre d'affichage du lien vers la page du compte dans le menu.
     * @var string $title Intitulé de qualification du réseau.
     * @var string $uri Lien vers la page du compte
     * }
     */
    public function defaults(): array
    {
        return [
            'active'          => false,
            'admin'           => true,
            'icon'            => '',
            'page_link_attrs' => [],
            'option_name'     => $this->getOptionName(),
            'order'           => 0,
            'title'           => ucfirst($this->name),
            'uri'             => '',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getIcon(): string
    {
        return $this->get('icon') ?: $this->social->getResources("/assets/channel/{$this->getName()}/img/icon.svg");
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return $this->get('title');
    }

    /**
     * @inheritDoc
     */
    public function getOrder(): int
    {
        return (int)$this->get('order');
    }

    /**
     * @inheritDoc
     */
    public function getOptionName(): string
    {
        return "tify_social_share[{$this->getOptionNameKey()}]";
    }

    /**
     * @inheritDoc
     */
    public function getOptionNameKey(): string
    {
        return $this->getName();
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): string
    {
        return $this->isActive() && $this->hasUri() ? 'online' : ($this->isActive() ? 'warning' : 'offline');
    }

    /**
     * @inheritDoc
     */
    public function hasAdmin(): bool
    {
        return $this->get('admin', true);
    }

    /**
     * @inheritDoc
     */
    public function hasUri(): bool
    {
        return !empty($this->get('uri', ''));
    }

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        $active = $this->get('active', false);

        return $active && ($active !== 'off');
    }

    /**
     * @inheritDoc
     */
    public function pageLink(array $attrs = []): string
    {
        if (!$this->isActive() || !$this->hasUri()) {
            return '';
        } else {
            $params = (new ParamsBag())->set(array_merge($this->get('page_link_attrs'), $attrs));

            if (!$params->has('tag')) {
                $params->set('tag', 'a');
            }

            if (!$params->has('content')) {
                $params->set('content',
                    ($params->get('icon', true) ? $this->getIcon() : '') .
                    ($params->get('title', true) ? $this->getTitle() : '')
                );
            }

            $params->set('attrs.href', $this->get('uri', ''));

            if (!$params->has('attrs.class')) {
                $params->set('attrs.class', 'Social-link Social-link--' . $this->getName());
            }

            if (!$params->has('attrs.title')) {
                $params->set('attrs.title', sprintf(__('Accéder à la page %s', 'tify'), $this->getTitle()));
            }

            if (!$params->has('attrs.target')) {
                $params->set('attrs.target', '_blank');
            }

            return $this->view('page-link', $params->all());
        }
    }

    /**
     * @inheritDoc
     */
    public function parse(): ChannelDriverContract
    {
        parent::parse();

        if ($opts = get_option('tify_social_share')) {
            $this->attributes = array_merge($this->attributes, $opts[$this->getOptionNameKey()] ?? []);
        }

        if ($this->hasAdmin()) {
            Metabox::add("Social-{$this->getName()}", [
                'name'   => $this->getOptionName(),
                'driver' => new ChannelMetaboxDriver($this, $this->social),
                'parent' => 'Social',
                'title'  => $this->getTitle(),
                'value'  => [
                    'active' => $this->get('active'),
                    'order'  => $this->get('order'),
                    'uri'    => $this->get('uri'),
                ],
            ])->setScreen('tify_options@options')->setContext('tab');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function view(?string $name = null, array $data = [])
    {
        if (is_null($this->view)) {
            $this->view = View::getPlatesEngine(array_merge([
                'directory' => $this->social->getResources()->path('/views/channel'),
                'factory'   => ChannelView::class,
                'channel'   => $this,
            ], $this->get('viewer', [])));
        }

        if (func_num_args() === 0) {
            return $this->view;
        }

        return $this->view->render($name, $data);
    }
}