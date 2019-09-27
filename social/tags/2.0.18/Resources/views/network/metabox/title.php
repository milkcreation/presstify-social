<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 * @var tiFy\Plugins\Social\Contracts\NetworkFactory $network
 */
?>
<span class="Social-tabIcon">
    <?php echo $network->getIcon(); ?>
</span>

<span class="Social-tabTitle">
    <?php echo $network->get('title'); ?>
</span>

<span class="Social-tabStatus Social-tabStatus--<?php echo $network->getStatus(); ?>">
    &#x25cf;
</span>
