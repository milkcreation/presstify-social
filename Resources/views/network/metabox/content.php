<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 * @var tiFy\Plugins\Social\Contracts\NetworkFactory $network
 */
?>
<table class="form-table">
    <tbody>
    <tr>
        <th scope="row">
            <?php _e('Activation de la prise en charge du réseau.', 'tify'); ?><br>
        </th>
        <td>
            <?php echo field('toggle-switch', [
                'name'  => $network->get('option_name') . '[active]',
                'value' => $network->isActive() ? 'on' : 'off',
            ]); ?>
        </td>
    </tr>
    </tbody>
</table>
<table class="form-table">
    <tbody>
    <tr>
        <th scope="row">
            <?php _e('Url vers la page du compte.', 'tify'); ?><br>
        </th>
        <td>
            <?php echo field('text', [
                'name'  => $network->get('option_name') . '[uri]',
                'value' => $network->get('uri', ''),
                'attrs' => [
                    'size'        => 80,
                    'placeholder' => __('https://[url-du-service]/[nom-de-la-page]', 'tify'),
                ],
            ]); ?>
            <em style="display:block;font-size:0.8em;color:#AAA;"><?php _e('L\'url doit être renseignée pour que le lien s\'affiche sur votre site.',
                    'tify'); ?></em>
        </td>
    </tr>

    <tr>
        <th scope="row">
            <?php _e('Ordre d\'affichage.', 'tify'); ?><br>
        </th>
        <td>
            <?php echo field('number', [
                'name'  => $network->get('option_name') . '[order]',
                'value' => $network->get('order', 0),
                'attrs' => [
                    'size' => 2,
                ],
            ]); ?>
        </td>
    </tr>
    </tbody>
</table>