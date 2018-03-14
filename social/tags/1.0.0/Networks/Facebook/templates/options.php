<?php
/**
 * @var \tiFy\Plugins\Social\Networks\Facebook\Facebook $network
 * @var array $value Valeurs des options
 */
?>
<table class="form-table">
    <tbody>
    <?php if ($network->getAttr('share', false)) : ?>
        <tr>
            <th scope="row">
                <?php _e('Identifiant de l\'API Facebook', 'tify'); ?>*<br>
                <em style="font-size:11px; color:#999;"><?php _e('Requis', 'tify'); ?></em>
            </th>
            <td>
                <?php
                tify_field_text(
                    [
                        'name'  => 'tify_social_share[fb][appId]',
                        'value' => $value['appId']
                    ]
                );
                ?>
            </td>
        </tr>
    <?php else : ?>
        <?php
        tify_field_hidden(
            [
                'name'  => 'tify_social_share[fb][appId]',
                'value' => ''
            ]
        );
        ?>
    <?php endif; ?>
    <tr>
        <th scope="row">
            <?php _e('Url de la page Facebook', 'tify'); ?><br>
        </th>
        <td>
            <?php
            tify_field_text(
                [
                    'name'  => 'tify_social_share[fb][uri]',
                    'value' => $value['uri'],
                    'attrs' => [
                        'size'        => 80,
                        'placeholder' => __('https://www.facebook.com/[nom de la page]', 'tify')
                    ]
                ]
            );
            ?>
        </td>
    </tr>
    </tbody>
</table>
