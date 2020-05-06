<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 * @var tiFy\Plugins\Social\Contracts\ChannelDriver $channel
 */
?>
<table class="form-table">
    <tbody>
    <tr>
        <th>
            <?php _e('Activation de la prise en charge du réseau.', 'tify'); ?><br>
        </th>
        <td>
            <?php echo field('toggle-switch', [
                'name'  => $this->name() . '[active]',
                'value' => $this->value('active') ? 'on' : 'off',
            ]); ?>
        </td>
    </tr>
    </tbody>
</table>
<table class="form-table">
    <tbody>
    <tr>
        <th>
            <?php _e('Url vers la page du compte.', 'tify'); ?><br>
        </th>
        <td>
            <?php echo field('text', [
                'name'  => $this->name() . '[uri]',
                'value' => $this->value('uri'),
                'attrs' => [
                    'size'        => 80,
                    'placeholder' => __('https://[url-du-service]/[nom-de-la-page]', 'tify'),
                ],
            ]); ?>
            <em style="display:block;font-size:0.8em;color:#AAA;">
                <?php _e('L\'url doit être renseignée pour que le lien s\'affiche sur votre site.', 'tify'); ?>
            </em>
        </td>
    </tr>
    <tr>
        <th>
            <?php _e('Identifiant de profile.', 'tify'); ?><br>
        </th>
        <td>
            <?php echo field('text', [
                'name'  => $this->name() . '[profile_id]',
                'value' => $this->value('profile_id'),
                'attrs' => [
                    'size' => 80,
                ],
            ]); ?>
            <em style="display:block;font-size:0.8em;color:#AAA;">
                <?php _e('Requis pour générer les liens vers l\'application sur mobile.', 'tify'); ?>
                <?php echo partial('tag', [
                    'attrs'   => [
                        'href'   => 'https://findmyfbid.com/',
                        'title'  => __('Service en ligne de récupération d\'ID de profile Facebook', 'theme'),
                        'target' => '_blank',
                    ],
                    'content' => __('Retrouver l\'ID de profile.', 'tify'),
                    'tag'     => 'a',
                ]); ?>
            </em>
        </td>
    </tr>
    <tr>
        <th>
            <?php _e('Ordre d\'affichage.', 'tify'); ?><br>
        </th>
        <td>
            <?php echo field('number', [
                'name'  => $this->name() . '[order]',
                'value' => $this->value('order'),
                'attrs' => [
                    'size' => 2,
                ],
            ]); ?>
        </td>
    </tr>
    </tbody>
</table>