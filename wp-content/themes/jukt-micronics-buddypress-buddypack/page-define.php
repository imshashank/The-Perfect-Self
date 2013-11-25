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
'post_title' => $name." Definition",
'post_content' => 'Please enter a definition for '.$name ,
'post_status' => 'draft',
'post_date' => date('Y-m-d H:i:s'),
'post_author' => $user_ID,
'post_type' => 'post',
'post_category' => $array
);    


$post_id = wp_insert_post($new_post); 
 
if (!$key_1_values)
{
add_post_meta( $post_id, 'level', 1 ); 
add_post_meta( $post_id, 'type', "student" ); 
}
else
{
$key_1_values++;
add_post_meta( $post_id, 'level', $key_1_values ); 
}


echo "new definition created for $name</br> and has level". $key_1_values;

//new master-> 

$yourcat= get_category($check);
$name=$yourcat->name;
$new_post = array(
'post_title' => $name ." Story",
'post_content' => 'Please enter a definition for '.$name ,
'post_status' => 'draft',
'post_date' => date('Y-m-d H:i:s'),
'post_author' => $user_ID,
'post_parent' => $post_id,
'post_type' => 'post',
'post_category' => $array
);    

$master_id = wp_insert_post($new_post); 

if (!$key_1_values)
{
add_post_meta( $master_id, 'level', 1 ); 
add_post_meta( $master_id, 'type', "master" ); 
}
else
{
$key_1_values++;
add_post_meta( $master_id, 'level', $key_1_values ); 
}



echo "new story created for $name</br> and has level". $key_1_values;


}else echo "definition already exists for $name</br>";

echo "</div>";						 
    }
}
?>

		
	<?php 
	if ( is_user_logged_in() ):

    global $current_user;
    get_currentuserinfo();
    $author_query = array('posts_per_page' => '-1','post_status' => array( 'draft', 'publish' ),'orderby'=> 'title','order' => 'ASC','author' => $current_user->ID);
    $author_posts = new WP_Query($author_query);
	
    while($author_posts->have_posts()) : $author_posts->the_post();
	
		
	$key_1_value = get_post_meta( get_the_ID(), 'type', true );
	// check if the custom field has a value
	if ($key_1_value=="student")
	{echo "<div class='prop-box'>";}
	
	if( ! empty( $key_1_value ) ) {
	echo "<div class='".$key_1_value."'><h3>Type: ".$key_1_value."</h3></div>";
	} 
	 
	echo '<h2>';?>
	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>       
	
	
	<?php
	$rating = wp_gdsr_show_article_rating	 (	 
$post_id = 0,
$use_default = true,
$size = 20,
$style = "oxygen",
$echo = true	  
);
echo $rating;	
	echo '</h2>';
	the_content();
	
	if ($key_1_value=="master")
	{echo "</div>";}
	
	
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
