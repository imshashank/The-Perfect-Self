<?php
/*
Template Name: fullwidth template
*/
?>
<?php get_header(); ?>

<div id="smain">


<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<h1><?php the_title(); ?></h1>

<div class="entry">
<?php the_content(__('Read more', 'Detox'));?>
<?php wp_link_pages('before=<div id="navigation">&after=</div>'); ?>
</div>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.', 'Detox'); ?></p>
<?php endif; ?>

<div id="navigation">
<div class="alignleft">
<?php _e('You are here', 'Detox'); ?>: <?php if (function_exists('dimox_breadcrumbs')) dimox_breadcrumbs(); ?>
</div>

</div>

</div>
<?php get_template_part('footer'); ?>