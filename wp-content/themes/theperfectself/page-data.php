<?php
/*
Template Name: data template
*/
?>	
	<?php get_header();?>
<script>




jQuery(function($) {
   $("section").click(function() {
      // remove classes from all
	$(this)().toggleClass("active");
      $("section").removeClass("active");
      $(this).addClass("active");

  });

   $("#myModalButton").click(function() {
	$("#myModal").show(1000);
  });

   $(".close-reveal-modal").click(function() {
	$("#myModal").hide(1000);
  });


});


</script>
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

$tags = array(
    0 => $name,
    1 => 'student'
);


$author_query = array('posts_per_page' => '-1','cat'=> $check ,'post_status' => array( 'publish','draft' ),'author' => $current_user->ID);
$author_posts = new WP_Query($author_query);

if(!$author_posts->have_posts()){
$yourcat= get_category($check);
$name=$yourcat->name;
$new_post = array(
'post_title' => $name." Definition",
'post_content' => 'Please enter a definition for '.$name ,
'post_status' => 'draft',
'tags_input'     => $tags,
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


//echo "new definition created for $name</br> and has level". $key_1_values;

//new master-> 

$tags = array(
    0 => $name,
    1 => 'master'
);



$yourcat= get_category($check);
$name=$yourcat->name;
$new_post = array(
'post_title' => $name ." Story",
'post_content' => 'Please enter a story for '.$name ,
'post_status' => 'draft',
'post_date' => date('Y-m-d H:i:s'),
'tags_input'     => $tags,
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



//echo "new story created for $name</br> and has level". $key_1_values;


}else echo "definition already exists for $name</br>";

echo "</div>";						 
    }
}




function newlevel ($doc_category , $level_number){ 
	
//echo "Doctor Category is $doc_category </br> and last level is $level_number";
global $user_ID;
$check =get_cat_ID( $doc_category );
$array = array(
    0 => $check
);

$author_query = array('posts_per_page' => '-1','category_name'=> $doc_category ,'author' => $current_user->ID);
$author_posts = new WP_Query($author_query);

$tags = array(
    0 => $name,
    1 => 'student'
);

$yourcat= $doc_category;
$name=$doc_category;
$new_post = array(
'post_title' => $name." Definition",
'post_content' => 'Please enter a definition for '.$name ,
'post_status' => 'draft',
'post_date' => date('Y-m-d H:i:s'),
'post_author' => $user_ID,
'post_type' => 'post',
'post_category' => $array,
'tags_input'     => $tags,
);    


$post_id = wp_insert_post($new_post); 
 
$level = $level_number +1;
add_post_meta( $post_id, 'level', $level ); 
add_post_meta( $post_id, 'type', "student" ); 

//echo "new definition created for $name</br> and has level". $key_1_values;

//new master-> 
$tags = array(
    0 => $name,
    1 => 'student'
);

$yourcat= $doc_category;
$new_post = array(
'post_title' => $name ." Story",
'post_content' => 'Please enter a story for '.$name ,
'post_status' => 'draft',
'post_date' => date('Y-m-d H:i:s'),
'post_author' => $user_ID,
'post_parent' => $post_id,
'post_type' => 'post',
'tags_input'     => $tags,
'post_category' => $array
);    

$master_id = wp_insert_post($new_post); 
$level = $level_number +1;
add_post_meta( $master_id, 'level', $level ); 
add_post_meta( $master_id, 'type', "master" ); 
//echo "new story created for $name</br> and has level". $key_1_values;

} 


?>

	<?php 
	if ( is_user_logged_in() ):

if (isset($_POST["post_id"]) && isset($_POST["post_content"])){
  $my_post = array(
      'ID'           => $_POST["post_id"],
      'post_content' => $_POST["post_content"],
      'post_status'  => 'publish'
  );

// Update the post into the database

	if ($_POST["type"] == "student")
	{
	  wp_update_post($my_post );
	echo "<p>Submitted for Evaluation</p>";
}
	else {
    $author_query = array('category_name' => $_POST["category"], 'orderby'=> 'category','post_status' => array( 'draft' ),'order' => 'ASC','author' => $current_user->ID);
    $author_posts = new WP_Query($author_query);
		if ($author_posts->found_posts == 1){
				  wp_update_post( $my_post );
		$a=$_POST["category"] ;
		$b=$_POST["level"];
			newlevel($a,$b);
		echo "Submitted for Evaluation";
		}
		else echo "Please write a story before writing the definition";
	}

}



    global $current_user;
    get_currentuserinfo();
    $author_query = array('posts_per_page' => '-1','orderby'=> 'title','post_status' => array( 'draft' ),'order' => 'ASC','author' => $current_user->ID);
    $author_posts = new WP_Query($author_query);
$c=0;
$count=0;


echo '<a href="#" class=" small button success round" id="myModalButton" data-reveal-id="myModal">Add a Property</a>';
?> <span data-tooltip class="has-tip" title="In your own words write what is (property name) from you to other people.">Student?</span>

<div id="myModal" class="reveal-modal open" style="margin-top: -97px; display: none; -webkit-transform-origin: 0px 0px; opacity: 1; -webkit-transform: scale(1, 1); visibility: visible; top: 100px;">

<div class="section-container auto" data-section="" data-section-resized="true" style="min-height: 49px;">
  <section class="active" style="padding-top: 48px;">
    <p class="title" data-section-title="" style="left: 0px; height: 49px;"><a id="panel0" href="#panel1">Select a property</a></p>
    <div class="content" data-section-content="">
    
    <div class="row">
    
	    <div class="large-12 columns">
	    	
	    	<div class="row">
	    	<?php
$args = array(
  'orderby' => 'name',
  'parent' => 0,
  'hide_empty' => 0
  ); 
$categories = get_categories( $args );
echo "<form action='../exercises/' method='post'>";
echo "<table>";
$s=1;
foreach ( $categories as $category ) {
if($s==1) echo "<tr>";
	//echo '<a href="' . get_category_link( $category->term_id ) . '">' . $category->name . '</a><br/>';
	if ($category->cat_name != 'Uncategorized') {
	echo "<td><input type='checkbox' name='check_list[]' value='$category->term_id'/> <span class='defText'>";  echo $category->cat_name;
    echo '</span></td>';     
	}
if($s%4==0 && $s!=1) echo "</tr><tr>";
$s++;
}
echo "</tr></table>";
echo "<input class='small button' type='submit' /></form>";

	?>
	
	<?php
if(!empty($_POST['check_list'])) {
    foreach($_POST['check_list'] as $check) {
            echo $check; //echoes the value set in the HTML form for each checked checkbox.
                         //so, if I were to check 1, 3, and 5 it would echo value 1, value 3, value 5.
                         //in your case, it would echo whatever $row['Report ID'] is equivalent to.
    }
}

?>
	    	      </div>
	    	     	    	      
	    	      </div>
	    	      
	    
    </div>
    </div>
      
    </section></div>
  
 <a class="close-reveal-modal">×</a>
</div>

<?php


echo '<div class="section-container accordion" data-section="accordion">';
    while($author_posts->have_posts()) : $author_posts->the_post();
	
	$categories = get_the_category();
	$separator = ' ';
	$output = '';
	if($categories){
	foreach($categories as $category) {
	$current_category =$category->cat_name;
	}}


	if($c==0 ){
$key_1_value = get_post_meta( get_the_ID(), 'type', true );
if($count==0){
$temp='active';}
else $temp='';

echo '  <section class="'.$temp.'">
	          <p class="title" data-section-title="" ><a class="panel1" href="#panel1">'.$current_category.'</a></p>
		    <div class="content" data-section-content="">
		      <p>
		      </p><div class="section-container tabs" data-section="tabs" data-section-resized="true" style="min-height: 50px;">';
}	  
 

$key_1_value = get_post_meta( get_the_ID(), 'type', true );
if( $key_1_value=='master' && $c!=1){
$c=1;
$flag=1;
}

if ($c!=1){
$flag=0;
?>

		        <section class="panel_2 active" style="padding-top: 49px;">
		          <p class="title" data-section-title="" style="left: 0px; height: 48px;width: 73px;"><a class="panel1" href="#panel1"><?php $v = get_post_meta( get_the_ID(), 'type', true ); echo $v; ?></a></p>
		          <div class="content" data-section-content="">
		          
		          <div class="row">
		          
		      	    <div class="large-12 columns">
				<form id="new_post" method="post" action="../exercises">		      	    	
		      	    	      <div class="row">
					<input type="hidden" name="post_id" value="<?php $var = get_the_ID();echo $var; ?>">
					<input type="hidden" name="type" value="<?php echo $key_1_value;?>">
<?php 	$key_2_value = get_post_meta( get_the_ID(), 'level', true ); ?>
<?php echo "Level: ".$key_2_value ."<br/>";?>
					<input type="hidden" name="level" value="<?php echo $key_2_value;?>">
					<input type="hidden" name="category" value="<?php echo $current_category;?>">

		      	    	            <div class="large-12 columns">
		      	    	              <textarea style="width: 589px;height: 96px;" name="post_content" cols="40" rows ="10" placeholder=""><?php $text = get_the_content();echo $text; ?></textarea>
		      	    	            </div>
		      	    	          </div>
		      	    	      <div class="row">		      	    	      
		      	    	      <div class="large-12 columns">
		      	    	      	<input type="submit" value="Submit for evaluation">
		      	    	      </div>
	      	    	      </form>
		      	    </div>
		      	    	      
		      	    
		          </div>
		          </div>
<div class="relatedposts">
<h3>Please rate these definitions</h3>
<?php

$authors = array(
    0 => $current_user->ID,
    1 => $current_user->ID,
);

	$orig_post = $post;
	global $post;
	$tags = wp_get_post_tags($post->ID);
	
	if ($tags) {
	$tag_ids = array();
	foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
	$args=array(
	'tag__in' => $tag_ids,
	'author__not_in' => $authors,
	'post__not_in' => array($post->ID),
	'posts_per_page'=>5, // Number of related posts to display.
	'caller_get_posts'=>1
	);
	
	$my_query = new wp_query( $args );

	while( $my_query->have_posts() ) {
	$my_query->the_post();
	?>
	
	<div class="relatedthumb">
		<a rel="external" href="<? the_permalink()?>"><?php the_post_thumbnail(array(150,100)); ?><br />
		<?php the_title(); ?>
		</a>
<?php 
$postRatingData = wp_gdsr_rating_article(get_the_ID());
gdsr_render_stars_custom(array(
    "max_value" => gdsr_settings_get('stars'),
    "size" => 12,
   'max_votes' => 1,
    "vote" => $postRatingData->rating
));
?>
	</div>
	
	<? }
	}
	$post = $orig_post;
	wp_reset_query();
	?>
</div>		          
  
		          </div>


		        </section>


<?php

}

	if ($c==1 ){
?>

<?php 	$key_1_value = get_post_meta( get_the_ID(), 'type', true ); ?>
<?php 	$key_2_value = get_post_meta( get_the_ID(), 'level', true ); ?>


 <section style="padding-top: 49px;" class="panel_2 <?php if ($flag==1) echo 'active';?>">
		         <p class="title" data-section-title="" style="left: 76px;height: 48px;width: 73px;"><a class="panel2" href="#panel2"><?php echo $key_1_value;?></a></p>
		         <div class="content" data-section-content="">		         
			         <div class="row">			         
			             <div class="large-12 columns">			             	
			             <form id="new_post" method="post" action="../exercises">		      	    	
		      	    	      <div class="row">
					<input type="hidden" name="post_id" value="<?php $var = get_the_ID();echo $var; ?>">

					<input type="hidden" name="type" value="<?php echo $key_1_value;?>">
<?php echo "Level: ".$key_2_value ."<br/>";?>
					<input type="hidden" name="level" value="<?php echo $key_2_value;?>">
					<input type="hidden" name="category" value="<?php echo $current_category;?>">

		      	    	            <div class="large-12 columns">
		      	    	              <textarea style="width: 589px;height: 96px;" name="post_content" cols="40" rows ="10" placeholder=""><?php $text = get_the_content();echo $text; ?></textarea>
		      	    	            </div>
		      	    	          </div>
		      	    	      <div class="row">		      	    	      
		      	    	      <div class="large-12 columns">
		      	    	      	<input type="submit" value="Submit for evaluation">
		      	    	      </div>
	      	    	      </form>	
			       	</div>
			         </div>
<div class="relatedposts">
<h3>Please rate these posts</h3>
<?php
$authors = array(
    0 => $current_user->ID,
    1 => $current_user->ID,
);
	$orig_post = $post;
	global $post;
	$tags = wp_get_post_tags($post->ID);
	
	if ($tags) {
	$tag_ids = array();
	foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
	$args=array(
	'tag__in' => $tag_ids,
	'post__not_in' => array($post->ID),
	'posts_per_page'=>5, // Number of related posts to display.
	'author__not_in' => $authors,
	'caller_get_posts'=>1
	);
	
	$my_query = new wp_query( $args );

	while( $my_query->have_posts() ) {
	$my_query->the_post();

	?>
	
	<div class="relatedthumb">
		<a rel="external" href="<? the_permalink()?>"><?php the_post_thumbnail(array(150,100)); ?><br />
		<?php the_title(); ?>
		</a>
<?php 
$postRatingData = wp_gdsr_rating_article(get_the_ID());
gdsr_render_stars_custom(array(
    "max_value" => gdsr_settings_get('stars'),
    "size" => 12,
   'max_votes' => 1,
    "vote" => $postRatingData->rating
));
?>
	</div>
	
	<? }
	}
	$post = $orig_post;
	wp_reset_query();
	?>		 
		         
		         </div>
		       </section>
		      
		      </div>

	
		    </div>


		  </section>

          
<?php
}

if ($c==0){
$c=1;}
else $c=0;

	
	$categories = get_the_category();
	$separator = ' ';
	$output = '';
	if($categories){
	foreach($categories as $category) {
	$last_category =$category->cat_name;
	}}
$count++;

    endwhile;
echo '</div>';


    $author_query = array('posts_per_page' => '-1','orderby'=> 'title','post_status' => array( 'publish' ),'order' => 'ASC','author' => $current_user->ID);
    $author_posts = new WP_Query($author_query);

$count=0;


    while($author_posts->have_posts()) : $author_posts->the_post();
if($count==0){
echo "<h2>Submitted definitions & stories for evaluation </h2>";
$count++;

}
		
	$key_1_value = get_post_meta( get_the_ID(), 'type', true );
	// check if the custom field has a value
//	if ($key_1_value=="student")
	{echo "<div class='prop-box'>";}
	
	if( ! empty( $key_1_value ) ) {
	echo "<div class='".$key_1_value."'><h3>Type: ".$key_1_value."</h3></div>";
	} 
	 
	echo '<h2>';?>
	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>       
	
	<?php
	$categories = get_the_category();
	$separator = ' ';
	$output = '';
	if($categories){
	foreach($categories as $category) {
	$current_id =$category->term_id;
	}}

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

	echo "</div>";
	
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