<?php
/*
Template Name: define tempaate
*/
?>	
	<?php get_header();?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ); ?>

		<div class="page" id="blog-page" role="main">
		
	<?php
if(!empty($_POST['check_list'])) {
    foreach($_POST['check_list'] as $check) {
            //echo $check."</br>"; //echoes the value set in the HTML form for each checked checkbox.
                         //so, if I were to check 1, 3, and 5 it would echo value 1, value 3, value 5.
                         //in your case, it would echo whatever $row['Report ID'] is equivalent to.
						  echo"<div class='demo'> ";
global $user_ID;
$array = array(
    0 => $check
);


$author_query = array('posts_per_page' => '-1','cat'=> $check ,'author' => $current_user->ID);
$author_posts = new WP_Query($author_query);

if(!$author_posts->have_posts()){
$yourcat= get_category($check);
$name=$yourcat->name;
$new_post = array(
'post_title' => $name,
'post_content' => 'Please enter a definition for '.$name ,
'post_status' => 'publish',
'post_date' => date('Y-m-d H:i:s'),
'post_author' => $user_ID,
'post_type' => 'post',
'post_category' => $array
);
$post_id = wp_insert_post($new_post); 
echo "new definition created for $name</br>";


}else echo "definition already exists</br>";

echo "</div>";						 
    }
}
?>

		
	<?php 
	if ( is_user_logged_in() ):

    global $current_user;
    get_currentuserinfo();
    $author_query = array('posts_per_page' => '-1','author' => $current_user->ID);
    $author_posts = new WP_Query($author_query);
    while($author_posts->have_posts()) : $author_posts->the_post();echo '<h2>';?>
	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>       
	<?php
	echo '</h2>';
	the_content();
    ?>
        
    <?php           
    endwhile;

else :

    echo "not logged in";

endif;
	?>
	

		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
