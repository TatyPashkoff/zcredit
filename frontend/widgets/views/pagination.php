<?php
?>
<div class="<?=$options['class']?>">

    <?php
    $count_pages = ceil($totalCount / $defaultPageSize );
    $left = $page - 1;

    $right = $count_pages - $page;
    if ($left < floor($defaultPageSize / 2)) {
        $start = 1;
    }else {
        $start = $page - floor($defaultPageSize / 2);
    }

    $end = $start + $defaultPageSize - 1;

    if ($end > $count_pages) {
        $start -= ($end - $count_pages);
        $end = $count_pages;
        if ($start < 1) $start = 1;
    }

    //echo $left;

    //echo  '['. $defaultPageSize . '] ' . $totalCount . ' cp' . $count_pages . ' p' .  $p . ' s' . $start . ' e' . $end;

    if( $totalCount > 0 && $count_pages >= 1 ){ ?>

        <a href="<?=$url_prev?>" class="<?=$options['link_prev_class']?>">
            <img src="/images/icons/paginate-prev.png" alt="">
            <i class="<?=$options['link_prev_icon_class']?>"></i>
        </a>

        <?php for ($p = $start; $p <= $end; $p++) { ?>
            <a href="/get-news?page=<?=$p . $url ?>" class="<?=$options['link_class']?><?=$p==$page ? ' ' . $options['link_active'] : '' ?>"><?=$p?></a>
        <?php } ?>

        <a href="<?=$url_next?>" class="<?=$options['link_next_class']?>">
            <img src="/images/icons/paginate-next.png" alt="">
            <i class="<?=$options['link_next_icon_class']?>"></i>
        </a>

    <?php } ?>

</div>