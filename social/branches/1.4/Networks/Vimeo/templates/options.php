<?php
/**
 * @var \tiFy\Plugins\Social\Networks\Vimeo\Vimeo $network
 * @var array $value Valeurs des options
 */
?>
<table class="form-table">
    <tbody>
    <tr>
        <th scope="row">
            <?php _e('Url de la chaîne Vimeo', 'tify'); ?><br>
        </th>
        <td>
            <?php
            tify_field_text(
                [
                    'name'  => 'tify_social_share[vimeo][uri]',
                    'value' => $value['uri'],
                    'attrs' => [
                        'size'        => 80,
                        'placeholder' => __('https://vimeo.com/channels/[nom de la chaîne]', 'tify')
                    ]
                ]
            );
            ?>
        </td>
    </tr>
    </tbody>
</table>
