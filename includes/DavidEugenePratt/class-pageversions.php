<?php
/**
 * Class PageVersions
 *
 * @package  DavidEugenePratt
 * @author   David Pratt
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.org/davideugenepratt
 */

namespace DavidEugenePratt;

/**
 * Class PageVersions
 *
 * @package DavidEugenePratt
 */
class PageVersions {

	/**
	 * PageVersions constructor.
	 */
	public function __construct() {

		$this->add_hooks();

		$controller = new \DavidEugenePratt\PageVersions\Controller();

	}

	/**
	 *  Adds the add_meta_boxes method of PageVersions to add_meta_boxes action
	 */
	public function add_hooks() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

	}

	/**
	 *  Adds meta box
	 *
	 * @return mixed
	 */
	public function add_meta_boxes() {

		return add_meta_box( 'revisions-box', __( 'Revisions Schedule', 'dep-page-versions' ), array( $this, 'revisions_meta_box_callback' ), 'page', 'side', 'low' );

	}

	/**
	 * Just includes the template
	 *
	 * @param WP_Post $post post that is passed to the template.
	 */
	public function revisions_meta_box_callback( $post ) {

		include( plugin_dir_path( __FILE__ ) . '../views/admin/revisions-box.php' );

	}

}
