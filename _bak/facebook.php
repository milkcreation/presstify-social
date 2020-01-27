<?php

/**
 * {@inheritdoc}
 */
//public function boot()
//{
  //  parent::boot();

    /**
     * $this->view()
     * ->modifyFolder('options', __DIR__ . '/views');
     *
     * $this->app->appAddAction('init', [$this, 'init']);
     * $this->app->appAddAction('wp_enqueue_scripts', [$this, 'wp_enqueue_scripts']);
     * $this->app->appAddAction('wp_ajax_tify_fb_post2feed_callback', [$this, 'wp_ajax']);
     * $this->app->appAddAction('wp_ajax_nopriv_tify_fb_post2feed_callback', [$this, 'wp_ajax']);
     */
//}

// _________________________________________________________________________________________________________________
/**
 * Initialisation globale de Wordpress.
 *
 * @return void

final public function init()
{
    \wp_register_style(
        'tiFyPluginSocialFacebook-share',
        class_info($this)->getUrl() . '/css/share.css',
        [],
        160511
    );
    \wp_register_script(
        'tiFyPluginSocialFacebook-share',
        class_info($this)->getUrl() . '/js/share.js',
        ['jquery'],
        160511,
        true
    );
} */

    // _________________________________________________________________________________________________________________
    /**
     * Mise en file des scripts de l'interface utilisateur
     * @todo
     *
     * @return void

    final public function wp_enqueue_scripts()
{
    if ($this->isActive() && $this->get('appId')) :
        \wp_enqueue_style('tiFyPluginSocialFacebook-share');
        \wp_enqueue_script('tiFyPluginSocialFacebook-share');
    endif;
}*/

    /**
     * Requête Ajax
     * @todo
     *
     * Rappel de traitement de partage

    final public function wp_ajax()
{
    $output = apply_filters(
        'tify_fb_post2feed_callback_handle',
        '',
        $_POST['response'],
        $_POST['attrs']
    );

    wp_die($output);
}*/

    /**
     * Lien de partage
     * @todo
     *
     * @see https://developers.facebook.com/docs/sharing/reference/feed-dialog/v2.6
     *
     * @param int $post_id Identifiant de qualification du post à partager
     * @param array $args Attributs de configuration du partage
     * @param bool $echo Activation de l'affichage
     *
     * @return null|string

    public static function shareButton($post = 0, $args = [], $echo = true)
{
    static $Instance;
    $Instance++;

    // @var \tiFy\Plugins\Social\Networks\Facebook\Facebook $network

    $network = self::tFyAppGetContainer('tiFy\Plugins\Social\Networks\Facebook\Facebook');

    // Initialisation de Facebook
    $appId = $network->getOption('appId');
    $fb = new \tiFy\Lib\Facebook\Facebook($appId);
    $fb->JSInit();

    if (!($post = get_post($post)) && is_singular()) :
        $post = get_post();
    endif;

    $defaults = [
        'id'     => '',
        'class'  => '',
        'href'   => '',
        'text'   => false,
        'icon'   => true,
        'params' => [
            'method' => 'share',
        ],
    ];
    $args = wp_parse_args($args, $defaults);
    extract($args);

    if ($icon && is_bool($icon)) :
        $icon = \tiFy\Lib\Utils::get_svg(self::tFyAppDirname() . '/assets/logo.svg', false);
    endif;

    if ($params['method'] === 'feed') :
        $defaults = [
            #'app_id'           => $appId,
            #'redirect_uri'     => '',
            #'display'          => '',
            #'from'             => (is_singular() && ! empty($post)) ? get_author_name($post->author_id) : '',
            #'to'               => '',
            'link'        => !empty($post) ? get_permalink($post->ID) : $href,
            'picture'     => (!empty($post) && ($attachment_id = get_post_thumbnail_id($post->ID))) ? wp_get_attachment_url($attachment_id) : '',
            #'source'           => ''
            'name'        => !empty($post) ? $post->post_title : get_bloginfo('name'),
            'caption'     => '',
            'description' => !empty($post) ? $post->post_excerpt : get_bloginfo('description'),
            #'ref'              => '',
            #'callback_attrs'   => array()
        ];
        $params = is_array($params) ? wp_parse_args($params, $defaults) : $defaults;

        if ($params['picture']) :
            foreach (\tiFy\Lib\Facebook\Facebook::$OGImageSizes as $size) :
                // Bypass
                if (!$picture = \tiFy\Lib\Utils::get_context_img_src($params['picture'], $size[0], $size[1],
                    true)) {
                    continue;
                }

                $params['picture'] = $picture;
                break;
            endforeach;
        endif;

        if (!$href && $params['link']) :
            $href = $params['link'];
        endif;
    else :
        // @see https://developers.facebook.com/docs/sharing/reference/share-dialog
        if (!$href) :
            global $wp;
            $href = home_url(add_query_arg([], $wp->request));
        endif;

        $params['href'] = !empty($post) ? get_permalink($post->ID) : $href;
    endif;

    $output = "<a";
    $output .= " href=\"{$href}\"";
    $output .= " class=\"{$class}\"";
    $output .= " data-tiFyPluginSocialNetwork=\"facebook\"";
    $output .= " data-action=\"tify-fb-api_share_button\"";
    $output .= " data-params=\"" . (htmlentities(json_encode($params))) . "\"";
    $output .= ">{$icon}{$text}</a>";

    if ($echo) :
        echo $output;
    else :
        return $output;
    endif;
}*/