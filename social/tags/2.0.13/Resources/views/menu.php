<?php
/**
 * Social - Menu d'affichage des liens vers la page des comptes des réseaux actifs.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var \tiFy\Plugins\Social\Contracts\NetworkViewer $this
 */
?>

<?php if ($items = $this->get('items', [])) : ?>
<nav <?php echo $this->htmlAttrs($this->get('attrs', [])); ?>>
    <ul class="Social-menuItems">
        <?php foreach($items as $item) : ?>
        <li class="Social-menuItem"><?php echo $item->pageLink(); ?></li>
        <?php endforeach; ?>
    </ul>
</nav>
<?php endif; ?>
