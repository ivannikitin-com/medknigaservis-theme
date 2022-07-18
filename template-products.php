<?php
/**
 * Template Name: Custom product category template
 *
 */
if (isset($_GET['term'])) {
   $term_slug = $_GET['term'];
} else {
    return;
}

remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open',10 );

add_filter( 'woocommerce_loop_add_to_cart_args', 'add_target_attribute', 10, 2 );
function add_target_attribute($args, $product) 
{
    $args['attributes']['target'] = '_top';    

    return $args;
}

remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
//add_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart_target', 15 );
function woocommerce_widget_shopping_cart_button_view_cart_cart() {
    echo '<a href="' . str_replace('https', 'http', esc_url( wc_get_cart_url() )) . '" class="button wc-forward" target="_top">' . esc_html__( 'View cart', 'woocommerce' ) . '</a>';
}

add_filter('woocommerce_catalog_orderby','in_woocommerce_catalog_orderby');

function in_woocommerce_catalog_orderby($args){
    unset($args['rating']);
    return $args;
}

//remove_action( 'woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart', 13 );
add_filter('woocommerce_loop_add_to_cart_link', 'replace_add_to_cart_by_simple_link',5,3);
function replace_add_to_cart_by_simple_link($lnk_html,$product,$arg) {
    $html =  sprintf(
        '<a href="%s" class="%s" %s target="_blank">%s</a>',
        esc_url( $product->get_permalink() ),
        esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
        isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
        esc_html( $product->add_to_cart_text() )
    );
    return $html;

}

get_header('custom');?>
<?php
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$posts_per_page  = apply_filters('loop_shop_per_page', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page());
//$term_slug = 'kosmetologiya';
$term = get_term_by('slug', $term_slug, 'product_cat'); 
$term_name = $term->name;
$ordering = $_GET['orderby'];
if ($ordering) {
    switch($ordering){
        case '':
            $meta_key = '';
            $order = 'asc';
            $orderby = 'menu_order title';
            break;
        case 'popularity':
            $meta_key = 'total_sales';
            $order = 'DESC';
            $orderby = [ 'meta_value_num'=>'DESC' ];
            break;
        case 'price':
            $meta_key = '_price';
            $order = 'ASC';
            $orderby = [ 'meta_value_num'=>'ASC' ];
            break;
        case 'price-desc':
            $meta_key = '_price';
            $order = 'DESC';
            $orderby = [ 'meta_value_num'=>'DESC' ];
            break;
        case 'date':
            $meta_key = '';
            $order = 'DESC';
            $orderby = 'post_date';
            break;
    }

}
$args = array(
    'post_type' => 'product',
    'posts_per_page' => $posts_per_page,
    'paged'          => $paged,
    'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $term_slug,
            ),
        ),
    'orderby' => $orderby,
    'order' => $order,
    'meta_key' => $meta_key
    );
$loop = new WP_Query( $args ); ?>
    <div id="content-wrap" class="container clr"> 
        <div id="primary" class="content-area clr">    
            <div id="content" class="clr site-content">
                <article class="entry-content entry books clr">
                    <header class="woocommerce-products-header">
                            <h1 class="woocommerce-products-header__title page-title"><?php echo $term_name; ?></h1>
                    </header>
                    <?php //do_action( 'woocommerce_before_shop_loop' ); ?>
                    <?php custom_query_products_woocommerce_result_count($loop, $posts_per_page); ?>
                    <?php custom_query_products_woocommerce_catalog_ordering($loop, $term_slug); ?>
                    <!-- <ul class="products wpex-row clr"> -->
                    <?php woocommerce_product_loop_start(); ?>
                    <?php
                    if ( $loop->have_posts() ) {
                        while ( $loop->have_posts() ) : $loop->the_post(); ?>
                            <?php wc_get_template_part( 'content', 'iframe_product' ); ?>
                        <?php endwhile;
                        do_action( 'woocommerce_after_shop_loop' );
                    } else {
                        echo __( 'No products found' );
                    }
                    wp_reset_postdata();
                    ?>
                    <?php woocommerce_product_loop_end(); ?>
                    <?php //do_action( 'woocommerce_after_shop_loop' ); ?>
                    <?php custom_query_products_pagination($loop); ?>
                </article>
            </div>
        </div>
    </div><!-- .container -->    
<?php get_footer('custom'); ?>
<?php 

function custom_query_products_woocommerce_result_count($loop, $posts_per_page) {
    $total = $loop->found_posts;
    $per_page = $posts_per_page;
    $current = (int) $loop->query_vars['paged']; 
    ?>
<p class="woocommerce-result-count">
    <?php
    // phpcs:disable WordPress.Security
    if ( 1 === intval( $total ) ) {
        _e( 'Showing the single result', 'woocommerce' );
    } elseif ( $total <= $per_page || -1 === $per_page ) {
        /* translators: %d: total results */
        printf( _n( 'Showing all %d result', 'Showing all %d results', $total, 'woocommerce' ), $total );
    } else {
        $first = ( $per_page * $current ) - $per_page + 1;
        $last  = min( $total, $per_page * $current );
        /* translators: 1: first result 2: last result 3: total results */
        printf( _nx( 'Showing %1$d&ndash;%2$d of %3$d result', 'Showing %1$d&ndash;%2$d of %3$d results', $total, 'with first and last result', 'woocommerce' ), $first, $last, $total );
    }
    // phpcs:enable WordPress.Security
    ?>
</p>    
<?php
}

function custom_query_products_woocommerce_catalog_ordering($loop, $term_slug){
    $catalog_orderby_options = apply_filters(
        'woocommerce_catalog_orderby',
        array(
            'menu_order' => __( 'Default sorting', 'woocommerce' ),
            'popularity' => __( 'Sort by popularity', 'woocommerce' ),
            'date'       => __( 'Sort by latest', 'woocommerce' ),
            'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
            'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
        )
    );
    $show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
    $default_orderby = apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );    
    $orderby = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby;
    if ( ! $show_default_orderby ) {
        unset( $catalog_orderby_options['menu_order'] );
    }

    if ( ! wc_review_ratings_enabled() ) {
        unset( $catalog_orderby_options['rating'] );
    }

    if ( ! array_key_exists( $orderby, $catalog_orderby_options ) ) {
        $orderby = current( array_keys( $catalog_orderby_options ) );
    }   
?>
    <form class="woocommerce-ordering" method="get" action="<?php echo esc_url_raw(get_pagenum_link( 999999999, false ) ); ?>">
        <select name="orderby" class="orderby" aria-label="<?php esc_attr_e( 'Shop order', 'woocommerce' ); ?>">
            <?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
                <option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="hidden" name="paged" value="1" />
        <?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
    </form>
<?php
}

function custom_query_products_pagination($loop) {
    $args = array(
        'total'   => $loop->max_num_pages,
        'current' => (int) $loop->query_vars['paged'],
        'base'    => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
        'format'  => '',
    );
    echo '<div class="hidden">'.$args['base'].'</div>';
    if ($args['total']>1) { ?>  
    <nav class="woocommerce-pagination">
    <?php
/*        echo paginate_links(
            apply_filters(
                'woocommerce_pagination_args',
                array( // WPCS: XSS ok.
                    'base'      => $args['base'],
                    'format'    => $args['format'],
                    'add_args'  => false,
                    'current'   => max( 1, $args['current'] ),
                    'total'     => $args['total'],
                    'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
                    'next_text' => is_rtl() ? '&larr;' : '&rarr;',
                    'type'      => 'list',
                    'end_size'  => 3,
                    'mid_size'  => 3,
                )
            )
        );*/
        echo str_replace('http:','https:',paginate_links(
                array( // WPCS: XSS ok.
                    'base'      => $args['base'],
                    'format'    => $args['format'],
                    'add_args'  => false,
                    'current'   => max( 1, $args['current'] ),
                    'total'     => $args['total'],
                    'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
                    'next_text' => is_rtl() ? '&larr;' : '&rarr;',
                    'type'      => 'list',
                    'end_size'  => 3,
                    'mid_size'  => 3,
                )
        ));        
    ?>
    </nav>
    <?php } ?>
<?php } ?>