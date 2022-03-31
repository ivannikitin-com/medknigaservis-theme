<?php
/**
 * The template for displaying search forms
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Placeholder
$placeholder = apply_filters( 'wpex_mobile_searchform_placeholder', __( 'Search', 'total' ), 'main' ); ?>
<form class="search_f clr" method="get" id="searchform" action="/">
	<div class="col_i">
		<input type="search" placeholder="Введите название книги, её автора или ISBN" class="i_search" name="s" id="s" required="required" value="<?php the_search_query(); ?>">
		<button type="reset" title="Кликни, чтобы очистить поле">&times;</button>
	</div>
	<?php if ( defined( 'ICL_LANGUAGE_CODE' ) ) : ?>
		<input type="hidden" name="lang" value="<?php echo( ICL_LANGUAGE_CODE ); ?>"/>
	<?php endif; ?>
	<input type="hidden" name="post_type" value="product" />
    <div class="col_b">
			<button type="submit" value="" class="b_search"><span class="hidden-xs">Найти книгу</span></button>
    </div>
</form>