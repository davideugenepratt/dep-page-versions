<?php
/**
 * Plugin Name:     DEP Page Versions
 * Plugin URI:
 * Description:     Allows for delayed publishing of revisions
 * Author:          David Pratt
 * Author URI:      http://www.davideugenepratt.com
 * Text Domain:     dep-page-versions
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Dep_Wp_Page_Versions
 */

spl_autoload_register( function ( $class_name ) {

	$path = str_replace( "_" , "/" , $class_name);

	$file_path = plugin_dir_path( __FILE__ ) . 'includes/' . $path . ".php";

	if( file_exists( $file_path ) ) {

		require_once( $file_path );

	}

});

$DepPageVersionsPlugin = new DepPageVersionsPlugin();
