<?php
/**
 * Second sidebar area 
 */ ?>

<aside id="sidebar2" class="sidebar-container sidebar-primary"<?php wpex_schema_markup( 'sidebar' ); ?>>

	<div id="sidebar2-inner" class="clr">

		<?php
		if ( is_active_sidebar( 'second-aside-sidebar' ) ) {
			dynamic_sidebar( 'titile-second-aside-sidebar' );			
		} 
		if ( is_active_sidebar( 'second-aside-sidebar' ) ) {
			dynamic_sidebar( 'second-aside-sidebar' );
		}?>

	</div><!-- #sidebar-inner -->

</aside><!-- #sidebar -->