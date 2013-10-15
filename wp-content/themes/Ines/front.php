<?php if ( is_user_logged_in() ) : ?>

<div class="simg">
<?php
if(has_post_thumbnail()) :?>
<?php $image = get_the_post_thumbnail($post->ID, 'gallerie'); ?><?php echo $image; ?></a>
<?php else :?>
<img src="<?php echo get_template_directory_uri(); ?>/sl/rotate.php" alt="<?php the_title(); ?>" />
<?php endif;?>
</div>

<?php else : ?>

<div class="fo">
<div id="slider1" class="sliderwrapper">

<?php 
	$my_query = new WP_Query('showposts=4');
while ($my_query->have_posts()) : $my_query->the_post();$do_not_duplicate = $post->ID;
?>

<div class="contentdiv">

<div class="fimg">
<div class="sll">
<h1><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
<?php the_excerpt(); ?>
<div class="hcx">{ <em><?php _e('on', 'Detox') ?> <?php the_time('M'); ?><?php the_time('j'); ?> <?php the_time('Y'); ?> | <?php _e('in', 'Detox') ?>: <?php the_category(' | ') ?></em> }</div>
</div>
<a href="<?php the_permalink() ?>">
<?php
if (function_exists('vp_get_thumb_url')) {
        $thumb=vp_get_thumb_url($post->post_content, 'gallerie');
}
?>
<img src="<?php if ($thumb!='') echo $thumb; ?>" alt="<?php the_title(); ?>" />
</a>
</div>

</div>

<?php endwhile; ?>

<div id="paginate-slider1" class="pagination"></div>
<script type="text/javascript">
featuredcontentslider.init({
id: "slider1", 
contentsource: ["inline", ""], 
toc: "#increment", 
nextprev: ["", ""], 
revealtype: "mouseover", 
enablefade: [true, 0.6], 
autorotate: [true, 9500], 
onChange: function(previndex, curindex){ 
}
})
</script>

</div>
</div>
<?php endif; ?>