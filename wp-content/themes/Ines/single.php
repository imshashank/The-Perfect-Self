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

<div class="meta">
{ <em><small><?php _e('Topic', 'Detox') ?>: <?php the_category(' | ') ?><?php the_tags(' | ',' | '); ?> <?php edit_post_link(' | Edit','',''); ?></small></em> }
<div class="rmeta"><span class="dates"><?php _e( 'on', 'Detox') ?> <?php the_date('M j', '<span class="bigdate">', '</span>'); ?></span></div>
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

<div id="sharer">

<div class="s1">
<?php if ( !function_exists('dynamic_sidebar')
		        || !dynamic_sidebar('sidebar') ) : ?>
<h3><?php _e('Widgetized', 'Detox') ?></h3>
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

<div class="meta">

<div class="postauthor">
<?php if ( get_the_author_meta( 'description' ) ) :  ?>

<div id="author-description">
<h3><?php printf( esc_attr__( 'About %s' ), get_the_author() ); ?> :</h3>
<div class="author-avatar">
<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'author_bio_avatar_size', 60 ) ); ?>
</div>

<?php the_author_meta( 'description' ); ?> | <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'Tracks' ), get_the_author() ); ?></a>
</div>
<?php endif; ?>
</div>

<div class="rel">
<h3><?php _e( 'Related', 'Detox') ?></h3>
<?php 
$max_articles = 4; // How many articles to display 
echo '<ul>'; 
$cnt = 0; $article_tags = get_the_tags(); 
$tags_string = ''; 
if ($article_tags) { 
foreach ($article_tags as $article_tag) { 
$tags_string .= $article_tag->slug . ','; 
} 
} 
$tag_related_posts = get_posts('exclude=' . $post->ID . '&numberposts=' . $max_articles . '&tag=' . $tags_string); 
if ($tag_related_posts) { 
foreach ($tag_related_posts as $related_post) { 
$cnt++; 
echo '<li class="child-' . $cnt . '">'; 
echo '<a href="' . get_permalink($related_post->ID) . '">'; 
echo $related_post->post_title . '</a></li>'; 
} 
} 
if ($cnt < $max_articles) { 
$article_categories = get_the_category($post->ID); 
$category_string = ''; 
foreach($article_categories as $category) { 
$category_string .= $category->cat_ID . ','; 
} 
$cat_related_posts = get_posts('exclude=' . $post->ID . '&numberposts=' . $max_articles . '&category=' . $category_string); 
if ($cat_related_posts) { 
foreach ($cat_related_posts as $related_post) { 
$cnt++; 
if ($cnt > $max_articles) break; 
echo '<li class="child-' . $cnt . '">'; 
echo '<a href="' . get_permalink($related_post->ID) . '">'; 
echo $related_post->post_title . '</a></li>'; 
} 
} 
} 
echo '</ul>'; 
?>
</div>

</div>


<div class="post-navigation clear">
                <?php
                    $prev_post = get_adjacent_post(false, '', true);
                    $next_post = get_adjacent_post(false, '', false); ?>
                    <?php if ($prev_post) : $prev_post_url = get_permalink($prev_post->ID); $prev_post_title = $prev_post->post_title; ?>
                        <a class="post-prev" href="<?php echo $prev_post_url; ?>"><em><?php _e( 'Previous post', 'Detox') ?></em><span><?php echo $prev_post_title; ?></span></a>
                    <?php endif; ?>
                    <?php if ($next_post) : $next_post_url = get_permalink($next_post->ID); $next_post_title = $next_post->post_title; ?>
                        <a class="post-next" href="<?php echo $next_post_url; ?>"><em><?php _e( 'Next post', 'Detox') ?></em><span><?php echo $next_post_title; ?></span></a>
                    <?php endif; ?>
                <div class="line"></div>
</div>

<div class="clearfix"></div><hr class="clear" />
<?php comments_template(); ?> 

</div>
</div>
<?php get_template_part('footer'); ?>