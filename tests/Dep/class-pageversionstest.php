<?php
/**
 * Class PageVersionsTest
 *
 * @package Dep
 * @category Class
 * @author   David Pratt
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.org/davideugenepratt
 */

namespace Dep;
require_once( '/vagrant/tests/class-wphelper.php' );

/**
 * Class PageVersionsTest
 *
 * @package Dep
 */
class PageVersionsTest extends \WP_UnitTestCase {
	/**
	 * Instance of PageVersions that will be created in setUp().
	 *
	 * @var PageVersions $page_versions instance of PageVersions to test against.
	 */
	public $page_versions;

	/**
	 * Setup function to get tests setup.
	 */
	public function setUp() {

		parent::setup();

		$this->page_versions = new \Dep\PageVersions();

	}

	/**
	 * Tests whether or not $page_versions->add_meta_boxes() gets added to add_meta_boxes hook.
	 */
	function test_add_hooks_adds_add_meta_boxes_to_add_meta_boxes_hook() {

		$actual = $this->page_versions->add_hooks();

		$has_add_meta_boxes = \WPHelper::check_hook_for_callback( 'add_meta_boxes', '\Dep\PageVersions', 'add_meta_boxes' );

		$this->assertTrue( $has_add_meta_boxes, "Dep\PageVersions->add_hooks() does not add add_meta_boxes() to 'add_meta_boxes' hook" );

	}

	/**
	 * Tests that $page_versions->add_meta_boxes() adds meta box.
	 */
	function test_add_meta_boxes_adds_meta_box() {

		$actual = $this->page_versions->add_meta_boxes();

		$meta_box_is_added = \WPHelper::check_screen_for_meta_box( 'page', 'revisions-box', '\Dep\PageVersions', 'revisions_meta_box_callback', 'side', 'low' );

		$this->assertTrue( $meta_box_is_added, 'Dep\PageVersions->add_meta_boxes() does not add revisions-box as a meta box' );

	}

}
