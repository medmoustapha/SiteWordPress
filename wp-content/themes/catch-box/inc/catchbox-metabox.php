<?php
/**
 * Catch Box Custom meta box
 *
 * @package Catch Themes
 * @subpackage Catch_Box
 * @since Catch Box 1.0
 */

/**
 * Add Metabox Options
 */
function catchbox_add_custom_box() {
	add_meta_box(
		'siderbar-layout',                          //Unique ID
		esc_html__( 'Catch Box Options', 'catch-box' ), //Title
		'catchbox_sidebar_layout',                  //Callback function
		'page',                                     //show metabox in pages
		'side'
	);
	add_meta_box(
		'siderbar-layout',                          //Unique ID
		esc_html__( 'Catch Box Options', 'catch-box' ), //Title
		'catchbox_sidebar_layout',                  //Callback function
		'post',                                     //show metabox in pages
		'side'
	);
}

add_action( 'add_meta_boxes', 'catchbox_add_custom_box' );

/**
 * @renders metabox to for sidebar layout
 */
function catchbox_sidebar_layout() {
	global $post;

	$sidebar_layout = array(
		'default-sidebar' => array(
			'id'    => 'catchbox-sidebarlayout',
			'value' => 'default',
			'label' => esc_html__( 'Default', 'catch-box' ),
		),
		'right-sidebar' => array(
			'id'    => 'catchbox-sidebarlayout',
			'value' => 'right-sidebar',
			'label' => esc_html__( 'Right sidebar', 'catch-box' ),
		),
		'left-sidebar' => array(
			'id'    => 'catchbox-sidebarlayout',
			'value' => 'left-sidebar',
			'label' => esc_html__( 'Left sidebar', 'catch-box' ),
		),
		'no-sidebar-one-column' => array(
			'id'    => 'catchbox-sidebarlayout',
			'value' => 'no-sidebar-one-column',
			'label' => esc_html__( 'No Sidebar, One Column', 'catch-box' ),
		),
	);

	// Use nonce for verification
	wp_nonce_field( basename( __FILE__ ), 'custom_meta_box_nonce' );

	// Begin the field table and loop  ?>
	<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="catchbox-sidebarlayout"><?php esc_html_e('Sidebar Layout Options', 'catch-box'); ?></label></p>
	<select class="widefat" name="catchbox-sidebarlayout" id="catchbox-sidebarlayout">
		 <?php
			$meta_value = get_post_meta( $post->ID, 'catchbox-sidebarlayout', true );

			if ( empty( $meta_value ) ){
				$meta_value = 'default';
			}
			
			foreach ( $sidebar_layout as $field =>$label ) {  
			?>
				<option value="<?php echo esc_attr( $label['value'] ); ?>" <?php selected( $meta_value, $label['value'] ); ?>><?php echo esc_html( $label['label'] ); ?></option>
			<?php
			} // end foreach
		?>
	</select>
<?php
}


/**
 * save the custom metabox data
 * @hooked to save_post hook
 */
function catchbox_save_custom_meta( $post_id ) {
	global $post;

   // Verify the nonce before proceeding.
	if ( !isset( $_POST[ 'custom_meta_box_nonce' ] ) || !wp_verify_nonce( $_POST[ 'custom_meta_box_nonce' ], basename( __FILE__ ) ) )
		return;

	// Stop WP from clearing custom fields on autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)
		return;

	if ('page' == $_POST['post_type']) {
		if (!current_user_can( 'edit_page', $post_id ) )
			return $post_id;
	} elseif (!current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
	}

	if ( ! update_post_meta ( $post_id, 'catchbox-sidebarlayout', sanitize_key( $_POST['catchbox-sidebarlayout'] ) ) ) {
		add_post_meta( $post_id, 'catchbox-sidebarlayout', sanitize_key( $_POST['catchbox-sidebarlayout'] ), true );
	}
}
add_action('save_post', 'catchbox_save_custom_meta');
