<?php
/**
 * @var \tiFy\Plugins\Social\Networks\YouTube\YouTube $network
 * @var array $value Valeurs des options
 */
?>
<table class="form-table">
    <tbody>
    <tr>
        <th scope="row">
            <?php _e('Url de la chaîne You Tube', 'tify'); ?><br>
        </th>
        <td>
            <?php
            tify_field_text(
                [
                    'name'  => 'tify_social_share[youtube][uri]',
                    'value' => $value['uri'],
                    'attrs' => [
                        'size'        => 80,
                        'placeholder' => __('https://www.youtube.com/channel/[nom de la chaîne]', 'tify')
                    ]
                ]
            );
            ?>
        </td>
    </tr>
    </tbody>
</table>
