<?php
/**
 * @var tiFy\Contracts\Metabox\MetaboxView $this
 * @var tiFy\Plugins\Social\Contracts\ChannelDriver $channel
 */
?>
<span class="Social-tabIcon">
    <?php echo $channel->getIcon(); ?>
</span>
<span class="Social-tabTitle">
    <?php echo $channel->get('title'); ?>
</span>
<span class="Social-tabStatus Social-tabStatus--<?php echo $channel->getStatus(); ?>">&#x25cf;</span>
