<?php
/**
 * @var tiFy\Contracts\Partial\PartialView $this
 * @var tiFy\Plugins\Social\Contracts\ChannelDriver $item
 */
?>
<?php if ($items = $this->get('items', [])) : ?>
<nav <?php echo $this->htmlAttrs($this->get('attrs', [])); ?>>
    <ul class="Social-menuChannels">
        <?php foreach($items as $item) : ?>
        <li class="Social-menuChannel"><?php echo $item->pageLink(); ?></li>
        <?php endforeach; ?>
    </ul>
</nav>
<?php endif;