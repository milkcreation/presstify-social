<?php
/**
 * @var tiFy\Plugins\Social\Contracts\NetworkItemViewInterface $this.
 */
?>

<span class="Social-tabIcon">
    <?php echo $this->getIcon(); ?>
</span>

<span class="Social-tabTitle">
    <?php echo $this->get('title'); ?>
</span>

<span class="Social-tabStatus Social-tabStatus--<?php echo $this->getStatus(); ?>">
    &#x25cf;
</span>
