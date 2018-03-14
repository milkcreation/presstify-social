<?php
/**
 * @var \tiFy\Plugins\Social\Networks\Twitter\Twitter $network
 * @var array $value Valeurs des options
 */
?>
<table class="form-table">
    <tbody>
    <tr>
        <th scope="row">
            <?php _e('Url du compte Twitter', 'tify'); ?>
        </th>
        <td>
            <?php
            tify_field_text(
                [
                    'name'  => 'tify_social_share[tweet][uri]',
                    'value' => $value['uri'],
                    'attrs' => [
                        'size'        => 80,
                        'placeholder' => __('https://twitter.com/[nom de la page]', 'tify')
                    ]
                ]
            );
            ?>
        </td>
    </tr>
    </tbody>
</table>
