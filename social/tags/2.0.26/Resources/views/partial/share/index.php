<?php
/**
 * @var tiFy\Contracts\Partial\PartialView $this
 * @var tiFy\Plugins\Social\Contracts\ChannelDriver $item
 * @var tiFy\Wordpress\Contracts\Query\QueryPost|null $post
 */
?>
<?php if ($items = $this->get('items', [])) : ?>
    <nav <?php echo $this->htmlAttrs($this->get('attrs', [])); ?>>
        <ul class="Social-shareChannels">
            <?php foreach ($items as $item) : ?>
                <li class="Social-shareChannel Social-shareChannel--<?php echo $item->getName(); ?>">
                    <?php $this->insert('item', compact('item', 'post')); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
<?php endif;