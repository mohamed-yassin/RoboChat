<?php


if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
 

function current_sql($process =  '*'){
	$subs =  subs_option_field_array();
	$table = isset($_REQUEST['tab']) && isset($subs[$_REQUEST['tab']]) ? $subs[$_REQUEST['tab']]['id'] :  array_values($subs)[0]['id'] ; 
	$table =  'robo_msgs_'.$table;
	$per_page =  current_per_page();

	global $wpdb;
	$sql = "SELECT $process  FROM  $table ";
	$fields =  general_report_fields();
	$where = '';
	foreach ($fields as $key => $field) {
		$name 	= esc_sql($field['name']);
		$filter = $name.'_filter';
		$from	= $name.'_from';
		$to		= $name.'_to';
	
		if($field['type'] == 'text' && isset($_REQUEST[$filter]) && $_REQUEST[$filter] && isset($_REQUEST[$name])  && $_REQUEST[$name] != ''){
			$filter = esc_sql($_REQUEST[$filter]);
			if($filter ==  'like'){
				$value 	= "'%".esc_sql($_REQUEST[$name])."%'";
			}elseif ($filter ==  '=' || $filter ==  '<' || $filter ==  '>') {
				$value 	= "'".esc_sql($_REQUEST[$name])."'";
			}
			$where 	= $where == '' ?  " WHERE " :  $where." AND ";
			$where .= " $name $filter $value ";
		}elseif ($field['type'] == 'date' && ((isset($_REQUEST[$from]) && $_REQUEST[$from] != '' ) && (isset($_REQUEST[$to]) && $_REQUEST[$to]) ) ) {
			$where 	= $where == '' ?  " WHERE " :  $where." AND ";
			$where .= ' ('.$field['name'].' >= "'.$_REQUEST[$from].'" AND '.$field['name'].'<  "'.$_REQUEST[$to].'"  ) ';
		}
	}
	return  $sql.$where ; 
}
function current_per_page(){
	return isset($_REQUEST['per_page']) ?  $_REQUEST['per_page'] :  5 ;  
}
function general_report_fields(){
	return array(
		array(
			'label' => __( 'Number', 'robo' ),
			'name'	=> 'mobile_number',
			'sort' => true,
			'type' => 'text',
		),
		array(
			'label' => __( 'Body', 'robo' ),
			'name'	=> 'msg_body',
			'sort' => true,
			'type' => 'text',
		),
		array(
			'label' => __( 'Created at', 'robo' ),
			'name'	=> 'created_at',
			'sort' => true,
			'type' => 'date',
		),
		array(
			'label' => __( 'Updated at', 'robo' ),
			'name'	=> 'updated_at',
			'sort' => true,
			'type' => 'date',
		)
	); 
}


class robo_general_msgs_report extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Customer', 'robo' ), //singular name of the listed records
			'plural'   => __( 'Customers', 'robo' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}


	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_rows( $per_page = 5, $page_number = 1 ) {
		global $wpdb;
		$per_page =  current_per_page();
		$sql =  current_sql();
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}	
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		$_REQUEST['total'] =  count($result);
		return $result;
	}


	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_customer( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}customers",
			[ 'ID' => $id ],
			[ '%d' ]
		);
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;
		$sql = current_sql("COUNT(*)");
		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No Messages.', 'robo' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'mobile_number':
			case 'msg_body':
			case 'msg_type':
			case 'msg_caption':
			case 'is_sent':
			case 'source':
			case 'status':
			case 'user':
			case 'created_at':
			case 'updated_at':
                if($column_name == 'source'){
                    if($item[ $column_name ] ==  1){
                        return __('Bulk Message','robo');
                    }elseif ($item[ $column_name ] ==  2) {
                        return __('Chat Bot','robo');
                    }elseif ($item[ $column_name ] ==  3) {
                        return __('Customer Services','robo');
                    }
                }elseif ($column_name == 'is_sent') {
                    if ($item[ $column_name ] ==  1) {
                        return __('&#x2714;','robo');
                    }else {
                        return __('X','robo');
                    }
                }elseif ($column_name == 'user') {
                    if (isset($item[ $column_name ]) && is_numeric($item[ $column_name ]) && $item[ $column_name ] > 0) {
						return not_null_auther_name($item[ $column_name ]);
                    }else {
                        return __('Undefined User','robo');
                    }
                }
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}
	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'mobile_number' 	=> __( 'Number', 'robo' ),
			'msg_body'    		=> __( 'Body', 'robo' ),
			'msg_type'    		=> __( 'Type', 'robo' ),
			'msg_caption'    	=> __( 'Caption', 'robo' ),
			'is_sent'    		=> __( 'Is Sent', 'robo' ),
			'source'    		=> __( 'Source', 'robo' ),
			'status'    		=> __( 'Status', 'robo' ),
			'user'    			=> __( 'User', 'robo' ),
			'created_at'    	=> __( 'Created at', 'robo' ),
			'updated_at'    	=> __( 'Updated at', 'robo' ),	
		];

		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'mobile_number' => array( 'mobile_number', false ),
			'msg_type' => array( 'msg_type', false ),
			'msg_type' => array( 'msg_type', false ),
			'is_sent' => array( 'is_sent', false ),
			'source' => array( 'source', false ),
			'status' => array( 'status', false ),
			'user' => array( 'user', false ),
			'created_at' => array( 'created_at', false ),
			'updated_at' => array( 'updated_at', false ),
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	/*
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}
*/

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = current_per_page();
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_rows( $per_page, $current_page );
	}

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_delete_customer' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::delete_customer( absint( $_REQUEST['customer'] ) );

		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		                wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}
			

		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_customer( $id );

			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}
	}

}



class SP_Plugin {

	// class instance
	static $instance;

	// customer WP_List_Table object
	public $msgs_list;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
	}


	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function plugin_menu() {
        $hook = add_submenu_page('robo_reports',__('General Reports','robo'), __('General Reports','robo'), 'read','robo_general_reports',[ $this, 'plugin_settings_page' ]);
		add_action( "load-$hook", [ $this, 'screen_option' ] );
	}


	/**
	 * Plugin settings page
	 */
	public function plugin_settings_page() {
		$subs =  subs_option_field_array();
		$count=  is_array($subs)? count($subs) :  0;

		?>
		<div class="wrap">
			<h2><?= __('General Report');?></h2>
            <h2 class="nav-tab-wrapper">
			<?php 
				if($count  >  0){
					$link = current_sub_page_url();
					foreach ($subs as $key => $sub) {
						echo  '<a href="'.$link.'&tab='.$sub['slug'].'" class="nav-tab nav-tab-active">'.$sub['slug'].'</a>';
					}
				}else {
					echo __('No Subscriptions added yet','robo');
				}
			?>
            </h2>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="POST" action='<?= current_sub_page_url('',array('tab', 'per_page')); ?>'>
								<?php								
								$fields =  general_report_fields();
								echo filter_fileds($fields);
								$this->msgs_list->prepare_items();
								$this->msgs_list->display(); ?>
								<input type='submit' value='<?=  __('Search','robo');?>'>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
	<?php
	}

	/**
	 * Screen options
	 */
	public function screen_option() {
		$option = 'per_page';
		$args   = [
			'label'   => 'Customers',
			'default' => 5,
			'option'  => 'customers_per_page'
		];
		add_screen_option( $option, $args );
		$this->msgs_list = new robo_general_msgs_report();
	}


	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}


add_action( 'plugins_loaded', function () {
	SP_Plugin::get_instance();
} );