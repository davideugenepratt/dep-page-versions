<?php

class DepPageVersions {

	public function __construct() {

    	$this->add_hooks();

    	$DepPageVersionsPlugin_Controller = new DepPageVersionsPlugin_Controller();

    }

    public function add_hooks() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

    }

    public function add_meta_boxes() {

		return add_meta_box( 'revisions-box' , __( 'Revisions Schedule', 'dep-page-versions' ), array( $this , 'revisions_meta_box_callback' ) , 'page', 'side', 'low' );

    }

    public function revisions_meta_box_callback( $post ) {

    	include( plugin_dir_path( __FILE__ ) . "../views/admin/revisions-box.php" );

    }

}
