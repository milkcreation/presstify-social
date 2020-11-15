<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use Detection\MobileDetect;
use tiFy\Contracts\View\PlatesEngine;
use tiFy\Contracts\View\Engine as ViewEngine;
use tiFy\Plugins\Social\Contracts\ChannelDriver as ChannelDriverContract;
use tiFy\Plugins\Social\SocialAwareTrait;
use tiFy\Support\ParamsBag;
use tiFy\Support\Proxy\Metabox;
use tiFy\Support\Proxy\Url;

class ChannelDriver extends ParamsBag implements ChannelDriverContract
{
    use SocialAwareTrait;

    /**
     * Nom de qualification.
     * @var string
     */
    protected $name = '';

    /**
     * Url de partage et indicateur de partage possible sur le réseau.
     * @see https://css-tricks.com/simple-social-sharing-links/
     * @see https://jonsuh.com/blog/social-share-links/
     * @var string
     */
    protected $sharer = '';

    /**
     * Paramètre de partage.
     * @var array
     */
    protected $share_params = [];

    /**
     * Instance du gestionnaire de gabarits d'affichage.
     * @var PlatesEngine|ViewEngine
     */
    protected $view;

    /**
     * @param string|null Nom de qualification.
     * @param array $attrs Attributs de configuration.
     *
     * @return void
     */
    public function __construct(?string $name = null, array $attrs = [])
    {
        if (!is_null($name)) {
            $this->setName($name);
        }

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
     * @var bool $deeplink Activation de gestion de lien profond.
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
            'deeplink'        => true,
            'icon'            => '',
            'page_link_attrs' => [],
            'option_name'     => $this->getOptionName(),
            'order'           => 0,
            'share'           => false,
            'title'           => ucfirst($this->name),
            'uri'             => '',
            'view'            => []
        ];
    }

    /**
     * @inheritDoc
     */
    public function getDeeplink(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getIcon(): string
    {
        return $this->get('icon')
            ?: call_user_func($this->social()->resources(), "/assets/dist/img/channel/{$this->getName()}/icon.svg");
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
    public function getPageUrl(): string
    {
        return $this->get('uri', '');
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
    public function getShareUrl(?array $params = null): string
    {
        if (is_null($params) && empty($this->share_params)) {
            return '';
        }

        return $this->sharer
            ? Url::set($this->sharer)->with(array_merge($this->share_params, $params ?: []))->render() : '';
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
    public function getTitle(): string
    {
        return $this->get('title');
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
    public function hasShare(): bool
    {
        return $this->isSharer() && filter_var($this->get('share'), FILTER_VALIDATE_BOOLEAN);
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
    public function isAndroidOS(): bool
    {
        return (new MobileDetect())->is('AndroidOS');
    }

    /**
     * @inheritDoc
     */
    public function isMobile(): bool
    {
        return (new MobileDetect())->isMobile();
    }

    /**
     * @inheritDoc
     */
    public function isIOS(): bool
    {
        return (new MobileDetect())->is('iOS');
    }

    /**
     * @inheritDoc
     */
    public function isSharer(): bool
    {
        return !!$this->sharer;
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

            $params->set('attrs.href', $this->getPageUrl());

            if ($this->isMobile() && $this->get('deeplink') && ($deeplink = $this->getDeeplink())) {
                $params->set([
                    'attrs.data-control'  => 'social.deeplink',
                    'attrs.data-deeplink' => $deeplink,
                ]);
            }

            if (!$params->has('attrs.class')) {
                $params->set('attrs.class', 'Social-link Social-link--' . $this->getName());
            } else {
                $params->set('attrs.class', sprintf($params->get('attrs.class', '%s'), $this->getName()));
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
                'driver' => new ChannelMetaboxDriver($this),
                'parent' => 'Social',
                'title'  => $this->getTitle(),
                'value'  => $this->all(),
            ])->setScreen('tify_options@options')->setContext('tab');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): ChannelDriverContract
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPostShare($post): ChannelDriverContract
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function view(?string $name = null, array $data = [])
    {
        if (is_null($this->view)) {
            $this->view = $this->social()->resolve('channel-view');

            $directory = $this->social()->resources('/views/channel/' . $this->getName());
            if (is_dir($directory)) {
                $this->view->setDirectory($directory);
            }

            $this->view->params(array_merge($this->get('view', []), ['channel' => $this]));
        }

        if (func_num_args() === 0) {
            return $this->view;
        }

        return $this->view->render($name, $data);
    }
}