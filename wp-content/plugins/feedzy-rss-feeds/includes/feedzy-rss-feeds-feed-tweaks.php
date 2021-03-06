<?php
/**
 * Useful helper functions for plugin.
 *
 * @link       http://themeisle.com
 * @since      3.0.0
 *
 * @package    feedzy-rss-feeds
 * @subpackage feedzy-rss-feeds/includes
 */

/**
 * Adds the featured image to the website's RSS feed.
 *
 * @param string $content The content of feed.
 *
 * @return string
 */
function feedzy_insert_thumbnail( $content ) {
	$settings = apply_filters( 'feedzy_get_settings', array() );
	if ( isset( $settings['general']['rss-feeds'] ) && 1 === intval( $settings['general']['rss-feeds'] ) ) {
		return $content;
	}

	global $post;
	if ( has_post_thumbnail( $post->ID ) ) {
		$content = '' . get_the_post_thumbnail( $post->ID, 'thumbnail' ) . '' . $content;
	}

	return $content;
}

// Alter the main blog feed to insert the thumbnail image.
add_filter( 'the_excerpt_rss', 'feedzy_insert_thumbnail' );
add_filter( 'the_content_feed', 'feedzy_insert_thumbnail' );

/**
 * Boostrap the plugin view.
 *
 * @param array $options The shortcode attributes.
 */
function feedzy_rss( $options = array() ) {
	$admin = Feedzy_Rss_Feeds::instance()->get_admin();
	return $admin->feedzy_rss( $options );
}

/**
 * The helper method for options wrapper
 *
 * @return Feedzy_Rss_Feeds_Options
 */
function feedzy_options() {
	return Feedzy_Rss_Feeds_Options::instance();
}

/**
 * Check if the user is before 3.0.3 or not.
 *
 * @return bool If the users is before 3.0.3 or after
 */
function feedzy_is_new() {
	return feedzy_options()->get_var( 'is_new' ) === 'yes' && ! feedzy_is_pro();
}

/**
 * Check if the user is pro or not.
 *
 * @return bool If the users is pro or not
 */
function feedzy_is_pro() {
	return defined( 'FEEDZY_PRO_ABSPATH' );
}
