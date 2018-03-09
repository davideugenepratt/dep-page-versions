<?php
/**
 * Class WP_Helper
 *
 * @package DavidEugenePratt
 * @category Class
 * @author   David Pratt
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.github.org/davideugenepratt
 */

/**
 * Class WP_Helper
 *
 * @author   David Pratt
 */
class WPHelper {

	/**
	 * Locates method of class in specified hooks callbacks.
	 *
	 * @param string $hook the hook to look for.
	 * @param string $class the class type to look for.
	 * @param string $method method name to look for.
	 * @return bool
	 */
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

	/**
	 * Looks for meta box for specific screen.
	 *
	 * @param string $screen the screen that meta box should be looked for in.
	 * @param int    $box_id the box id to be looked for.
	 * @param string $class the class name to look for.
	 * @param string $method the method name to look for.
	 * @param string $context the context to check against.
	 * @param string $priority the priority to verify against.
	 * @return bool
	 */
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

	/**
	 * Looks at routes and verifies that a method is attached to the route.
	 *
	 * @param array  $routes routes to look for.
	 * @param string $callback_type route method type to look for.
	 * @param string $http_method http method to look for.
	 * @param string $class class type to look for.
	 * @param string $method method name to look for.
	 * @return bool
	 */
	public static function check_route_for_method_and_callback( $routes, $callback_type, $http_method, $class, $method ) {

		foreach ( $routes as $route ) {

			if ( isset( $route['methods'][ $http_method ] ) && $route['methods'][ $http_method ] ) {

				return is_a( $route[ $callback_type ][0], $class ) && $route[ $callback_type ][1] === $method;

			}
		}

	}

}
