<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Plugins\Social\Contracts\{ChannelDriver, Social};
use tiFy\Metabox\MetaboxDriver;
use tiFy\Metabox\MetaboxView;
use tiFy\Support\Proxy\View;

class ChannelMetaboxDriver extends MetaboxDriver
{
    /**
     * Instance du réseau associé.
     * @var ChannelDriver
     */
    protected $channel;

    /**
     * Instance du gestionnaire des réseaux.
     * @var Social
     */
    protected $social;

    /**
     * CONSTRUCTEUR.
     *
     * @param ChannelDriver $channel Instance du réseau associé.
     * @param Social $social Instance du gestionnaire des réseaux.
     *
     * @return void
     */
    public function __construct(ChannelDriver $channel, Social $social)
    {
        $this->set('channel', $this->channel = $channel);
        $this->social = $social;
    }

    /**
     * @inheritDoc
     */
    public function viewer(?string $view = null, array $data = [])
    {
        $directory = $this->social->getResources()->path('/views/channel/metabox/' . $this->channel->getName());
        if (!is_dir($directory)) {
            $directory = $this->social->getResources()->path('/views/channel/metabox');
        }

        if (!$this->viewer) {
            $this->viewer = View::getPlatesEngine(array_merge([
                'directory' => $directory,
                'factory'   => MetaboxView::class,
                'metabox'   => $this
            ], config('metabox.viewer', []), $this->get('viewer', [])));
        }

        if (func_num_args() === 0) {
            return $this->viewer;
        }

        return $this->viewer->render($view, $data);
    }
}