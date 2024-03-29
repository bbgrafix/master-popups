<?php
/**
 * Plugin Name: Xbox Framework
 * Plugin URI: http://xboxframework.com
 * Description: Xbox is a powerful framework to create beautiful, professional and flexibles Meta boxes and Admin pages. Building meta boxes and admin pages has never been easier!
 * Version: 1.3.5
 * Author: MaxLopez
 * Author URI: https://codecanyon.net/user/maxlopez
 * Text Domain: xbox
 * Domain Path: /languages/
 */

/*
|---------------------------------------------------------------------------------------------------
| Xbox Framework
|---------------------------------------------------------------------------------------------------
*/

if( ! class_exists( 'XboxLoader135', false ) ) {
	include dirname( __FILE__ ) . '/loader.php';
	$loader = new XboxLoader135( '1.3.5', 965 );
	$loader->init();
}


/*
|---------------------------------------------------------------------------------------------------
| Usage example | These files are just for the example. Comment or remove these lines if you don't need it.
|---------------------------------------------------------------------------------------------------
*/
if( function_exists('my_theme_options') || function_exists('my_simple_metabox') ){
	return;
}

if( ! defined( 'XBOX_HIDE_DEMO' ) || ( defined( 'XBOX_HIDE_DEMO' ) && ! XBOX_HIDE_DEMO ) ){
	include dirname( __FILE__ ) . '/example/admin-page.php';
	include dirname( __FILE__ ) . '/example/metabox.php';
}



