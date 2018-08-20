<?php
/**
 * @var \tiFy\Plugins\Social\Networks\Instagram\Instagram $network
 * @var array $value Valeurs des options
 */
?>
<table class="form-table">
    <tbody>
    <tr>
        <th scope="row">
            <?php _e('Url du compte Instagram', 'tify'); ?><br>
        </th>
        <td>
            <?php
            tify_field_text(
                [
                    'name'  => 'tify_social_share[instagram][uri]',
                    'value' => $value['uri'],
                    'attrs' => [
                        'size'        => 80,
                        'placeholder' => __('https://instagram.com/[nom du compte]', 'tify')
                    ]
                ]
            );
            ?>
        </td>
    </tr>
    </tbody>
</table>
