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
		$phone 		= get_post_meta( $post->ID, 'phone', true );
		$first_name = get_post_meta( $post->ID, 'first_name', true );
		$notes 		= get_post_meta( $post->ID, 'notes', true );
		$jop_title 	= get_post_meta( $post->ID, 'jop_title', true );
		// Set default values.

		// Form fields.
		echo '<div class="">';
			echo '<h4 class="rob-custom-field-title">' . __( 'First Name', slug ) . ' </h4>';
			echo '<span> ' . __( 'the name of the client which will be used in messaging process', slug ) . '</span>';
			echo '<input class="rob-custom-field" type="text" name="first_name" value="' . esc_attr__( $first_name ) . '">';
		echo "</div>";
		echo "<div>";
			echo '<h4 class="rob-custom-field-title">' . __( 'Phone', slug ) . ' </h4>';
			echo '<input class="rob-custom-field" type="text" name="phone" value="' . esc_attr__( $phone ) . '">';
		echo "</div>";

		echo "<div>";
			echo '<h4 class="rob-custom-field-title">' . __( 'Jop Title', slug ) . ' </h4>';
			echo '<input class="rob-custom-field" type="text" name="jop_title" value="' . esc_attr__( $jop_title ) . '">';
		echo "</div>";


		echo "<div>";
			echo '<h4 class="rob-custom-field-title">' . __( 'Notes', slug ) . ' </h4>';
			echo '<textarea class="rob-custom-field" name="notes">'. esc_attr__( $notes ) .'</textarea>';
		echo "</div>";

	}

	public function save_metabox( $post_id, $post ) {

		// Add nonce for security and authentication.
		$nonce_name   = @$_POST['client_nonce'];
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

		
		$phone= pure_phone($_POST[ 'phone' ] );
		$first_name = isset( $_POST[ 'first_name' ] ) ? sanitize_text_field( $_POST[ 'first_name' ] ) : '';
		$notes = isset( $_POST[ 'notes' ] ) ? sanitize_text_field( $_POST[ 'notes' ] ) : '';
		$jop_title = isset( $_POST[ 'jop_title' ] ) ? sanitize_text_field( $_POST[ 'jop_title' ] ) : '';

		
		// Update the meta field in the database.
		update_post_meta( $post_id, 'phone', $phone );
		update_post_meta( $post_id, 'first_name', $first_name );
		update_post_meta( $post_id, 'notes', $notes );
		update_post_meta( $post_id, 'jop_title', $jop_title );
	}

}
new phone_Meta_Box;


// User profiles
function customer_service_client_permissions( $user ) {  
 ?>

	<h3><?php _e("Permission", "robo"); ?></h3>
	
	<table class="form-table">
		<tr>
			<th><label for="show_msgs"><?php _e("Customer Services"); ?></label></th>
			<td>
				<input type="checkbox" name="show_msgs" id="show_msgs"
					value="checked"
					class="regular-text" <?php echo esc_attr( get_the_author_meta( 'show_msgs', $user->ID ) ); ?> ><br />
			</td>
		</tr>
		<tr>
			<th><label for="send_msg"><?php _e("Send Messages"); ?></label></th>
			<td>
				<input type="checkbox" name="send_msg" id="send_msg"
					value="checked"class="regular-text" <?php echo esc_attr( get_the_author_meta( 'send_msg', $user->ID ) ); ?>/><br />
			</td>
		</tr>
	</table>
	<?php }


	function update_permissions( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) ) { 
			return false; 
		}
		update_user_meta( $user_id, 'show_msgs', $_POST['show_msgs'] );
		update_user_meta( $user_id, 'send_msg', $_POST['send_msg'] );
	}


	class template_instructions {

		public function __construct() {
	
			if ( is_admin() ) {
				add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
				add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
			}
	
		}
	
		public function init_metabox() {
	
			add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
	
		}
	
		public function add_metabox() {
			add_meta_box(
				'instructions',
				__( 'الاكواد المتاحة', slug ),
				array( $this, 'render_metabox' ),
				'template',
				'side',
				'default'
			);
		}
	
		public function render_metabox( $post ) {
		?>
			<tr>
				<td>
				{{current_user}} 
				</td>
				<td>
				المستخدم الحالي 
				</td>
				</br>
			</tr>

			<tr>
				<td>
				{{phone}}
				</td>
				<td>
				رقم هاتف العميل 
				</td>
				</br>
			</tr>

			<tr>
				<td>
				{{first_name}}
				</td>
				<td>
				اسم الاول للعميل  
				</td>
				</br>
			</tr>
			<?php 
		}
	}
	new template_instructions;