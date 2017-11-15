<?php
    if(!empty($results)):
?>
<div class="asocials">
    <div class="container">
        <div class="asocials__wrapper">
            <ul class="asocials__wrapper-icons">
                <li><a href="#" data-filter=".facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                <li><a href="#" data-filter=".twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                <li><a href="#" data-filter=".youtube"><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>
                <li><a href="#"data-filter=".linkedin" ><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li>
            </ul>
            <div class="asocials__wrapper--container">
                <?php
                foreach($results as $item):
                    ?>
                    <div class="as__item <?php echo $item->getPostType(); ?>">
                        <?php if($item->getPhoto()): ?>
                            <a class="as__item__image" href="<?php echo $item->getlink(); ?>" target="_blank">
                                <img src="<?php echo $item->getPhoto(); ?>" >
                            </a>
                        <?php endif; ?>
                        <div class="as__item__content">
                            <a class="as__item__username" href="<?php echo $item->getlink(); ?>" target="_blank">
                                <?php echo $item->getTitle(); ?>
                            </a>
                            <div class="as__item__date">
                                <?php echo date('d F',strtotime($item->getCreateTime())); ?>
                            </div>
                            <div class="as__item__text">
                                <?php echo $item->getContent(); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                endforeach;
                ?>
            </div>
        </div>
    </div>
</div>
<?php
    endif;
?>
