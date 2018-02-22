<?php
namespace Dep\PageVersions;
class Controller {

	public function __construct() {

		$this->add_hooks();

	}

	public function add_hooks() {

		add_action( 'save_post', array( $this, 'save_post' ) );

		add_filter( 'the_content', array( $this, 'get_revision' ) );

	}

	public function save_post( $post_id ) {

		if ( ! isset( $_POST['dep_revisions_box_nonce'] ) ) {

			return $post_id;

		}

		$nonce = $_POST['dep_revisions_box_nonce'];

		if ( ! wp_verify_nonce( $nonce, 'dep_revisions_box' ) ) {

			return $post_id;

		}

		if ( wp_is_post_autosave( $post_id ) ) {

			return $post_id;

		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {

			return $post_id;

		}

		$revisions_data = array();

		foreach ( $_POST['dep_revisions_id'] as $key => $id ) {
			$revisions_data[ $id ] = array(
				'month'  => $_POST['dep_revisions_publish_month'][ $key ],
				'day'    => $_POST['dep_revisions_publish_day'][ $key ],
				'year'   => $_POST['dep_revisions_publish_year'][ $key ],
				'hour'   => $_POST['dep_revisions_publish_hour'][ $key ],
				'minute' => $_POST['dep_revisions_publish_minute'][ $key ],

			);

		}

		$post_saved = update_post_meta( $post_id, 'dep_revisions_data', json_encode( $revisions_data ) );

	}

	public function get_revision( $content ) {

		if ( isset( $_GET['fl_builder'] ) ) {

			return $content;

		} else {

			$revisions = wp_get_post_revisions( get_the_ID() );

			$revisions_data = json_decode( get_post_meta( get_the_ID(), 'dep_revisions_data', true ), true );

			foreach ( $revisions as $key => $revision ) {

				if ( isset( $revisions_data[ $revision->ID ] ) ) {

					$date_data = $revisions_data[ $revision->ID ];

					$date_data['hour'] = ( '' == $date_data['hour'] ) ? '00' : $date_data['hour'];

					$date_data['minute'] = ( '' == $date_data['minute'] ) ? '00' : $date_data['minute'];

					$date_string = $date_data['year'] . '-' . str_pad( $date_data['month'], 2, '0', STR_PAD_LEFT ) . '-' . str_pad( $date_data['day'], 2, '0', STR_PAD_LEFT );

					$time_string = $date_data['hour'] . ':' . $date_data['minute'];

					$date_time_string = $date_string . ' ' . $time_string;

					$date = \DateTime::createFromFormat( 'Y-m-d H:i', $date_time_string );

					$right_now = new \DateTime();

					if ( $date && $date->format( 'Y-m-d H:i' ) === $date_time_string ) {

						if ( $right_now > $date ) {

							return $revision->post_content;

						}
					}
				}
			}

			return $content;

		}

	}

}
