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
<body <?php body_class(); ?>>

<?php wpex_outer_wrap_before(); ?>

<div id="outer-wrap" class="clr">

	<?php wpex_hook_wrap_before(); ?>

	<div id="wrap" class="clr">

		<?php //wpex_hook_wrap_top(); ?>

		<?php wpex_hook_main_before(); ?>

		<main id="main" class="site-main clr"<?php wpex_schema_markup( 'main' ); ?>>

			
<div id="header_top_wrap">
            	<div id="header_top" class="container clr">
                	<div id="nav-wrap" class="col_left">
                        <!--<ul class="nav clr">
                            <li><a href="#">Доставка</a></li>
                            <li><a href="#">Оплата</a></li>
                            <li><a href="#">Скидки и акции</a></li>
                            <li><a href="#">Помощь</a></li>
                            <li><a href="#">Юридическим лицам</a></li>
                            <li><a href="#">Написать руководителю</a></li>
                        </ul>-->
						<?php // Menu arguments
						$menu_args = apply_filters( 'wpex_header_menu_args', array(
							'theme_location'  => 'main_menu',
							'menu_class'     => 'nav clr',
							'container'      => false,
							'fallback_cb'    => false,
							'link_before'    => '',
							'link_after'     => '',
							/*'walker'         => new WPEX_Dropdown_Walker_Nav_Menu(),*/
						) );
                        wp_nav_menu( $menu_args ); ?>
                        
                    </div>
                    <!-- Mobile menu -->
                        <div id="mobile-menu" class="clr wpex-mobile-menu-toggle wpex-hidden">
                        	<a href="#" class="mobile-menu-toggle"><span class="fa fa-navicon"></span></a>
                        </div>
                    
                    <div class="account">
                    	<span class="fa fa-user"></span>
						<?php if (is_user_logged_in()) { 
							global $current_user, $display_name; 
							$current_user = wp_get_current_user();?>
						<a href="/my-account/edit-address/" title="Личный кабинет" class="wpex-login">Личный кабинет<?php //echo $current_user->display_name;?></a><span>&nbsp;/&nbsp;</span>
						<a href="<?php echo esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) ); ?>" title="Выход" class="wpex-login">Выход</a>
						<?php } else { ?>
                        <a href="/my-account/edit-address/" title="Войти" class="wpex-login">Вход<span>&nbsp;/&nbsp;</span>Регистрация</a>
						<?php } ?>
                    </div><!--/.account-->
                </div><!--/#header_top-->
            </div><!--/#header_top_wrap-->
			
            <div class="container">            
             
            <div id="header_main" class="clr">
            	<div class="col_3 logo">				
					<?php 
					$subname = get_theme_mod('subname', '');
					if ($subname){ ?>					
						<div class="sub_name_shop"><?php echo $subname; ?></div>
					<?php } ?>
                    <div class="logo_h">
						<?php if (is_front_page() || is_cart() ) { ?>
                        <!-- Для Главной страницы -->
                        <span class="logo"><img src="<?php echo get_theme_mod('custom_logo', ''); ?>" class="img-responsive"></span>
						<?php } else { ?>
						<a class="logo" href="/" title="На Главную"><img src="<?php echo get_theme_mod('custom_logo', ''); ?>" class="img-responsive"></a>
						<?php } ?>
					</div>
                </div><!--/.col_3-->
                
            	<div class="col_3 opening_hours">
                	<div class="fs12">Время работы (по Москве)</div>
                    <div class="fs14">
						<?php $days=array();
						$hours=array();
						$days_options['пн&nbsp;&bull;&nbsp;']=get_theme_mod('work_hours_monday', '');
						$days_options['вт&nbsp;&bull;&nbsp;']=get_theme_mod('work_hours_tuesday', '');
						$days_options['ср&nbsp;&bull;&nbsp;']=get_theme_mod('work_hours_wednesday', '');
						$days_options['чт&nbsp;&bull;&nbsp;']=get_theme_mod('work_hours_thursday', '');
						$days_options['пт&nbsp;&bull;&nbsp;']=get_theme_mod('work_hours_friday', '');
						$days_options['сб&nbsp;&bull;&nbsp;']=get_theme_mod('work_hours_saturday', '');
						$days_options['вс']=get_theme_mod('work_hours_sunday', '');
						$curvalue='-';
						$i=-1;

						foreach ($days_options as $key => $value){
							if ($value === $curvalue){
								$days[$i] .= $key;
							} else {
								$i++;
								$color=($value)?'c_3e9a8a':'c_fb3f3b';
								$border=($value)?'brd':'brd fff';
								$days[$i] = '<div style="display:inline-block;"><b class="'.$color.'">'.$key;
								$hours[$i] = '</b><div class="txt-center '.$border.' fs11">&nbsp;'. $value. '&nbsp;</div></div>';
								$curvalue=$value;
							}
						}
						for ($k=0; $k<=$i; $k++){
							echo $days[$k];
							echo $hours[$k];
						}
						?>
                    </div>
                </div><!--/.col_3-->
                
            	<div class="col_3 phone">
            		<div class="socials">
            			<?php  $vkontakte_lnk = get_theme_mod('vkontakte', '');
            			if ($vkontakte_lnk) { ?>
            			<a href="<?php echo $vkontakte_lnk; ?>"><span class="fa fa-vk"></span></a>
						<?php } ?>
						<?php $odnoklassniki_lnk = get_theme_mod('odnoklassniki', '');
						if ($odnoklassniki_lnk) { ?>
					    <a href="<?php echo $odnoklassniki_lnk; ?>"><span class="fa fa-odnoklassniki"></span></a>
					    <?php } ?>
					    <?php $facebook_lnk = get_theme_mod('facebook', '');
					    if ($facebook_lnk) { ?>
					    <a href="<?php echo $facebook_lnk; ?>"><span class="fa fa-facebook"></span></a>
					    <?php } ?>
					    <?php $instagram_lnk = get_theme_mod('instagram', '');
					    if ($instagram_lnk) { ?>
					    <a href="<?php echo $instagram_lnk; ?>"><span class="fa fa-instagram"></span></a>
					    <?php } ?>
					</div>
                <div class="fs12">Звонок по России бесплатный</div>
                	<div class="tel">
                    	<span class="fa fa-phone"></span>
                    	<a href="tel:<?php echo phone_clean(get_theme_mod('phone', '')); ?>"><?php echo get_theme_mod('phone', ''); ?></a>
                    </div>
                </div><!--/.col_3-->
                
            </div><!--/#header_main-->
			<div id="header_bott" class="clr">
				<?php if (is_cart() || is_checkout()) { ?>
				<!-- На предыдущую страницу -->
                <div class="col_0">
                    <div id="history_back">
                        <a href="#" class="hidden">
							<?php _e('< Вернуться назад' ,'medknigaservis'); ?>
                        </a>
                    </div><!--/#history_back-->
                </div><!--/.col_0-->
            	<?php } ?>
            	<?php if (!is_cart() && !is_checkout() ) { ?>
                <div class="col_1">
                    <!-- Search-->
					<?php get_search_form(); ?>
                </div><!--/.col_1-->
                <?php } ?>
                
                <!-- Мини-корзина -->
                <div class="col_2">
                    <div id="mini_cart">
                        <a href="<?php echo wc_get_cart_url(); ?>" class="menucart<?php echo (is_page('cart') || is_page('checkout'))?' disabled':''?>">
                        	<span class="fa fa-shopping-cart"></span>
                            <span class="hidden-xs">Товаров на</span>
                            <span> <?php echo WC()->cart->get_cart_total(); ?></span>
                        </a>
                    </div><!--/#mini_cart-->
                </div><!--/.col_2-->
			</div><!--/#header_bott-->
			<?php wpex_hook_header_after(); ?>
			<!-- Баннеры -->
			<?php $left_banner=get_field('left_banner',2);
			$left_banner_lnk=get_field('left_banner_lnk',2);
			if (!$left_banner) {$left_banner = get_stylesheet_directory_uri().'/img/banner.jpg';}
			$right_banner=get_field('right_banner',2);
			if (!$right_banner) {$right_banner = get_stylesheet_directory_uri().'/img/banner.jpg';}
			$right_banner_lnk=get_field('right_banner_lnk',2); ?>
            <div class="info_blocks">
            	<?php if (!is_cart() && !is_checkout()){ ?>
				<div class="mk-row clr">
					<div class="col-sm-6 col-md-6">
						<?php if ($left_banner_lnk) { ?><a href="<?php echo sanitize_text_field($left_banner_lnk); ?>"><?php }?>
						<img src="<?php echo $left_banner; ?>" class="img-responsive">
						<?php if ($left_banner_lnk) { ?></a><?php }?>
					</div>
                    <div class="col-sm-6 col-md-6">
						<?php if ($right_banner_lnk) { ?><a href="<?php echo sanitize_text_field($right_banner_lnk); ?>"><?php }?>
                    	<img src="<?php echo $right_banner ?>" class="img-responsive">
						<?php if ($right_banner_lnk) { ?></a><?php }?>
                    </div>
				</div><!--/.row-->
				<?php } ?>
        	</div><!--/.info_blocks-->
			
			<?php wpex_hook_main_top(); ?>
    	</div><!--/.container-->