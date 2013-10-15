<?php get_template_part('header'); ?>

<div id="smain">
<div id="post" <?php post_class(); ?>>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="simg">
<?php
if(has_post_thumbnail()) :?>
<?php $image = get_the_post_thumbnail($post->ID, 'gallerie'); ?><?php echo $image; ?></a>
<?php else :?>
<img src="<?php echo get_template_directory_uri(); ?>/sl/rotate.php" alt="<?php the_title(); ?>" />
<?php endif;?>
<h1 class="entry-title"><?php the_title(); ?></h1>
</div>

<div class="entry">
<?php the_content(__('Read more', 'Detox'));?>
<div class="clearfix"></div><hr class="clear" />
<?php wp_link_pages('before=<div id="page-links">&after=</div>'); ?>
<?php edit_post_link('<h3>Edit</h3>','',''); ?>
</div>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.', 'Detox') ?></p>
<?php endif; ?>

<div class="clearfix"></div><hr class="clear" />
<div id="sharer">


<div class="s1">
<?php if ( !function_exists('dynamic_sidebar')
		        || !dynamic_sidebar('pagebar') ) : ?> 
<?php endif; ?>
</div>

<div class="s2">
<h3><?php _e('Share it', 'Detox') ?></h3>
<a title="<?php _e('Share it', 'Detox') ?>" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&#38;t=<?php the_title(); ?>">
<img src="<?php echo get_stylesheet_directory_uri() ?>/images/fb.jpg" alt="del" /></a>
<a title="<?php _e('Share it', 'Detox') ?>" href="http://twitter.com/home?status=Currently reading <?php the_permalink(); ?>">
<img src="<?php echo get_stylesheet_directory_uri() ?>/images/twi.jpg" alt="tech" /></a>
</div>

</div>

<div id="navigation">
<?php _e('You are here', 'Detox') ?>: <?php if (function_exists('dimox_breadcrumbs')) dimox_breadcrumbs(); ?>
</div>
</div>
<?php get_template_part('footer'); ?>