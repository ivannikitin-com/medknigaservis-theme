<?php 
/**
 * Child theme functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 * Text Domain: wpex
 * @link http://codex.wordpress.org/Plugin_API
 *
 */

/**
 * Load the parent style.css file
 *
 * @link http://codex.wordpress.org/Child_Themes
 */
function total_child_enqueue_parent_theme_style() {

	// Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update your theme)
	$theme   = wp_get_theme( 'Total' );
	$version = $theme->get( 'Version' );

	// Load the stylesheet
	wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css');
	if (is_page('checkout')){
		wp_enqueue_script( 'checkout-js', get_stylesheet_directory_uri() . '/js/med-checkout.js', array('jquery'), null, true );
		wp_enqueue_script( 'pickpoint-js', 'https://pickpoint.ru/select/postamat.js', array('jquery'), null, true );
		//wp_enqueue_script( 'boxberry-js', 'https://points.boxberry.de/js/boxberry.js', array('jquery'), null, true );
		wp_enqueue_script( 'boxberry-ru-js', 'https://points.boxberry.ru/js/boxberry.js', array('jquery'), null, true );				
	}

	
	wp_enqueue_script( 'maskedinput-js', get_stylesheet_directory_uri() . '/js/jquery.maskedinput.min.js', array('jquery'), null, true );
	wp_enqueue_script( 'medknigaservice-js',  get_stylesheet_directory_uri() .'/js/medknigaservice.js', array('jquery','maskedinput-js'), null, true );
	if (is_page('checkout')){
		wp_enqueue_script( 'addfav-js',  get_stylesheet_directory_uri() . '/js/addfav.js', array('jquery'), null, true );
	}
	if (is_page('books')){
		wp_enqueue_script( 'template-books-js',  get_stylesheet_directory_uri() . '/js/template-books.js', array('jquery'), null, true );
	}	
	// Стилизация полей форм
	wp_enqueue_style( 'formstyler-css', get_stylesheet_directory_uri() . '/jQueryFormStyle/jquery.formstyler.css' );
	wp_enqueue_script( 'formstyler-js', get_stylesheet_directory_uri() . '/jQueryFormStyle/jquery.formstyler.min.js', array('jquery'), null, true );		
	wp_enqueue_style( 'formstylertheme-css', get_stylesheet_directory_uri() . '/jQueryFormStyle/jquery.formstyler.theme.css' );
	wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri().'/css/custom.css' );
}
add_action( 'wp_enqueue_scripts', 'total_child_enqueue_parent_theme_style' );
add_action('admin_enqueue_scripts', 'med_admin_js_css', 99);
 
function med_admin_js_css(){
 
	wp_enqueue_style('med-wp-admin', get_stylesheet_directory_uri() .'/wp-admin-style.css' );
	wp_enqueue_script('med-wp-admin', get_stylesheet_directory_uri() .'/js/med-admin.js', array('jquery'), null, true  );
}

require get_stylesheet_directory() . '/inc/customizer.php';
require get_stylesheet_directory() . '/inc/medknigaservis-functions.php';
require get_stylesheet_directory() . '/inc/woocommerce-functions.php';
require get_stylesheet_directory() . '/inc/gtm.php';
require get_stylesheet_directory() . '/inc/hooks.php';
require get_stylesheet_directory() . '/inc/orderlist.php';
require get_stylesheet_directory() . '/inc/pickpoint.php';
require get_stylesheet_directory() . '/inc/orders2excel.php';
require get_stylesheet_directory() . '/inc/b2cpl.php';
require get_stylesheet_directory() . '/inc/topdelivery.php';
require get_stylesheet_directory() . '/inc/boxberry.php';
require get_stylesheet_directory() . '/inc/ordertags.php';
require get_stylesheet_directory() . '/inc/webpushs.php';

// Формирование SEO TITLE с ISBN
include get_stylesheet_directory() . '/yoast_seo/isbn_title.php';

// Чистим admin bar от всякого хлама
define('UPDRAFTPLUS_ADMINBAR_DISABLE', true);
add_action( 'wp_before_admin_bar_render', function () {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');          // Remove the Wordpress logo
    $wp_admin_bar->remove_menu('about');            // Remove the about Wordpress link
    $wp_admin_bar->remove_menu('wporg');            // Remove the Wordpress.org link
    $wp_admin_bar->remove_menu('documentation');    // Remove the Wordpress documentation link
    $wp_admin_bar->remove_menu('support-forums');   // Remove the support forums link
    $wp_admin_bar->remove_menu('feedback');         // Remove the feedback link
    // $wp_admin_bar->remove_menu('comments');         // Remove the comments link
    $wp_admin_bar->remove_menu('new-content');      // Remove the content link
    $wp_admin_bar->remove_menu('w3tc');             // If you use w3 total cache remove the performance link
    $wp_admin_bar->remove_menu('wpseo-menu');       // Remove Yoast SEO
    $wp_admin_bar->remove_menu('ubermenu');         // Remove ubermenu
    $wp_admin_bar->remove_menu('wpex_custom_css');  // Remove Пользовательский CSS
});

add_action( 'after_setup_theme', 'elk_setup' );
function elk_setup() {
	add_theme_support( 'custom-logo' );	
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	register_nav_menu( 'sidebar_catalog', 'Sidebar Catalog' );
	
}

add_action('wpex_hook_sidebar_inner','med_sidebar_catalog',5);
function med_sidebar_catalog(){ ?>
	<div id="woocommerce_product_categories" class="sidebar-box widget woocommerce widget_product_categories clr">
		<div class="widget-title">Каталог</div>
	<?php wp_nav_menu( array(
	'theme_location'  => 'sidebar_catalog',
	'menu'            => 'catalog', 
	'container'       => 'false', 
	'container_class' => '', 
	'container_id'    => '',
	'menu_class'      => 'product-categories', 
	'menu_id'         => '',
	'echo'            => true,
	'fallback_cb'     => '__return_empty_string',
	'before'          => '',
	'after'           => '',
	'link_before'     => '',
	'link_after'      => '',
	'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	'depth'           => 0,
	'walker'          => '',
) ); ?>
	</div>
<?php }

add_action( 'widgets_init', 'med_register_sidebar_widget_areas' );
function med_register_sidebar_widget_areas() {
	$tag = wpex_get_mod( 'sidebar_headings' );
	$tag = $tag ? $tag : 'div';
	
	register_sidebar( array(
		'name'          => __( 'Заголовок для нижней боковой колонки', 'medknigaservis' ),
		'id'			=> 'titile-second-aside-sidebar',
		'before_widget' => '<div id="%1$s" class="sidebar-box widget %2$s clr">',
		'after_widget'  => '</div>',
		'before_title'  => '<' . $tag . ' class="widget-title">',
		'after_title'   => '</' . $tag . '>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Нижняя боковая колонка', 'medknigaservis' ),
		'id'			=> 'second-aside-sidebar',
		'before_widget' => '<div id="%1$s" class="sidebar-box widget %2$s clr">',
		'after_widget'  => '</div>',
		'before_title'  => '<' . $tag . ' class="widget-title">',
		'after_title'   => '</' . $tag . '>',
	) );	
}

add_filter( 'gettext', 'theme_change_translations', 20, 3 );
function theme_change_translations( $translated_text, $text, $domain ) {

        switch ( $translated_text ) {

            case 'Sale!' :

                $translated_text = 'Выгодно!';
                break;
        }

    return $translated_text;
}
