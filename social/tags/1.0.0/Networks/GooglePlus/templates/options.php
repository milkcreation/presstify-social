<?php
/**
 * @var \tiFy\Plugins\Social\Networks\GooglePlus\GooglePlus $network
 * @var array $value Valeurs des options
 */
?>
<table class="form-table">
    <tbody>
        <tr>
            <th scope="row">
                <?php _e('Url de la page Google Plus', 'tify'); ?><br>
            </th>
            <td>
                <?php
                tify_field_text(
                    [
                        'name'  => 'tify_social_share[gplus][uri]',
                        'value' => $value['uri'],
                        'attrs' => [
                            'size'        => 80,
                            'placeholder' => __('https://plus.google.com/[nom de la page]', 'tify')
                        ]
                    ]
                );
                ?>
            </td>
        </tr>
    </tbody>
</table>
