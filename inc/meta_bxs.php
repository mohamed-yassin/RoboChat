<?php 

class phone_Meta_Box {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
		add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {

		add_meta_box(
			'phone',
			__( 'Client Info', slug ),
			array( $this, 'render_metabox' ),
			'client',
			'advanced',
			'default'
		);
	}

	public function render_metabox( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'client_nonce_action', 'client_nonce' );

		// Retrieve an existing value from the database.
		$phone = get_post_meta( $post->ID, 'phone', true );
		$first_name = get_post_meta( $post->ID, 'first_name', true );

		// Set default values.
		if( empty( $phone ) ) $phone = '';

		// Form fields.
		echo '<h4>' . __( 'First Name', slug ) . ' </h4>';
		echo '<input type="text" name="first_name" value="' . esc_attr__( $first_name ) . '">';
		echo '<span> ' . __( 'the name of the client which will be used in messaging process', slug ) . '</span>';
		
		echo '<h4>' . __( 'Phone', slug ) . ' </h4>';
		echo '<input type="text" name="phone" value="' . esc_attr__( $phone ) . '">';
	}

	public function save_metabox( $post_id, $post ) {

		// Add nonce for security and authentication.
		$nonce_name   = $_POST['client_nonce'];
		$nonce_action = 'client_nonce_action';

		// Check if a nonce is set.
		if ( ! isset( $nonce_name ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;

		// Check if the user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Check if it's not a revision.
		if ( wp_is_post_revision( $post_id ) )
			return;

		// Sanitize user input. first_name
		//$phone = isset( $_POST[ 'phone' ] ) ? sanitize_text_field( $_POST[ 'phone' ] ) : '';
		
		$phone= pure_phone($_POST[ 'phone' ] );
		$first_name = isset( $_POST[ 'first_name' ] ) ? sanitize_text_field( $_POST[ 'first_name' ] ) : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, 'phone', $phone );
		update_post_meta( $post_id, 'first_name', $first_name );
	}

}

new phone_Meta_Box;