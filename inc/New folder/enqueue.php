<?php
function enqueue() {	
	$css_files = array();
	$js_files = array();
	
		$css_files[] = 'bootstrap.min';
		$css_files[] = 'whatsapp_simulation';
		$css_files[] = 'robochat';

		$js_files[]	 = array('popper.min');
		$js_files[]	 = array('bootstrap.min');
		
		if(is_sub_page()){
			$js_files[]	 = array('whatsapp_simulation');
		}
		if(get_current_screen()->id ==  'client'){
			$js_files[]	 = array('client_screen');
		}
		
		$js_files[]	 = array('robochat');

	foreach ((array)$css_files as $css) {
		$src = css.$css.'.css';
		$handle = @end(explode('/',$css));
		wp_enqueue_style($handle,$src, array(), 1 );
	};

	foreach ((array)$js_files as $js) {
		$src = js.$js[0].'.js';
		$handle = @end(explode('/',$js[0]));
		if(isset($js[1])){
			$in_footer =  $js[1] == FALSE ? TRUE : FALSE ; 
			$deps = is_array($js['deps']) ? $js['deps'] :  array();	
		}else {
			$in_footer =  TRUE ; 
			$deps = array();	
		}

		wp_enqueue_script($handle,$src,$deps,1,$in_footer);
	}
}