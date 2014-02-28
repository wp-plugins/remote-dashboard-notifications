<?php
/**
 * Get requester user agent
 */
$user_agent = $_SERVER['HTTP_USER_AGENT'];

/**
 * Validate user agent.
 * if the request is not from WordPress (admin) return false
 */
if ( stristr( $user_agent, 'WordPress' ) == false ) {
	// _e( 'Sorry, this page is not publicly accessible.', 'remote-notifications' );
	// exit;
}

/**
 * Check if channel info is available
 */
if( !isset( $_GET['payload'] ) ) {
	_e( 'Unable to find channel information.', 'remote-notifications' );
	exit;
}

/**
 * Decode the payload from base64
 */
$payload = base64_decode( $_GET['payload'] );

/**
 * Check if payload have been decoded
 */
if( !$payload ) {
	_e( 'The payload was not properly encoded in base64.', 'remote-notifications' );
	exit;
}

/**
 * Decode the channel JSON string
 */
$payload = json_decode( $payload );

/**
 * Check if the payload is in a usable JSON format
 */
if( json_last_error() != JSON_ERROR_NONE ) {
	_e( 'The payload is not in a correct JSON format.', 'remote-notifications' );
	exit;
}

/**
 * Check if all variables are provided
 */
if( !isset( $payload->channel ) || !isset( $payload->key ) ) {
	_e( 'All required variable were not provided.', 'remote-notifications' );
	exit;
}

/**
 * Prepare and sanitize the vars. Get the channel ready for verification.
 */
$channel_id	 = intval( $payload->channel );
$channel_key = sanitize_key( $payload->key );
$channel 	 = new WP_Query( array( 'post_type' => 'notification', 'p' => $channel_id, 'post_status' => 'publish' ) );
$channel_pid = $channel->post->ID;
$key 		 = get_option( "_rn_channel_key_$channel_id", false );

/**
 * Check if taxonomy exists
 */
if( !taxonomy_exists( 'rn-channel' ) ) {

	_e( 'No channels.', 'remote-notifications' );
	exit;

}

/**
 * Check if the channel has a key set
 */
if( false === $key ) {

	_e( 'Key hasn\'t been set for this channel.', 'remote-notifications' );
	exit;

} else {

	/**
	 * Check the validity of the key
	 */
	if( $key !== $channel_key ) {
		_e( 'The key you provided for this channel is incorrect.', 'remote-notifications' );
		exit;
	}
}

/**
 * Now we know the channel exists and the request is authenticated
 */
$args = array(
	'post_type' 			 => 'notification',
	'post_status' 			 => 'publish',
	'posts_per_page' 		 => 1,
	'orderby' 				 => 'date',
	'order' 				 => 'DESC',
	'update_post_meta_cache' => false,
	'update_post_term_cache' => false,
	'tax_query' 			 => array(
		array(
			'taxonomy' => 'rn-channel',
			'field'    => 'id',
			'terms'    => $channel_id
		)
	)
);

/**
 * Find the latest notification
 */
$notification = new WP_Query( $args );

if( isset( $notification->post ) ) {

	/* Get settings */
	$settings = get_post_meta( $notification->post->ID, '_rn_settings', true );

	$alert = array(
		'title'   => $notification->post->post_title,
		'message' => htmlentities( $notification->post->post_content ),
		'slug' 	  => $notification->post->post_name,
		'expiry'  => '',
		'type'    => array()
	);

	/* Add settings */
	$alert = array_merge( $alert, $settings );

	/* Check if there are post types limitations */
	$pt = wp_get_post_terms( $notification->post->ID, 'rn-pt' );

	/* Add the supported post types to the response */
	if( is_array( $pt ) && !empty( $pt ) ) {

		foreach( $pt as $type ) {

			array_push( $alert['type'], $type->slug );

		}

	}

	echo json_encode( $alert );

} else {

	echo 'nothing';

}