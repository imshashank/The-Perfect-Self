<?php get_template_part('header'); ?>

<div id="smain">

<?php if ( (is_home())  ) { ?>
<?php get_template_part('front'); ?>
<?php } else { ?><?php } ?>

<div class="breaks">

<div class="hc3">

<?php
if ( function_exists( 'bp_is_active' ) ){
	get_template_part('log');
} else {
	get_template_part('post');
}
?>

</div>

<div class="hc4">
<?php
if ( function_exists( 'bp_is_active' ) ){
		get_template_part('mpost');
} else {
	get_template_part('fpost');
}
?>
</div>

<div class="breaks">
<?php
if ( function_exists( 'bp_is_active' ) ){
		get_template_part('fbp');
} else {
}
?>
</div>

<?php /* variable for alternating post styles */
$style_classes = array('hc5','hc6','hc8','hc7');
$styles_count = count($style_classes);
$style_index = 0;
?>
<?php $my_query = new WP_Query('showposts=9&offset=6'); ?>
<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
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
<?php endwhile; ?>

</div>

<div class="msp">
<div class="c1">

<div id="slider2" class="sliderwrapper">

<?php $my_query = new WP_Query('showposts=4&offset=4'); ?>
<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>

<div class="contentdiv">

<div class="simg">
<div class="hp"><a href="<?php the_permalink() ?>">
<?php
if (function_exists('vp_get_thumb_url')) {
        $thumb=vp_get_thumb_url($post->post_content, 'sbrowse');
}
?>
<img src="<?php if ($thumb!='') echo $thumb; ?>" alt="<?php the_title(); ?>" />
</a></div>
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
autorotate: [true, 12500], 
onChange: function(previndex, curindex){ 
}
})
</script>

</div>
</div>

<div class="c11">
<div class="c2">

<?php $my_query = new WP_Query('showposts=1&offset=8'); ?>
<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>

<div class="simg">
<div class="sls"><h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2></div>
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
</div>
<?php get_footer(); ?>