<?php
/**
 * Class Dep\PageVersions\ControllerTest
 *
 * @package Dep\PageVersions
 */

namespace Dep\PageVersions;
require_once( __DIR__ . '/../../class-wphelper.php' );

/**
 * Class ControllerTest
 *
 * @package Dep\PageVersions
 */
class ControllerTest extends \WP_UnitTestCase {

	/**
	 * The post id of the mock post created to test against.
	 *
	 * @var string $mock_post_id the id of the mock post created in setUp()
	 */
	protected $mock_post_id;

	/**
	 * An instance of Dep\PageVersions\Controller to test against.
	 *
	 * @var Controller $dep_page_versions_controller an instance to test against.
	 */
	protected $dep_page_versions_controller;

	/**
	 *  The id of the mock subscriber crete in setup() to test against.
	 *
	 * @var string $subscriber_id id of mock subscriber.
	 */
	protected $subscriber_id;

	/**
	 * Id of mock administrator created in setUp() to test against.
	 *
	 * @var string $administrator_id id of mock administrator.
	 */
	protected $administrator_id;

	/**
	 * Revision data created in setUp()
	 *
	 * @var array $revisions_data revision data to test against.
	 */
	protected $revisions_data;

	/**
	 * Setup method to ge everything setup before each test
	 */
	public function setUp() {

		parent::setup();

		$this->mock_post_id = $this->factory->post->create(
			array(
				'post_title'   => 'Test Rider',
				'post_type'    => 'page',
				'post_content' => 'original',
			)
		);

		$this->subscriber_id = $this->factory->user->create(
			array(
				'user_login' => 'test',
				'role'       => 'subscriber',
			)
		);

		$this->administrator_id = $this->factory->user->create(
			array(
				'user_login' => 'admin-test',
				'role'       => 'administrator',
			)
		);

		wp_set_current_user( $this->administrator_id );

		$this->dep_page_versions_controller = new \Dep\PageVersions\Controller();

		$_POST['dep_revisions_box_nonce'] = wp_create_nonce( 'dep_revisions_box' );

		$_POST['dep_revisions_id'] = array( '1', '2', '3' );

		$_POST['dep_revisions_publish_month'] = array( '1', '2', '3' );

		$_POST['dep_revisions_publish_day'] = array( '1', '2', '3' );

		$_POST['dep_revisions_publish_year'] = array( '2017', '2017', '2017' );

		$_POST['dep_revisions_publish_hour'] = array( '1', '2', '3' );

		$_POST['dep_revisions_publish_minute'] = array( '1', '2', '3' );

		$this->revisions_data = array();

		foreach ( $_POST['dep_revisions_id'] as $key => $id ) {

			$this->revisions_data[ $id ] = array(

				'month'  => $_POST['dep_revisions_publish_month'][ $key ],
				'day'    => $_POST['dep_revisions_publish_day'][ $key ],
				'year'   => $_POST['dep_revisions_publish_year'][ $key ],
				'hour'   => $_POST['dep_revisions_publish_hour'][ $key ],
				'minute' => $_POST['dep_revisions_publish_minute'][ $key ],

			);

		}

	}

	/**
	 * Tests that $dep_page_versions_controller->save_post() is added to save_post hook.
	 */
	function test_add_hooks_adds_save_post_to_save_post_hook() {

		$actual = $this->dep_page_versions_controller->add_hooks();

		$has_save_post = \WPHelper::check_hook_for_callback( 'save_post', 'Dep\PageVersions\Controller', 'save_post' );

		$this->assertTrue( $has_save_post, "Dep\PageVersions\Controller->add_hooks() does not add save_post() to 'save_post' hook" );

	}

	/**
	 * Tests that $dep_page_versions_controller->save_post() won't save if nonce is not present.
	 */
	function test_save_post_wont_save_if_nonce_not_present() {

		unset( $_POST['dep_revisions_box_nonce'] );

		$actual = $this->dep_page_versions_controller->save_post( $this->mock_post_id );

		$this->assertTrue( $actual === $this->mock_post_id, "Dep\PageVersions\Controller->save_post( $this->mock_post_id ) does not fail if nonce field is not present." );

	}

	/**
	 * Tests that $dep_page_versions_controller->save_post() won't save if nonce is incorrect.
	 */
	function test_save_post_wont_save_if_nonce_not_correct_nonce() {

		$_POST['dep_revisions_box_nonce'] = wp_create_nonce( 'wrong-nonce' );

		$actual = $this->dep_page_versions_controller->save_post( $this->mock_post_id );

		$this->assertTrue( $actual === $this->mock_post_id, "Dep\PageVersions\Controller->save_post( $this->mock_post_id ) does not fail if nonce field is wrong nonce." );

		$_POST['dep_revisions_box_nonce'] = wp_create_nonce( 'dep_revisions_box' );

	}

	/**
	 * Tests that $dep_page_versions_controller->save_post() won't save if user is subscriber.
	 */
	function test_save_post_wont_save_if_user_is_subscriber() {

		wp_set_current_user( $this->subscriber_id );

		$_POST['dep_revisions_box_nonce'] = wp_create_nonce( 'dep_revisions_box' );

		$actual = $this->dep_page_versions_controller->save_post( $this->mock_post_id );

		$this->assertTrue( $actual === $this->mock_post_id, "Dep\PageVersions\Controller->save_post( $this->mock_post_id ) allows users without proper permissions to edit post." );

		wp_set_current_user( $this->administrator_id );

	}

	/**
	 * Tests that $dep_page_versions_controller->save_post() saves dep_revision_data to meta.
	 */
	function test_save_post_saves_dep_revision_data_meta() {

		$expected = json_encode( $this->revisions_data );

		$this->dep_page_versions_controller->save_post( $this->mock_post_id );

		$actual = get_post_meta( $this->mock_post_id, 'dep_revisions_data', true );

		$this->assertEquals( $expected, $actual, "Dep\PageVersions\Controller->save_post( $this->mock_post_id ) does not save the dep_revisions_data meta." );

	}

	/**
	 * Tests that $dep_page_versions_controller->add_hooks() adds $dep_page_versions_controller->get_Revision to content_filter hook.
	 */
	function test_add_hooks_adds_get_revision_to_the_the_content_filter() {

		$actual = $this->dep_page_versions_controller->add_hooks();

		$has_get_revision = \WPHelper::check_hook_for_callback( 'the_content', 'Dep\PageVersions\Controller', 'get_revision' );

		$this->assertTrue( $has_get_revision, "Dep\PageVersions\Controller->add_hooks() does not add get_revision() to 'the_post' hook" );

	}

	/**
	 * Tests that $dep_page_versions_controller->get_revisions() gets posts revisions.
	 */
	function test_get_revisions_gets_the_posts_revisions() {

		$post = get_post( $this->mock_post_id );

		$post->post_content = 'update 1';

		wp_update_post( $post );

		$post->post_content = 'update 2';

		wp_update_post( $post );

		$post->post_content = 'update 3';

		wp_update_post( $post );

		$revisions = wp_get_post_revisions( $this->mock_post_id );

		$revisions_data = array();

		$last_month = date( 'm', strtotime( 'last day of -1 month' ) );
		$this_year  = date( 'Y', strtotime( 'last day of this month' ) );
		$next_month = date( 'm', strtotime( 'last day of +1 month' ) );

		$revisions_counter = 0;

		$first_revision = false;

		foreach ( $revisions as $key => $revision ) {

			$month = ( $revisions_counter >= 1 ) ? $last_month : $next_month;

			if ( 1 == $revisions_counter ) {

				$second_revision = $revision;

			}

			$revisions_data[ $revision->ID ] = array(

				'month'  => $month,
				'day'    => $revisions_counter + 1 . '',
				'year'   => $this_year,
				'hour'   => '12',
				'minute' => '30',

			);

			$revisions_counter++;

		}

		update_post_meta( $this->mock_post_id, 'dep_revisions_data', json_encode( $revisions_data ) );

		$args = array(
			'p'         => $this->mock_post_id,
			'post_type' => 'page',
		);

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {

			while ( $query->have_posts() ) {

				$query->the_post();

				$actual = $this->dep_page_versions_controller->get_revision( $post->post_content );

			}
		}

		$this->assertEquals( $second_revision->post_content, $actual );

	}

}
