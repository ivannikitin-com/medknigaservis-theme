<?php
/**
 * elektrika220-380 Theme Customizer
 *
 * @package elektrika220-380
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function medknigaservis_customize_register( $wp_customize ) {

	$wp_customize->add_section(
		'section_one', array(
			'title' => 'Телефон и часы работы',
			'description' => '',
			'priority' => 11,
		)
	);
	$wp_customize->add_setting('phone', 
		array('default' => '')
	);
	$wp_customize->add_control('phone', array(
			'label' => 'Телефон',
			'section' => 'section_one',
			'type' => 'text',
		)
	);
/*	$wp_customize->add_setting('work_hours_weekdays', 
		array('default' => '')
	);
	$wp_customize->add_control('work_hours_weekdays', array(
			'label' => 'Часы работы по будням',
			'section' => 'section_one',
			'type' => 'text',
		)
	);		
	$wp_customize->add_setting('work_hours_weekend', 
		array('default' => '')
	);
	$wp_customize->add_control('work_hours_weekend', array(
			'label' => 'Часы работы по выходным',
			'section' => 'section_one',
			'type' => 'text',
		)
	);	*/
	$wp_customize->add_setting('work_hours_monday', 
		array('default' => '')
	);
	$wp_customize->add_control('work_hours_monday', array(
			'label' => 'Часы работы в Понедельник',
			'section' => 'section_one',
			'type' => 'text',
		)
	);	
	$wp_customize->add_setting('work_hours_tuesday', 
		array('default' => '')
	);
	$wp_customize->add_control('work_hours_tuesday', array(
			'label' => 'Часы работы во Вторник',
			'section' => 'section_one',
			'type' => 'text',
		)
	);
	$wp_customize->add_setting('work_hours_wednesday', 
		array('default' => '')
	);
	$wp_customize->add_control('work_hours_wednesday', array(
			'label' => 'Часы работы в Среду',
			'section' => 'section_one',
			'type' => 'text',
		)
	);
	$wp_customize->add_setting('work_hours_thursday', 
		array('default' => '')
	);
	$wp_customize->add_control('work_hours_thursday', array(
			'label' => 'Часы работы в Четверг',
			'section' => 'section_one',
			'type' => 'text',
		)
	);
	$wp_customize->add_setting('work_hours_friday', 
		array('default' => '')
	);
	$wp_customize->add_control('work_hours_friday', array(
			'label' => 'Часы работы в Пятницу',
			'section' => 'section_one',
			'type' => 'text',
		)
	);
	$wp_customize->add_setting('work_hours_saturday', 
		array('default' => '')
	);
	$wp_customize->add_control('work_hours_saturday', array(
			'label' => 'Часы работы в Субботу',
			'section' => 'section_one',
			'type' => 'text',
		)
	);
	$wp_customize->add_setting('work_hours_sunday', 
		array('default' => '')
	);
	$wp_customize->add_control('work_hours_sunday', array(
			'label' => 'Часы работы в Воскресенье',
			'section' => 'section_one',
			'type' => 'text',
		)
	);	
	$wp_customize->add_section(
		'section_social', array(
			'title' => 'Ссылки на соц. сети',
			'description' => 'Ссылки на группы магазина в соц. сетях',
			'priority' => 11,
		)
	);
	$wp_customize->add_setting('vkontakte', 
		array('default' => '')
	);
	$wp_customize->add_control('vkontakte', array(
			'label' => 'В контакте',
			'section' => 'section_social',
			'type' => 'text',
		)
	);
	$wp_customize->add_setting('odnoklassniki', 
		array('default' => '')
	);
	$wp_customize->add_control('odnoklassniki', array(
			'label' => 'Одноклассники',
			'section' => 'section_social',
			'type' => 'text',
		)
	);	
	$wp_customize->add_setting('facebook', 
		array('default' => '')
	);
	$wp_customize->add_control('facebook', array(
			'label' => 'Facebook',
			'section' => 'section_social',
			'type' => 'text',
		)
	);
	$wp_customize->add_setting('instagram', 
		array('default' => '')
	);
	$wp_customize->add_control('instagram', array(
			'label' => 'Instagram',
			'section' => 'section_social',
			'type' => 'text',
		)
	);	
		
	$wp_customize->add_section(
		'section_subname', array(
			'title' => 'Текст над логотипом',
			'description' => '',
			'priority' => 11,
		)
	);
	$wp_customize->add_setting('subname', 
		array('default' => '')
	);
	$wp_customize->add_control('subname', array(
			'label' => 'Текст над логотипом',
			'section' => 'section_subname',
			'type' => 'text',
		)
	);
}	
add_action( 'customize_register', 'medknigaservis_customize_register' );
