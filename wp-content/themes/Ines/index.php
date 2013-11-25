<?php get_header(); ?>

<div id="smain">

<div class="simg">
<?php
if(has_post_thumbnail()) :?>
<?php $image = get_the_post_thumbnail($post->ID, 'gallerie'); ?><?php echo $image; ?></a>
<?php else :?>
<img src="<?php echo get_template_directory_uri(); ?>/sl/rotate.php" alt="<?php the_title(); ?>" />
<?php endif;?>
<h1 class="entry-title"><?php the_title(); ?></h1>
</div>

<div class="breaks">
<?php /* variable for alternating post styles */
$style_classes = array('hc5','hc6','hc8','hc7');
$styles_count = count($style_classes);
$style_index = 0;
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="<?php echo $style_classes[$style_index++ % $styles_count]; ?> view view-first">
<div class="hp">
<a href="<?php the_permalink() ?>">
<?php
if (function_exists('vp_get_thumb_url')) {
        $thumb=vp_get_thumb_url($post->post_content, 'sbrowse');
}
?>
<img src="<?php if ($thumb!='') echo $thumb; ?>" alt="<?php the_title(); ?>" />
</a>
<div class="x"></div>
</div>
<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
<?php the_excerpt(); ?>
<div class="mask">
<h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
<a href="<?php the_permalink() ?>" class="info"><?php _e('Read more', 'Detox') ?></a>
</div>
</div>

<?php endwhile; else: ?>
<div class="navigation hc5"><b><?php _e('Sorry, nothing found, search again?', 'Detox') ?></b></div>
<?php endif; ?>

<div class="navigation hc5">
<?php if(function_exists('wp_pagenavi')) { wp_pagenavi('', '', '', '', 3, false);} ?>
</div>

</div>

</div>
<?php get_template_part('footer'); ?>