<?php
 
/**
 * The header for medknigaservice theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 4.0
 */ 
 ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?><?php wpex_schema_markup( 'html' ); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>

<!-- Begin Body -->
<!-- <body <?php //body_class(); ?>> -->
<body class="archive tax-product_cat term-studentam-vuzov term-24 theme-Total woocommerce woocommerce-page woocommerce-js yith-wcbm-theme-total wpex-theme wpex-responsive full-width-main-layout no-composer wpex-live-site hasnt-overlay-header wpex-has-fixed-footer page-header-disabled smooth-fonts wpex-mobile-toggle-menu-icon_buttons has-mobile-menu wpb-js-composer js-comp-ver-6.5.0 vc_responsive customize-support wpex-is-retina wpex-window-loaded custom-tax-product">    

<?php wpex_outer_wrap_before(); ?>

<div id="outer-wrap" class="clr">

	<?php wpex_hook_wrap_before(); ?>

	<div id="wrap" class="clr">

		<?php //wpex_hook_wrap_top(); ?>

		<?php wpex_hook_main_before(); ?>

		<main id="main" class="site-main clr"<?php wpex_schema_markup( 'main' ); ?>>