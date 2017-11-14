<?php
    foreach($results as $item):
?>
<div class="sw__item sw__item--<?php echo $item->getPostType(); ?>">
    <?php if($item->getPhoto()): ?>
    <a class="sw__item__image" href="<?php echo $item->getlink(); ?>" target="_blank">
        <img src="<?php echo $item->getPhoto(); ?>" >
    </a>
    <?php endif; ?>
    <div class="sw__item__content">
        <a class="sw__item__username" href="<?php echo $item->getlink(); ?>" target="_blank">
            <?php echo $item->getTitle(); ?>
        </a>
        <div class="sw__item__date">
            <?php echo date('d F',strtotime($item->getCreateTime())); ?>
        </div>
        <div class="sw__item__text">
            <?php echo $item->getContent(); ?>
        </div>
    </div>
</div>
<?php
    endforeach;
?>
