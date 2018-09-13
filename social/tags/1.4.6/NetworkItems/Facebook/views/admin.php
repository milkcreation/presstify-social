<?php
/**
 * @var \tiFy\Plugins\Social\Contracts\NetworkItemViewInterface $this
 */
?>

<table class="form-table">
    <tbody>
    <tr>
        <th scope="row">
            <?php _e('Identifiant de l\'API Facebook', 'tify'); ?>*<br>
            <em style="font-size:11px; color:#999;"><?php _e('Requis', 'tify'); ?></em>
        </th>
        <td>
            <?php
            tify_field_text(
                [
                    'name'  => $this->get('option_name') . '[appId]',
                    'value' => $this->get('appId', '')
                ]
            );
            ?>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <?php _e('Url vers la page du compte', 'tify'); ?><br>
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
        </td>
    </tr>
    </tbody>
</table>
