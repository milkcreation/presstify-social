<?php

namespace tiFy\Plugins\Social\NetworkItems\GooglePlus;

use tiFy\Plugins\Social\NetworkItems\AbstractNetworkItem;

class GooglePlus extends AbstractNetworkItem
{
    /** ________________________________________________________________________________________________________________*/
    /**
     * Initialisation globale
     *
     * @return void
     */
    final public function init()
    {
        // Initialisation des scripts
        \wp_register_style(
            'tiFyPlugins-socialNetworksGooglePlus-share',
            self::tFyAppUrl() . '/Share.css',
            [],
            160511
        );
        \wp_register_script(
            'tiFyPlugins-socialNetworksGooglePlus-share',
            self::tFyAppUrl() . '/Share.js',
            ['jquery'],
            '160629',
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
        \wp_enqueue_style('tiFyPlugins-socialNetworksGooglePlus-share');
        \wp_enqueue_script('tiFyPlugins-socialNetworksGooglePlus-share');
    }

    /**
     * Pied de page de l'interface utilisateurs
     *
     * @return string
     */
    final public function wp_footer()
    {
        ?>
        <script type="text/javascript">/* <![CDATA[ */
            window.___gcfg = {
                lang: '<?php echo get_locale();?>'
            };
            (function () {
                var po = document.createElement('script');
                po.type = 'text/javascript';
                po.async = true;
                po.src = 'https://apis.google.com/js/plusone.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(po, s);
            })();
            /* ]]> */</script><?php
    }

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

        $output = "<a href=\"https://plus.google.com/share?url=" . esc_attr($url) . "\"" .
            " onclick=\"javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;\"" .
            " class=\"$class\"" .
            " data-tiFyPluginSocialNetwork=\"googleplus\">" .
            "{$icon}{$text}" .
            "</a>";

        if ($echo) :
            echo $output;
        else :
            return $output;
        endif;
    }
}