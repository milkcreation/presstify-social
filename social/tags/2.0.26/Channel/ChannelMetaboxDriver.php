<?php declare(strict_types=1);

namespace tiFy\Plugins\Social\Channel;

use tiFy\Plugins\Social\SocialAwareTrait;
use tiFy\Plugins\Social\Contracts\ChannelDriver;
use tiFy\Metabox\{MetaboxDriver, MetaboxView};
use tiFy\Support\Proxy\View;

class ChannelMetaboxDriver extends MetaboxDriver
{
    use SocialAwareTrait;

    /**
     * Instance du réseau associé.
     * @var ChannelDriver
     */
    protected $channel;

    /**
     * CONSTRUCTEUR.
     *
     * @param ChannelDriver $channel Instance du réseau associé.
     *
     * @return void
     */
    public function __construct(ChannelDriver $channel)
    {
        $this->set('channel', $this->channel = $channel);
    }

    /**
     * @inheritDoc
     */
    public function viewer(?string $view = null, array $data = [])
    {
        $social = $this->channel->social();
        $directory = $social->getResources()->path('/views/admin/metabox/' . $this->channel->getName());
        if (!is_dir($directory)) {
            $directory = $social->getResources()->path('/views/admin/metabox');
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