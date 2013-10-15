<?php $my_query = new WP_Query('showposts=1&offset=5'); ?>
<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
<div class="hcy">{ <em><?php the_category(' <span> | </span> '); ?></em> }</div>
<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
<div class="r"><a href="<?php the_permalink() ?>"><?php _e('More', 'Detox') ?> &#187; &#187;</a></div>
<?php endwhile; ?>