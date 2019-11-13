<?php

class Segment_Identify {

	function render_segment_identify() {

		$settings = Segment_Analytics_WordPress::get_settings();
		if ( ! $settings['js_api_key'] ) {
			return;
		}
		$current_user   = wp_get_current_user();
		$current_post   = get_post();
		$trackable_user = Segment_Analytics_WordPress::check_trackable_user( $current_user );
		$trackable_post = Segment_Analytics_WordPress::check_trackable_post( $current_post );
		if ( $trackable_user === false || $trackable_post === false ) {
			//not trackable or not logged in
			return;
		}
		$traits  = Segment_Analytics_WordPress::get_current_user_traits( $current_user );
		$user_id = $traits['userId'];
		if ( $user_id !== 0 && $user_id !== null ) { // only continue if there's a user id
			// get user id format		
			if ( $settings['userid_is_email'] === "yes" ) {
				$user    = get_user_by( 'id', $user_id );
				$user_id = $user->user_email;
			}
			?>
            <script type="text/javascript">
                analytics.identify( <?php echo '"' . Segment_Analytics_WordPress::esc_js_deep( $user_id ) . '"' ?><?php if ( ! empty( $traits ) ) {
					echo ', ' . json_encode( Segment_Analytics_WordPress::esc_js_deep( $traits ) );
				} else {
					echo ', {}';
				} ?><?php if ( ! empty( $options ) ) {
					echo ', ' . json_encode( Segment_Analytics_WordPress::esc_js_deep( $options ) );
				} ?>);
            </script>
			<?php
			if ( $settings['use_alias'] === "yes" ) {
				?>
                <script type="text/javascript">
                    analytics.alias("<?php echo Segment_Analytics_WordPress::esc_js_deep( $user_id ); ?>");
                </script><?php
			}
		}
	}
}

/**
 * Filters the .identify() object and adds extra user traits
 *
 * @return array Modified array of $traits
 */
function add_user_traits( $traits, $user ) {
	$settings = Segment_Analytics_WordPress::get_settings();
	if ( $traits['userId'] === 0 ) {
		$traits['userId'] = $user->ID;
	}
	if ( $traits['userId'] == 0 || $traits['userId'] == null || $traits['userId'] == null ) {
		//try to get userid from event cookies
		//
		if ( Segment_Cookie::get_cookie( 'signed_up' ) !== "" ) {
			$user_id          = Segment_Cookie::get_cookie( 'signed_up' );
			$user_id          = Segment_Encrypt::encrypt_decrypt( $user_id, 'd' );
			$user_id          = base64_decode( $user_id );
			$user_id          = json_decode( $user_id );
			$current_user     = get_user_by( 'id', $user_id );
			$traits['userId'] = $current_user->ID;
		}
		if ( Segment_Cookie::get_cookie( 'order_processing' ) && $traits['userId'] === null ) {
			$properties  = Segment_Cookie::get_cookie( 'order_processing' );
			$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
			$properties  = base64_decode( $properties );
			$properties  = json_decode( $properties );
			$properties  = json_decode( $properties, true );
			$order_id    = $properties['order_id'];
			$order_id    = $properties['order_id'];
			$user_id = Segment_Analytics_WordPress::get_user_id_from_order($order_id );
			$track['userId'] = $user_id;
		}
		if ( Segment_Cookie::get_cookie( 'order_pending' ) && $traits['userId'] === null ) {
			$properties  = Segment_Cookie::get_cookie( 'order_pending' );
			$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
			$properties  = base64_decode( $properties );
			$properties  = json_decode( $properties );
			$properties  = json_decode( $properties, true );
			$order_id    = $properties['order_id'];
			$user_id = Segment_Analytics_WordPress::get_user_id_from_order($order_id );
			$track['userId'] = $user_id;
		}
		if ( Segment_Cookie::get_cookie( 'order_completed' ) && $traits['userId'] === null ) {
			$properties  = Segment_Cookie::get_cookie( 'order_pending' );
			$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
			$properties  = base64_decode( $properties );
			$properties  = json_decode( $properties );
			$properties  = json_decode( $properties, true );
			$order_id    = $properties['order_id'];
			$user_id = Segment_Analytics_WordPress::get_user_id_from_order($order_id );
			$track['userId'] = $user_id;
		}
		if ( Segment_Cookie::get_cookie( 'order_paid' ) && $traits['userId'] === null ) {
			$properties  = Segment_Cookie::get_cookie( 'order_paid' );
			$properties  = Segment_Encrypt::encrypt_decrypt( $properties, 'd' );
			$properties  = base64_decode( $properties );
			$properties  = json_decode( $properties );
			$properties  = json_decode( $properties, true );
			$order_id    = $properties['order_id'];
			$order_id    = $properties['order_id'];
			$user_id = Segment_Analytics_WordPress::get_user_id_from_order($order_id );
			$track['userId'] = $user_id;
		}
		if ( Segment_Cookie::get_cookie( 'order_cancelled' ) && $traits['userId'] === null ) {
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

	//woocommerce traits
	if ( $settings['track_woocommerce_fieldset']['track_woocommerce'] === 'yes' && $settings['track_woocommerce_meta_fieldset']['track_woocommerce_meta'] === "yes" && $traits['userId'] !== null ) {
		$meta_keys = array(
			"billing_address_1",
			"billing_address_2",
			"billing_city",
			"billing_company",
			"billing_email",
			"billing_first_name",
			"billing_last_name",
			"billing_phone",
			"billing_postcode",
			"billing_state",
			"paying_customer",
			"shipping_address_1",
			"shipping_address_2",
			"shipping_city",
			"shipping_company",
			"shipping_country",
			"shipping_first_name",
			"shipping_last_name",
			"shipping_method",
			"shipping_postcode",
			"shipping_state",
			"wc_last_active",
		);
		// Get all user meta data for $user_id
		foreach ( $meta_keys as $key ) {
			$value          = get_user_meta( $traits['userId'], $key, true );
			$traits[ $key ] = $value;
		}
		// Filter out empty meta data
		$traits = array_filter( $traits );
	}

	//this is for the custom meta key functionality
	if ( $settings['custom_user_traits'] !== null && $traits['userId'] !== null ) {
		$custom_traits = $settings['custom_user_traits'];
		foreach ( $custom_traits as $custom_trait ) {
			$trait_label            = $custom_trait["custom_user_traits_label"];
			$trait_key              = $custom_trait["custom_user_traits_key"];
			$trait_value            = get_user_meta( $user->ID, $trait_key, true );
			$traits[ $trait_label ] = $trait_value;
		}
	}

	return array_filter($traits);
}

add_filter( 'filter_user_traits', 'add_user_traits', 10, 2 );