<?php
/*
Template Name: definitiosn tempaate
*/
?>	
	<?php get_header(); echo "I work !!";?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ); ?>

		<div class="page" id="blog-page" role="main">

			<?php

$args = array(
  'orderby' => 'name',
  'parent' => 0,
  'hide_empty' => 0
  ); 
$categories = get_categories( $args );
echo "<form action='../data/' method='post'>";

foreach ( $categories as $category ) {
	//echo '<a href="' . get_category_link( $category->term_id ) . '">' . $category->name . '</a><br/>';
	if ($category->cat_name != 'Uncategorized') {
	echo "<input type='checkbox' name='check_list[]' value='$category->term_id'/> <span class='defText'>";  echo $category->cat_name;
    echo '</span><br>';     
	}
}
echo "<input type='submit' /></form>";

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


		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
