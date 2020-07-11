<?php




define( 'Carbon_Fields_Plugin\PLUGIN_FILE', __FILE__ );

define( 'Carbon_Fields_Plugin\RELATIVE_PLUGIN_FILE', basename( dirname( \Carbon_Fields_Plugin\PLUGIN_FILE ) ) . '/' . basename( \Carbon_Fields_Plugin\PLUGIN_FILE ) );

	if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
		require( __DIR__ . '/vendor/autoload.php' );
	}
	\Carbon_Fields\Carbon_Fields::boot();

	if ( is_admin() ) {
		\Carbon_Fields_Plugin\Libraries\Plugin_Update_Warning\Plugin_Update_Warning::boot();
	}
