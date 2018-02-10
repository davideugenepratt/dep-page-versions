<?php
/**
 * Class DepPageVersionsPluginTest
 *
 * @package Dep_Page_Versions
 */

require_once( "/vagrant/tests/phpunit/includes/WPHelper.php" );

class DepPageVersionsPluginTest extends WP_UnitTestCase {

	public function setUp() {

		parent::setup();

		$this->DepPageVersionsPlugin = new DepPageVersions();

	}

	function test_add_hooks_adds_add_meta_boxes_to_add_meta_boxes_hook() {

		$actual = $this->DepPageVersionsPlugin->add_hooks();

		$has_add_meta_boxes = WPHelper::check_hook_for_callback( 'add_meta_boxes' , 'DepPageVersions', 'add_meta_boxes' );

		$this->assertTrue( $has_add_meta_boxes , "DepPageVersionsPlugin->add_hooks() does not add add_meta_boxes() to 'add_meta_boxes' hook"   );

	}

	function test_add_meta_boxes_adds_meta_box() {

		$actual = $this->DepPageVersionsPlugin->add_meta_boxes();

		$meta_box_is_added = WPHelper::check_screen_for_meta_box( 'page' , 'revisions-box' , 'DepPageVersions', 'revisions_meta_box_callback' , 'side' , 'low' );

		$this->assertTrue( $meta_box_is_added , "DepPageVersionsPlugin->add_meta_boxes() does not add revisions-box as a meta box" );

	}

}
