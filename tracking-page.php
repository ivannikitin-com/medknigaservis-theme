<?php
/**
 * The template for displaying tracking widget.
 *
 * Template Name: Tracking widget
 */

get_header(); ?>

	<div id="content-wrap" class="container clr">

		<?php wpex_hook_primary_before(); ?>

		<div id="primary" class="content-area clr">

			<?php wpex_hook_content_before(); ?>

			<div id="content" class="site-content clr">

				<?php wpex_hook_content_top(); ?>

				<?php
				// Start loop
				while ( have_posts() ) : the_post();

					wpex_get_template_part( 'page_single_blocks' );
				?>
				
				<div id="tracking-widget-container"></div>

				<!-- MetashipTrackingWidget -->
				  <script type="text/javascript">
				    (function (P, i, m, p, a, y) {
				      P[p] = P[p] || function () {
				        (P[p].q = P[p].q || []).push(arguments);
				      };
				      a = i.createElement('script');
				      a.async = 1;
				      a.src = m;
				      if (i.head) {
				        i.head.appendChild(a);
				      }
				    })(window, document, 'https://tracking.metaship.ru/widget/t-widget.js', 'MetashipTrackingWidget');
				 
				    MetashipTrackingWidget('insert', {
				      el: 'tracking-widget-container',
				      shopTags: ['3eG']
				    });
				  </script>
				<!-- /MetashipTrackingWidget -->

				<?php 	

				endwhile; ?>

				<?php wpex_hook_content_bottom(); ?>

			</div><!-- #content -->

			<?php wpex_hook_content_after(); ?>

		</div><!-- #primary -->

		<?php wpex_hook_primary_after(); ?>

	</div><!-- .container -->

<?php get_footer(); ?>