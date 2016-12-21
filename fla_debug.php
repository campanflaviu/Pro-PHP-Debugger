<?php

// Pro PHP Debugger
// 
// flaviu@cimpan.ro 
// http://cimpan.ro
// 2013 - 2014
// 

// config
	define('FLA_VERSION',   '0.6.1');
	define('ENABLED',   	TRUE);

	define('VIEW_FILTER', 	'USER_AGENT'); // or 'DEBUG_IP' or USER_AGENT or FALSE (caution!)
	define('DEBUG_IP',  	'0.0.0.0');
	define('USER_AGENT', 	'custom_user_agent_string');



// include CSS and JS only once
	if(!isset($fla_css)) 		$fla_css 		= FALSE;
	if(!isset($fla_css_lite)) 	$fla_css_lite 	= FALSE;

function fla_sandbox(){ // check if sandbox values are applied
	if(!ENABLED) return FALSE;
	if(VIEW_FILTER && VIEW_FILTER == 'DEBUG_IP' && DEBUG_IP != $_SERVER['REMOTE_ADDR'] && $_SERVER['REMOTE_ADDR'] != '127.0.0.1') return FALSE;
	if(VIEW_FILTER && VIEW_FILTER == 'USER_AGENT' && strpos($_SERVER['HTTP_USER_AGENT'], USER_AGENT) === FALSE) return FALSE;
	return TRUE;
}

function foldable_array($arr){
	$new_arg = '';
	foreach($arr as $key => $value){
		$new_arg .= '<div><span onClick="toggle_array(this)" class="fla_key">['.$key.']</span><span class="fla_array"> => ';
		if(is_array($value) || is_object($value)){
			$fold_arr = foldable_array($value);
			$fold_arr = !is_string($fold_arr) ? implode('', $fold_arr) : $fold_arr;
			$new_arg .= 'Array<br>{<br><div class="fla_inner_array">'.$fold_arr.'</div>}';
		}
		else
			$new_arg .= '<div style="display: inline">'.htmlentities($value).'</div>';
		$new_arg .= '</span></div>';
		$arr = $new_arg;
	}
	return $arr;
}

function foldable_array_lite($arr){
	$new_arg = '';
	foreach($arr as $key => $value){
		$new_arg .= '<div><span class="fla_key">['.$key.']</span><span style="color:#FFF"> => ';
		if(is_array($value) || is_object($value)){
			$fold_arr = foldable_array($value);
			$fold_arr = !is_string($fold_arr) ? implode('', $fold_arr) : $fold_arr;
			$new_arg .= 'Array<br>{<br><div class="fla_inner_array">'.$fold_arr.'</div>}';
		}
		else
			$new_arg .= '<div class="fla_single">'.htmlentities($value).'</div>';
		$new_arg .= '</span></div>';
		$arr = $new_arg;
	}
	return $arr;
}

function get_mem_usage(){
	$mem_usage = memory_get_usage(true);
	$unit  =array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    return @round($mem_usage / pow(1024, ($i = floor(log($mem_usage, 1024)))), 2).' '.$unit[$i];
}

function get_cpu_usage(){
	if(function_exists('sys_getloadavg')){
		$a = sys_getloadavg();
		$c['1 MINUTE'] 		= $a[0];
		$c['5 MINUTES'] 	= $a[1];
		$c['15 MINUTES'] 	= $a[2];
		return $c;
	}
	return 'NA';
}

function fla($arg1, $custom_text = "", $die = FALSE){
	// exit if VIEW_FILTER is not applied or not ENABLED
		if(!fla_sandbox()) return;

	$r = fla_process($arg1, $custom_text, $die);
	fla_print_full($r['arg'], $r['type'], $r['custom']);
	
	// die if necessary
		if($die) exit;
}

function flb($arg1, $custom_text = "", $die = FALSE){
	// exit if VIEW_FILTER is not applied or not ENABLED
		if(!fla_sandbox()) return;

	$r = fla_process($arg1, $custom_text, $die);
	fla_print_lite($r['arg'], $r['type'], $r['custom']);

	// die if necessary
		if($die) exit;
}

function fla_process($arg1, $custom_text, $die){
	// custom text
		if($custom_text === TRUE) {
			$die = TRUE;
			$custom_text = '';
		}
		if($arg1 == '_file');
		elseif($custom_text)  $custom_text .= '<br>';

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

					case '_trace':
						$type = 'BACKTRACE';
						$arg1 = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
						unset($arg1[0]); // remove this function from list
						break;

					case '_server':
						$type = 'SERVER DETAILS';
						$arg1 = array(	'PHP VERSION' 			=> phpversion(),
										'OS NAME' 				=> php_uname('s'),
										'HOST NAME' 			=> php_uname('n'),
										'OS RELEASE' 			=> php_uname('r'),
										'OS VERSION' 			=> php_uname('v'),
										'SERVER ARCHITECTURE' 	=> php_uname('m'),
										'INTERFACE'				=> php_sapi_name(),
										'MEMORY USAGE'			=> get_mem_usage(),
										'CPU LOAD'				=> get_cpu_usage());
						break;

					case '_file':
						$type = 'FILE';
						$arg1 = file_exists($custom_text) ? highlight_file($custom_text, TRUE) : 'File does not exist!';
						break;
					default: 
						$type .= ' - '.strlen($arg1).' chars'; 	
						break;
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


	// detect ajax call
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
			if(defined('AJAX_REQUEST'))	// cs-cart default ajax error reporting
				fn_set_notification('N', fn_get_lang_var('notice'), $custom_text.'<span style="color: green">('.$type.')</span><pre style="max-height: 550px;overflow: auto;">'.print_r($arg1, TRUE).'</pre>', 'K');
			else
				echo json_encode($arg1);
				return;
		}
	return array('arg' => $arg1, 'type' => $type, 'custom' => $custom_text);
}

function fla_print_full($arg1, $type, $custom){
	global $fla_css;

	// custom array folding
		if(!is_array($arg1) && $type != 'FILE') $arg1 = htmlentities(print_r($arg1, TRUE));
		elseif($type == 'FILE');
		else $arg1 = foldable_array($arg1);

	// display
		echo "<!-- DEBUGGER -->
				<div class='fla_dbgr'><div class='fla_debug".(($type != 'FILE') ? "'>".$custom."<span style='color: yellow;font:normal 13px arial,sans-serif!important;'>(".$type.")</span><pre>".$arg1."</pre>" : " white'>".$arg1)."</div><div class='fla_close' onClick='hide_fla(this)'>D</div></div>";
		if(isset($fla_css) && $fla_css) return;
		else{
			echo "<script>function hide_fla(e){var el=e.parentNode,notes=null
					for(var i=0; i<el.childNodes.length; i++)if (el.childNodes[i].className=='fla_debug'||el.childNodes[i].className=='fla_debug white'){notes=el.childNodes[i];break}
					var style=window.getComputedStyle(notes),disp=style.getPropertyValue('display');notes.style.display=(disp=='block')?'none':'block'}
					
					function toggle_array(e){var el=e.parentNode;var disp_status = el.getElementsByClassName('fla_array')[0].style.display;
						if(disp_status == 'none') 	el.getElementsByClassName('fla_array')[0].style.display = '';
						else 						el.getElementsByClassName('fla_array')[0].style.display = 'none';
					}

				</script>
				<style>
					.fla_close{position:absolute;right:0px;top:0px;font-size:9px;padding:4px;cursor:pointer;background-color:#3C3F42;color:#3C3F42;margin:5px}
					.fla_debug{background-color:#3C3F42;border:1px solid black;padding:10px;color:white;text-align:left;margin-bottom:1px}
					.fla_dbgr{position:relative;min-height:40px;min-width:40px;opacity:0.9;transition:opacity 0.2s ease-in-out}
					.fla_dbgr:hover{opacity:1}.fla_close:hover{background-color:red;color:white}
					.fla_dbgr pre{white-space:pre-wrap;white-space:-moz-pre-wrap;white-space:-pre-wrap;white-space:-o-pre-wrap;word-wrap:break-word;font-size:13px!important;font-family:'Courier New',Courier,monospace!important;border:0px!important;background:none!important;color:#FFF!important}
					.fla_key,.fla_array,.fla_array div{font-family:'Courier New',Courier,monospace!important;font-weight:bold;color:white}
					.fla_debug .fla_key{cursor:pointer;font-weight:bold;color:#FF6633}
					.fla_debug .fla_key:hover{text-decoration: underline;}
					.fla_debug.white{background-color:#DDD}
					.fla_debug div.fla_inner_array{margin-left: 30px;}</style>";
					$fla_css = TRUE;
		}
}

function fla_print_lite($arg1, $type, $custom){
	global $fla_css_lite;

	// custom array folding
		if(!is_array($arg1) && $type != 'FILE') $arg1 = htmlentities(print_r($arg1, TRUE));
		elseif($type == 'FILE');
		else $arg1 = foldable_array_lite($arg1);

	// display
		echo "<!-- DEBUGGER LITE -->
				<div class='fla_dbgr_lite'><div class='fla_debug_lite".(($type != 'FILE') ? "'>".$custom."<span style='color:yellow;font:normal 13px arial,sans-serif!important;'>(".$type.")</span><pre>".$arg1."</pre>" : " white'>".$arg1)."</div></div>";
		if(isset($fla_css_lite) && $fla_css_lite) return;
		else{echo "<style>
					.fla_debug_lite{background-color:#000;padding:10px;color:white;text-align:left;margin-bottom:1px}
					.fla_dbgr_lite{position:relative;min-height:40px;min-width:40px;}
					.fla_dbgr_lite pre{white-space:pre-wrap;white-space:-moz-pre-wrap;white-space:-pre-wrap;white-space:-o-pre-wrap;word-wrap:break-word;font-size:13px!important;font-family:'Courier New',Courier,monospace!important;border:0px!important;background:none!important;color:#FFF!important}
					.fla_key,.fla_array,.fla_array div{font-family:'Courier New',Courier,monospace!important;font-weight:bold;color:white}
					.fla_debug_lite .fla_key{font-weight:bold;color:#FF6633}
					.fla_debug_lite.white{background-color:#DDD}
					.fla_debug_lite div.fla_inner_array{margin-left: 30px;}
					.fla_single{display: inline; color: #FFF;font-family:'Courier New',Courier,monospace!important}
				</style>";
				$fla_css_lite = TRUE;
		}   
}
?>
