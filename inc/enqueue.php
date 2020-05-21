<?php

function enqueue() {	
	

	if($_GET['process']='show_messages'){

		$css_files[] = 'bootstrap.min';
		$css_files[] = 'fontawesome';
		$css_files[] = 'whatsapp_simulation';


		$js_files[]	 = array('datastore');
		$js_files[]	 = array('date-utils');
		$js_files[]	 = array('whatsapp_simulation');
	}









	foreach ($css_files as $css) {
		$src = css.$css.'.css';
		$handle = end(explode('/',$css));
		wp_enqueue_style($handle,$src, array(), 1 );
	};
	foreach ($js_files as $js) {
		$src = js.$js[0].'.js';
		$handle = end(explode('/',$js[0]));
		$in_footer =  $js[1] == FALSE ? TRUE : FALSE ; 
		$deps = is_array($js['deps']) ? $js['deps'] :  array();

		wp_enqueue_script($handle,$src,$deps,1,$in_footer);
	}
} 

add_action( 'admin_enqueue_scripts', 'enqueue' );