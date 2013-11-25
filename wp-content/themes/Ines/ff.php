<div class="msp">
<div class="c1">

<div id="slider2" class="sliderwrapper">

<?php 
		$my_query = new WP_Query('showposts=4&offset=0');
while ($my_query->have_posts()) : $my_query->the_post();$do_not_duplicate = $post->ID;
?>

<div class="contentdiv">

<div class="simg">
<a href="<?php the_permalink() ?>">
<?php
if (function_exists('vp_get_thumb_url')) {
        $thumb=vp_get_thumb_url($post->post_content, 'fteaser');
}
?>
<img src="<?php if ($thumb!='') echo $thumb; ?>" alt="<?php the_title(); ?>" />
</a>
</div>
<div class="hcx">{ <em><?php _e('on', 'Detox') ?> <?php the_time('M'); ?><?php the_time('j'); ?> <?php the_time('Y'); ?> | <?php _e('in', 'Detox') ?>: <?php the_category(' | ') ?></em> }</div>
<h2><a href="<?php the_permalink() ?>" ><?php the_title(); ?></a></h2>
<?php the_excerpt(); ?>

</div>

<?php endwhile; ?>

<div id="paginate-slider2" class="pagination"></div>
<script type="text/javascript">
featuredcontentslider.init({
id: "slider2", 
contentsource: ["inline", ""], 
toc: "#increment", 
nextprev: ["", ""], 
revealtype: "mouseover", 
enablefade: [true, 0.6], 
autorotate: [true, 3500], 
onChange: function(previndex, curindex){ 
}
})
</script>

</div>


</div>

<div class="c11">
<div class="c2">

<?php 
	$my_query = new WP_Query('showposts=1&offset=4');
while ($my_query->have_posts()) : $my_query->the_post();$do_not_duplicate = $post->ID;
?>

<div class="simg">
<a href="<?php the_permalink() ?>">
<?php
if (function_exists('vp_get_thumb_url')) {
        $thumb=vp_get_thumb_url($post->post_content, 'fteaser');
}
?>
<img src="<?php if ($thumb!='') echo $thumb; ?>" alt="<?php the_title(); ?>" />
</a>
</div>
<?php endwhile; ?>

</div>

<div class="c3">
<?php if ( !function_exists('dynamic_sidebar')
		        || !dynamic_sidebar('widgetbar') ) : ?> 
            
<h3>Widgetized Sidebar</h3>

<?php endif; ?>
</div>

</div>

</div>