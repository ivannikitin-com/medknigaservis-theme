<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 4.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<div id="content-wrap" class="container clr">

		<?php wpex_hook_primary_before(); ?>

		<div id="primary" class="content-area clr">

			<?php wpex_hook_content_before(); ?>

			<main id="content" class="clr site-content" role="main">

				<?php wpex_hook_content_top(); ?>

				<article class="entry clr">

					<?php
					// Check custom page content
					if ( wpex_get_mod( 'error_page_content_id' ) && $id = wpex_get_current_post_id() ) :

						// Get post
						$post = get_post( $id );

						// Echo post content and apply the_content filters
						echo wpex_the_content( $post->post_content, 'error404' );

					else :

						// Get error text
						$error_text = trim( wpex_get_translated_theme_mod( 'error_page_text' ) );

						// Display custom text
						if ( $error_text )  : ?>

							<div class="custom-error404-content clr">
								<?php echo wpex_the_content( $error_text, 'error404' ); ?>
							</div><!-- .custom-error404-content -->

						<?php
						// Display default text
						else : ?>

							<div class="error404-content clr">
								<h1>Хорошая попытка!</h1>
								<p><b>Но товар, который Вы искали в данный момент отсутствует по двум причинам:<br />или закончился тираж, или он был слишком популярен.</b></p>
								<p>Во втором случае он у нас появится в самое ближайшее время.</p>
								<img class="img_thanks" src="http://medknigaservis.ru/wp-content/uploads/2019/04/404.png">
							</div><!-- .error404-content -->
							
							<div data-retailrocket-markup-block="5ca5d4c097a52523207d83c7" ></div>

						<?php endif; ?>

					<?php endif; ?>

				</article><!-- .entry -->

				<?php wpex_hook_content_bottom(); ?>

			</main><!-- #content -->

			<?php wpex_hook_content_after(); ?>

		</div><!-- #primary -->

		<?php wpex_hook_primary_after(); ?>

	</div><!-- .container -->

<?php get_footer(); ?>