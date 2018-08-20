<?php
/**
 * @var \tiFy\Plugins\Social\Networks\LinkedIn\LinkedIn $network
 * @var array $value Valeurs des options
 */
?>
<table class="form-table">
    <tbody>
    <tr>
        <th scope="row">
            <?php _e('Url du compte LinkedIn', 'tify'); ?><br>
        </th>
        <td>
            <?php
            tify_field_text(
                [
                    'name'  => 'tify_social_share[linkedin][uri]',
                    'value' => $value['uri'],
                    'attrs' => [
                        'size'        => 80,
                        'placeholder' => __('https://www.linkedin.com/profile/[nom du compte]', 'tify')
                    ]
                ]
            );
            ?>
        </td>
    </tr>
    </tbody>
</table>
