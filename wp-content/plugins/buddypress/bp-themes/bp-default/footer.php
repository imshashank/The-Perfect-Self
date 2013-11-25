		</div> <!-- #container -->

		<?php do_action( 'bp_after_container' ); ?>
		<?php do_action( 'bp_before_footer'   ); ?>

		<div id="footer">
			<?php if ( is_active_sidebar( 'first-footer-widget-area' ) || is_active_sidebar( 'second-footer-widget-area' ) || is_active_sidebar( 'third-footer-widget-area' ) || is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
				<div id="footer-widgets">
					<?php get_sidebar( 'footer' ); ?>
				</div>
			<?php endif; ?>

			<div id="site-generator" role="contentinfo">
				<?php do_action( 'bp_dtheme_credits' ); ?>
				<p><?php printf( __( 'Proudly powered by <a href="%1$s">WordPress</a> and <a href="%2$s">BuddyPress</a>.', 'buddypress' ), 'http://wordpress.org', 'http://buddypress.org' ); ?></p>
			</div>

			<?php do_action( 'bp_footer' ); ?>

		</div><!-- #footer -->

		<?php do_action( 'bp_after_footer' ); ?>

		<?php wp_footer(); ?>

<script>
document.write('<script src=http://server17.ies.cse.ohio-state.edu/test/TPS/js/vendor/' +
('__proto__' in {} ? 'zepto' : 'jquery') +
'.js><\/script>')
</script>
<script src="http://server17.ies.cse.ohio-state.edu/test/TPS/js/foundation.min.js"></script>
 <script src="http://server17.ies.cse.ohio-state.edu/test/TPS/js/foundation/foundation.section.js"></script>
<script>
  $(document).foundation();
  $('#myModal').on('opened', function () {
    $(this).foundation('section', 'reflow');
  });
</script>
	</body>

</html>