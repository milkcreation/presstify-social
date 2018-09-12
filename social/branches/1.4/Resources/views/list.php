<?php
/**
 * @var \tiFy\Plugins\Social\Contracts\NetworkItemViewInterface $this
 */
?>

<?php if ($items = $this->get('items', [])) : ?>
<nav <?php echo $this->htmlAttrs($this->get('attrs', [])); ?>>
    <ul class="tiFySocial-menuItems">
        <?php foreach($items as $item) : ?>
        <li class="tiFySocial-menuItem"><?php echo $item->pageLink(); ?></li>
        <?php endforeach; ?>
    </ul>
</nav>
<?php endif; ?>
