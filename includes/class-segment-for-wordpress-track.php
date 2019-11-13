<?php
//
//  Render tracking snippet in <footer>
class Segment_Track {

	function render_segment_track() {
		$settings = Segment_Analytics_WordPress::get_settings();
		if ( ! $settings['js_api_key'] ) {
			return;
		} else {
			$current_user        = wp_get_current_user();
			$user_id             = $current_user->ID;
			$current_post        = get_post();
			$trackable_user      = Segment_Analytics_WordPress::check_trackable_user( $current_user );
			$trackable_post_type = Segment_Analytics_WordPress::check_trackable_post( $current_post );
			if ( $trackable_user === false || $trackable_post_type === false ) {
				//not trackable
				return;
			} else {

				$tracks = Segment_Analytics_WordPress::get_current_page_track();
				foreach ( $tracks as $track ) {
					$track = apply_filters( 'filter_track_call', $track, $user_id );
					//you can use this filter to change the track calls
					?>

                    <script type="text/javascript">
                        analytics.track(<?php echo '"' . Segment_Analytics_WordPress::esc_js_deep( $track['event'] ) . '"' ?><?php if ( ! empty( $track['properties'] ) ) {
							echo ', ' . json_encode( Segment_Analytics_WordPress::esc_js_deep( $track['properties'] ) );
						} else {
							echo ', {}';
						} ?><?php if ( ! empty( $track['options'] ) ) {
							echo ', ' . json_encode( Segment_Analytics_WordPress::esc_js_deep( $track['options'] ) );
						} ?>);
                    </script>

					<?php

				}
			}
		}
	}
}



/**
 * Filters the .track() object and adds event properties, using this to append email and userIf if selected as an example of how to use this filter
 *
 * @return array Modified array of $track["properties"]
 */
function add_event_properties( $track, $user_id ) {
/*	$settings = Segment_Analytics_WordPress::get_settings();

	if ( $track['userId'] === 0 || $track['userId'] === null || $track['userId'] === null ) {

		if ( Segment_Cookie::get_cookie( 'signed_up' ) !== "" ) {
			$user_id         = Segment_Cookie::get_cookie( 'signed_up' );
			$user_id      = Segment_Encrypt::encrypt_decrypt( $user_id, 'd' );
			$user_id      = base64_decode( $user_id );
			$user_id      = json_decode( $user_id );
			$current_user    = get_user_by( 'id', $user_id );
			$track['userId'] = $current_user->ID;
		}

		if ( class_exists( 'WooCommerce' ) ) {

		if ( Segment_Cookie::get_cookie( 'order_processing' ) !== "" ) {
			if ( $track['userId'] === 0 || $track['userId'] === null || $track['userId'] === null ) {
				$properties  = Segment_Cookie::get_cookie( 'order_processing' );
				$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( $properties, true );
				$order_id    = $properties['order_id'];
				$user_id = Segment_Analytics_WordPress::get_user_id_from_order($order_id );
				$track['userId'] = $user_id;
			}
		}
		if ( Segment_Cookie::get_cookie( 'order_pending' ) !== "" ) {
			if ( $track['userId'] === 0 || $track['userId'] === null || $track['userId'] === null ) {
				$properties  = Segment_Cookie::get_cookie( 'order_pending' );
				$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( $properties, true );
				$order_id    = $properties['order_id'];
				$user_id = Segment_Analytics_WordPress::get_user_id_from_order($order_id );
				$track['userId'] = $user_id;
			}
		}
		if ( Segment_Cookie::get_cookie( 'order_completed' ) !== "" ) {
			if ( $track['userId'] === 0 || $track['userId'] === null || $track['userId'] === null ) {
				$properties  = Segment_Cookie::get_cookie( 'order_pending' );
				$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( $properties, true );
				$order_id    = $properties['order_id'];
				$user_id = Segment_Analytics_WordPress::get_user_id_from_order($order_id );
				$track['userId'] = $user_id;
			}
		}
		if ( Segment_Cookie::get_cookie( 'order_paid' ) !== "" ) {
			if ( $track['userId'] === 0 || $track['userId'] === null || $track['userId'] === null ) {
				$properties  = Segment_Cookie::get_cookie( 'order_paid' );
				$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( $properties, true );
				$order_id    = $properties['order_id'];
				$user_id = Segment_Analytics_WordPress::get_user_id_from_order($order_id );
				$track['userId'] = $user_id;

			}
		}
		if ( Segment_Cookie::get_cookie( 'order_cancelled' ) !== "" ) {
			if ( $track['userId'] === 0 || $track['userId'] === null || $track['userId'] === null ) {
				$properties  = Segment_Cookie::get_cookie( 'order_cancelled' );
				$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
				$properties  = base64_decode( $properties );
				$properties  = json_decode( $properties );
				$properties  = json_decode( $properties, true );
				$order_id    = $properties['order_id'];
				$user_id = Segment_Analytics_WordPress::get_user_id_from_order($order_id );
				$track['userId'] = $user_id;
			}
		}
	}
	}
	$track['options']                       = array();
	$track['options']['library']            = array();
	$track['options']['library']['name']    = 'analytics-wordpress';
	$track['options']['library']['version'] = 'in8.io';
	$track['properties']['noninteraction']  = true;
	if ( $settings["include_user_ids"] === 'yes' && $user_id !== 0 ) {
		$user                          = get_user_by( 'id', $user_id );
		$track["properties"]["email"]  = $user->user_email;
		$track["properties"]["userId"] = $user->ID;
	}
	$track['properties'] = array_filter( $track['properties'] );*/

	return $track;
}

add_filter( 'filter_track_call', 'add_event_properties', 10, 2 );

