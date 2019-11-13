<?php
//
//  Server side events
class Segment_HTTP_Track {

	function render_segment_http_track( ...$args ) {
		$args     = func_get_args();
		$action   = current_action();
		$settings = Segment_Analytics_WordPress::get_settings();
		if ( ! $settings['http_api_key'] ) {
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
				//if current user is trackable
				//
				$event_name = Segment_Analytics_WordPress::get_event_name( $action );
				if ( $event_name === '' || $event_name === null ) {
					return;
				}
				if ( $current_user->ID === 0 || $current_user->ID === null ) {
					$event_user_id = Segment_Analytics_WordPress::get_event_user_id( $event_name, $args );
					if ( $event_user_id === 0 && $event_user_id === null ) {
						return; // if there is no uid then there's no server side event
					} else {
						$user_id = $event_user_id;
					}
				}
				$event_properties = Segment_Analytics_WordPress::get_event_properties( $event_name, $user_id, $args );
				$event_identifier = '';
				if ( $settings['event_identifier_fieldset']['use_event_identifier'] === 'yes' ) {
					$event_identifier = ' - Server Side';
					if ( $settings['event_identifier_fieldset']['use_event_identifier_label'] !== "" ) {
						$event_identifier = ' ' . $settings['event_identifier_fieldset']['use_event_identifier_label'];
					}
				}
				$time         = current_time( 'timestamp' );
				$http_api_key = $settings['http_api_key'];
				$http_api_key = $http_api_key . ':';
				$http_api_key = base64_encode( $http_api_key );

				if ( $event_name === '' || $event_name === null ) {
					return;
				}
				$json = '{
						  "context": {
							"library": {
								 "name": "segment-wordpress",
								 "version": "in8.io"
							  }
						 },
						"userId": "' . $user_id . '",
						"type": "track",
						"event": "' . $event_name . $event_identifier . '",
						"properties": ' . $event_properties . ',
						"timestamp": "' . $time . '"
						}';
				$json = json_decode( $json, true ); //make it json
				$url  = 'https://api.segment.io/v1/track';
				$data = wp_remote_post( $url, array(
					'headers' => array(
						'Content-Type'  => 'application/json',
						'Authorization' => 'Basic' . ' ' . $http_api_key,
					),
					'body'    => json_encode( $json ), // make it json again, body itself to be an array too
					'method'  => 'POST'
				) );

				return;
			}

			return;
		}

		return;
	}

	function render_segment_ecommerce_http_track( ...$args ) {
		$args     = func_get_args();
		$action   = current_action();
		$settings = Segment_Analytics_WordPress::get_settings();

		if ( ! $settings['http_api_key'] || $action == 'Commented' ) {
			return;
		} else {
			$current_user        = wp_get_current_user();
			$user_id             = $current_user->ID;
			$event_user_id       = $user_id;
			$current_post        = get_post();
			$trackable_user      = Segment_Analytics_WordPress::check_trackable_user( $current_user );
			$trackable_post_type = Segment_Analytics_WordPress::check_trackable_post( $current_post );
			if ( $trackable_user === false || $trackable_post_type === false ) {
				//not trackable
				return;
			} else {
				//if current user is trackable
				//
				$event_name = Segment_Analytics_WordPress::get_event_name( $action );


				if ( $event_name === '' || $event_name === null ) { //make sure we have an event name
					return;
				}
				if ( $current_user->ID === 0 ||
				     $current_user->ID === null ||
				     in_array( $event_name, array(
					     'Order Completed',
					     'Order Pending',
					     'Order Failed',
					     'Order On-Hold',
					     'Order Processing',
					     'Order Paid',
					     'Order Refunded',
					     'Order Cancelled'
				     ) ) ||
				     in_array( $event_name, array(
					     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_completed"],
					     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_pending"],
					     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_failed"],
					     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_on_hold"],
					     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_processing"],
					     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_paid"],
					     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_refunded"],
					     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_cancelled"],
					     $settings["woocommerce_events_labels"]["woocommerce_events_custom_labels"]["woocommerce_event_order_completed"]
				     ) )
				) {
					$event_user_id = Segment_Analytics_WordPress::get_event_user_id( $event_name, $args );
					if ( $event_user_id === 0 && $event_user_id === null ) {
						return; // if there is no uid then there's no server side event
					} else {
						$user_id = $event_user_id;
					}
				}
				$event_properties = Segment_Analytics_WordPress::get_ecommerce_event_properties( $event_name, $user_id, $args );
				$event_identifier = '';
				if ( $settings['event_identifier_fieldset']['use_event_identifier'] === 'yes' ) {
					$event_identifier = ' - Server Side';
					if ( $settings['event_identifier_fieldset']['use_event_identifier_label'] !== "" ) {
						$event_identifier = ' ' . $settings['event_identifier_fieldset']['use_event_identifier_label'];
					}
				}
				$time         = current_time( 'timestamp' );
				$http_api_key = $settings['http_api_key'];
				$http_api_key = $http_api_key . ':';
				$http_api_key = base64_encode( $http_api_key );
				if ( $event_name == 'Commented' ) {//woocommerce creates a comment by admin whenever people complete an order
					return;
				}
				$json = '{
						  "context": {
							"library": {
								 "name": "segment-wordpress",
								 "version": "in8.io"
							  }
						 },
						"userId": "' . $user_id . '",
						"type": "track",
						"event": "' . $event_name . $event_identifier . '",
						"properties": ' . $event_properties . ',
						"timestamp": "' . $time . '"
						}';
				$json = json_decode( $json, true ); //make it json
				$url  = 'https://api.segment.io/v1/track';
				$data = wp_remote_post( $url, array(
					'headers' => array(
						'Content-Type'  => 'application/json',
						'Authorization' => 'Basic' . ' ' . $http_api_key,
					),
					'body'    => json_encode( $json ), // make it json again, body itself to be an array too
					'method'  => 'POST'
				) );

				return;
			}

			return;
		}

		return;
	}
}