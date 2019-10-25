<?php
/** ________________________________________________________________________________________________________________ */
/**
 * Initialisation globale
 * @todo
 *
 * @return void
 */
final public function init()
{
    // Initialisation des scripts
    \wp_register_style(
        'tiFyPlugins-socialNetworksTwitter-share',
        self::tFyAppUrl() . '/Share.css',
        [],
        160511
    );
    \wp_register_script(
        'twitter-widgets',
        '//platform.twitter.com/widgets.js',
        [],
        150109,
        true
    );
    \wp_register_script(
        'tiFyPlugins-socialNetworksTwitter-share',
        self::tFyAppUrl() . '/Share.js',
        ['jquery', 'twitter-widgets'],
        160629,
        true
    );
}

    /**
     * Mise en file des scripts de l'interface utilisateur
     *
     * @return void
     */
    final public function wp_enqueue_scripts()
{
    \wp_enqueue_style('tiFyPlugins-socialNetworksTwitter-share');
    \wp_enqueue_script('tiFyPlugins-socialNetworksTwitter-share');
}

    /**
     * CONTROLEURS
     */
    /**
     * Lien de partage
     *
     * @param int $post_id Identifiant de qualification du post à partager
     * @param array $args Attributs de configuration du partage
     * @param bool $echo Activation de l'affichage
     *
     * @return null|string
     */
    public static function shareButton($post = 0, $args = [], $echo = true)
{
    global $wp;

    static $Instance;
    $Instance++;

    if (!($post = get_post($post)) && is_singular()) :
        $post = get_post();
    endif;

    $defaults = [
        'id'    => '',
        'class' => '',
        'text'  => false,
        'icon'  => true,
        'url'   => !empty($post) ? wp_get_shortlink($post->ID) : home_url(add_query_arg([], $wp->request)),
    ];
    $args = wp_parse_args($args, $defaults);
    extract($args);

    if ($icon && is_bool($icon)) :
        $icon = \tiFy\Lib\Utils::get_svg(self::tFyAppDirname() . '/assets/logo.svg', false);
    endif;


    $output = "<a href=\"https://twitter.com/intent/tweet?url=" . esc_attr($url) . "&text=" . esc_attr($text) . "\"" .
        " class=\"$class\"" .
        " data-tiFyPluginSocialNetwork=\"twitter\">" .
        "{$icon}{$text}" .
        "</a>";

    if ($echo) :
        echo $output;
    else :
        return $output;
    endif;
}