<?php

class WPHelper {

	public static function check_hook_for_callback( $hook, $class, $method ) {

		global $wp_filter;

		if ( isset( $wp_filter[ $hook ] ) ) {

			foreach ( $wp_filter[ $hook ] as $filter ) {

				foreach ( $filter as $callback ) {

					$is_correct_class = is_a( $callback['function'][0], $class );

					if ( $is_correct_class && $method == $callback['function'][1] ) {

						return true;

					}
				}
			}
		}

		return false;

	}

	public static function check_screen_for_meta_box( $screen, $box_id, $class, $method, $context, $priority ) {

		global $wp_meta_boxes;

		$correct_screen = isset( $wp_meta_boxes[ $screen ] );

		if ( ! $correct_screen ) {
			return false; }

		$correct_context = isset( $wp_meta_boxes[ $screen ][ $context ] );

		if ( ! $correct_context ) {
			return false; }

		$correct_priority = isset( $wp_meta_boxes[ $screen ][ $context ][ $priority ] );

		if ( ! $correct_priority ) {
			return false; }

		$correct_box_id = isset( $wp_meta_boxes[ $screen ][ $context ][ $priority ][ $box_id ] );

		if ( ! $correct_box_id ) {
			return false; }

		$correct_class_in_callback = is_a( $wp_meta_boxes[ $screen ][ $context ][ $priority ][ $box_id ]['callback'][0], $class );

		if ( ! $correct_class_in_callback ) {
			return false; }

		$correct_callback_method = $method == $wp_meta_boxes[ $screen ][ $context ][ $priority ][ $box_id ]['callback'][1];

		return $correct_callback_method;

	}

	public static function check_route_for_method_and_callback( $routes, $callback_type, $http_method, $class, $method ) {

		foreach ( $routes as $route ) {

			if ( isset( $route['methods'][ $http_method ] ) && $route['methods'][ $http_method ] ) {

				return is_a( $route[ $callback_type ][0], $class ) && $route[ $callback_type ][1] === $method;

			}
		}

	}

}
