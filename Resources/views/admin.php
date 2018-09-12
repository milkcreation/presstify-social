<?php
/**
 * @var \tiFy\Plugins\Social\Contracts\NetworkItemViewInterface $this
 */
?>

<table class="form-table">
    <tbody>
    <tr>
        <th scope="row">
            <?php _e('Activation de la prise en charge du réseau.', 'tify'); ?><br>
        </th>
        <td>
            <?php
            tify_field_toggle_switch(
                [
                    'name'  => $this->get('option_name') . '[active]',
                    'value' => $this->isActive() ? 'on' : 'off'
                ]
            );
            ?>
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
            <?php
            tify_field_text(
                [
                    'name'  => $this->get('option_name') . '[uri]',
                    'value' => $this->get('uri', ''),
                    'attrs' => [
                        'size'        => 80,
                        'placeholder' => __('https://[url-du-service]/[nom-de-la-page]', 'tify')
                    ]
                ]
            );
            ?>
            <em style="display:block;font-size:0.8em;color:#AAA;"><?php _e('L\'url doit être renseignée pour que le lien s\'affiche sur votre site.', 'tify'); ?></em>
        </td>
    </tr>

    <tr>
        <th scope="row">
            <?php _e('Ordre d\'affichage.', 'tify'); ?><br>
        </th>
        <td>
            <?php
            tify_field_number(
                [
                    'name'  => $this->get('option_name') . '[order]',
                    'value' => $this->get('order', 0),
                    'attrs' => [
                        'size'        => 2,
                    ]
                ]
            );
            ?>
        </td>
    </tr>
    </tbody>
</table>
