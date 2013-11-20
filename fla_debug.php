<?php

function foldable_array($arr){
	$new_arg = '';
	foreach($arr as $key => $value){
		$new_arg .= '<div>';
		if(gettype($value) == 'array')
			$new_arg .= '<span onClick="toggle_array(this)" class="fla_key">['.$key.']</span><div class="fla_array"> => Array<br>{<br><div class="fla_inner_array">'.foldable_array($value).'</div>}</div>';
		else
			$new_arg .= '<span onClick="toggle_array(this)" class="fla_key">['.$key.']</span><span class="fla_array"> => '.htmlentities($value).'</span>';
		$new_arg .= '</div>';
		$arr = $new_arg;
	}
	return $arr;
}

function fla($arg1, $custom_text = "", $die = FALSE){

	// custom text
		if($custom_text) $custom_text .= '<br>';

	// variable type processing
		$type = gettype($arg1);
		switch ($type){
			case 'boolean': $arg1  = $arg1 ? 'TRUE' : 'FALSE'; 		break;
			case 'string':
				switch ($arg1) {
					case '_funcs':
						$type = 'USER DEFINED FUNCTIONS';
						$arg1 = get_defined_functions();
						$arg1 = $arg1['user'];
						break;
					case '_vars':
						$type = 'USER DEFINED VARIABLES';
						$arg1 = $GLOBALS;
						unset($arg1['GLOBALS'], $arg1['GLOBALS'], $arg1['_POST'], $arg1['_GET'], $arg1['_COOKIE'], $arg1['_FILES'],  $arg1['_ENV'],  $arg1['_REQUEST'],   $arg1['_SERVER']);
						break;

					// specials vars
					case '_post': 		$type = '$_POST'; 		$arg1 = $GLOBALS['_POST'];			break;
					case '_get': 		$type = '$_GET'; 		$arg1 = $GLOBALS['_GET'];			break;
					case '_cookies': 	$type = '$_COOKIE'; 	$arg1 = $GLOBALS['_COOKIE'];		break;
					case '_files':		$type = '$_FILES'; 		$arg1 = $GLOBALS['_FILES'];			break;
					case '_request': 	$type = '$_REQUEST'; 	$arg1 = $GLOBALS['_REQUEST'];		break;
					case '_server': 	$type = '$_SERVER'; 	$arg1 = $GLOBALS['_SERVER'];		break;

					case '_trace':
						$type = 'BACKTRACE';
						$arg1 = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
						unset($arg1[0]); // remove this function from list
						break;

					default: $type .= ' - '.strlen($arg1).' chars'; 	break;
				}
				break;
			case 'array':	 $type .= ' - '.count($arg1). ' elems'; 	break;
			case 'object': 
				$arg1 	= array(	'name'		=> 	get_class($arg1),
									'functions' =>  get_class_methods($arg1),
									'variables' => 	get_object_vars(  $arg1)); 
				break;
			case 'resource': $arg1 = get_resource_type($arg1); 			break;
			default: break;
		}

	// custom array folding
		if(gettype($arg1) != 'array') 	$arg1 = htmlentities(print_r($arg1, TRUE));
		else 							$arg1 = foldable_array($arg1);

	// cs-cart default ajax error reporting
		if(defined('AJAX_REQUEST')){
			fn_set_notification('N', fn_get_lang_var('notice'), $custom_text.'<span style="color: green">('.$type.')</span><pre style="max-height: 550px;overflow: auto;">'.print_r($arg1, TRUE).'</pre>', 'K');
			return;
		}

	// display
		echo "
			<!-- flaviu@cimpan.ro  - DEBUGGER -->
				<div class='fla_dbgr'>
					<div class='fla_debug'>".$custom_text."<span class='fla_descr'style='color: yellow'>(".$type.")</span><pre id='fla_pre'>".$arg1."</pre></div>
					<div class='fla_close' onClick='hide_fla(this)'>D</div>
				</div>
				<script>function hide_fla(e){var el=e.parentNode,notes=null
					for(var i=0; i<el.childNodes.length; i++){if (el.childNodes[i].className=='fla_debug'){notes=el.childNodes[i];break}}
					var style=window.getComputedStyle(notes),disp=style.getPropertyValue('display');notes.style.display=(disp=='block')?'none':'block'}
					
					function toggle_array(e){var el=e.parentNode;var disp_status = el.getElementsByClassName('fla_array')[0].style.display;
						if(disp_status == 'none') 	el.getElementsByClassName('fla_array')[0].style.display = '';
						else 						el.getElementsByClassName('fla_array')[0].style.display = 'none';
					}

				</script>
				<style>
					.fla_close{position:absolute;right:0px;top:0px;font-size:9px;padding:4px;cursor:pointer;background-color:#3C3F42;color:#3C3F42;margin:5px}
					.fla_debug{background-color:#3C3F42;border:1px solid black;padding:10px;color:white;text-align:left;margin-bottom:1px}
					.fla_dbgr{position:relative;min-height:40px;min-width:40px;opacity:0.8;transition:opacity 0.2s ease-in-out}
					.fla_dbgr:hover{opacity:1}.fla_close:hover{background-color:red;color:white}
					.fla_dbgr pre{white-space:pre-wrap;white-space:-moz-pre-wrap;white-space:-pre-wrap;white-space:-o-pre-wrap;word-wrap:break-word;font-size:13px!important;font-family:'Courier New',Courier,monospace!important}
					.fla_debug .fla_descr{font-family:'Courier New',Courier,monospace!important;font-weight:bold}
					.fla_debug .fla_key{cursor: pointer}
					.fla_debug div.fla_array{margin-bottom: 7px;margin-left: 30px;margin-top: -14px;}
					.fla_debug div.fla_inner_array{margin-left: 30px;}</style>
			<!-- flaviu@cimpan.ro   -->

		";

	// die if necessary
		if($die) exit;
}
?>