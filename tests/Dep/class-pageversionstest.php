<?php
/**
 * Class Dep\PageVersionsTest
 *
 * @package Dep_Page_Versions
 */
namespace Dep;
require_once( '/vagrant/tests/class-wphelper.php' );

class PageVersionsTest extends \WP_UnitTestCase {
	public $page_versions;
	public function setUp() {

		parent::setup();

		$this->page_versions = new \Dep\PageVersions();

	}

	function test_add_hooks_adds_add_meta_boxes_to_add_meta_boxes_hook() {

		$actual = $this->page_versions->add_hooks();

		$has_add_meta_boxes = \WPHelper::check_hook_for_callback( 'add_meta_boxes', '\Dep\PageVersions', 'add_meta_boxes' );

		$this->assertTrue( $has_add_meta_boxes, "Dep\PageVersions->add_hooks() does not add add_meta_boxes() to 'add_meta_boxes' hook" );

	}

	function test_add_meta_boxes_adds_meta_box() {

		$actual = $this->page_versions->add_meta_boxes();

		$meta_box_is_added = \WPHelper::check_screen_for_meta_box( 'page', 'revisions-box', '\Dep\PageVersions', 'revisions_meta_box_callback', 'side', 'low' );

		$this->assertTrue( $meta_box_is_added, 'Dep\PageVersions->add_meta_boxes() does not add revisions-box as a meta box' );

	}

}
