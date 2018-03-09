<?php
/**
 * Displays a list of forums.
 *
 * @var WP_Post $post the pos tobject passed in from controller.
 *
 * @package DavidEugenePratt
 */

$revisions         = wp_get_post_revisions();
$revisions_data    = json_decode( get_post_meta( $post->ID, 'dep_revisions_data', true ), true );
$revisions_counter = count( $revisions );
wp_nonce_field( 'dep_revisions_box', 'dep_revisions_box_nonce' );

?>


<table>

	<tr>

		<th>#</th>
		<th>Date/Time</th>
		<th></th>

	</tr>

<?php foreach ( $revisions as $key => $revision ) { ?>

	<?php

		$month  = ( isset( $revisions_data[ $revision->ID ] ) ) ? $revisions_data[ $revision->ID ]['month'] : '';
		$day    = ( isset( $revisions_data[ $revision->ID ] ) ) ? $revisions_data[ $revision->ID ]['day'] : '';
		$year   = ( isset( $revisions_data[ $revision->ID ] ) ) ? $revisions_data[ $revision->ID ]['year'] : '';
		$hour   = ( isset( $revisions_data[ $revision->ID ] ) ) ? $revisions_data[ $revision->ID ]['hour'] : '';
		$minute = ( isset( $revisions_data[ $revision->ID ] ) ) ? $revisions_data[ $revision->ID ]['minute'] : '';


	?>

	<!--

	<tr>

		<td colspan="2"><?php var_dump( $revision ); ?></td>

	</tr>

	-->

	<tr>

		<td>

			<strong>

				<?php echo $revisions_counter; ?>

				<input type="hidden" name="dep_revisions_id[]" value="<?php echo $revision->ID; ?>" >

			</strong>

		</td>

		<td>

			<select class="dep-revisions-control month" name="dep_revisions_publish_month[]" type="text">
				<?php

				for ( $m = 1; $m <= 12; $m++ ) {

					$selected = ( $month == $m ) ? 'selected' : '';

					echo ' <option value="' . $m . '" ' . $selected . '>' . date( 'M', mktime( 0, 0, 0, $m ) ) . '</option>';

				}

				?>
			</select>

			<input type="text" class="dep-revisions-control day" name="dep_revisions_publish_day[]" value="<?php echo $day; ?>" placeholder="XX" >,

			<input type="text" class="dep-revisions-control year" name="dep_revisions_publish_year[]"  value="<?php echo $year; ?>" placeholder="XXXX" > @

			<input type="text" class="dep-revisions-control hour" name="dep_revisions_publish_hour[]"  value="<?php echo $hour; ?>" placeholder="XX" > :

			<input type="text" class="dep-revisions-control minute" name="dep_revisions_publish_minute[]"  value="<?php echo $minute; ?>" placeholder="XX"  >

		</td>


		<td>

			<?php echo '<a href="revision.php?revision=' . $revision->ID . '" class="dep-revisions-preview" ><span class="dashicons dashicons-visibility"></span></a><br>'; ?>

		</td>


	</tr>

	<?php $revisions_counter--; ?>

<?php } ?>

</table>

<style>

	.misc-pub-revisions {

		display:none;

	}

	.misc-pub-curtime {

		display:none;

	}

	.dep-revisions-control {

	font-size:10px;

	}

	.dep-revisions-control.month {

		width:45px;
		height:20px;

	}

	.dep-revisions-control.day {

		width:25px;

	}

	.dep-revisions-control.year {

		width:40px;

	}

	.dep-revisions-control.hour {

		width:25px;

	}

	.dep-revisions-control.minute {

		width:25px;

	}

	.dep-revisions-preview {

		text-decoration:none;

	}

</style>
