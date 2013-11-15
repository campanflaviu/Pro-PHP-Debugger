<?php


function fla($arg1, $custom_text = "", $die = FALSE){

	// custom text
		if($custom_text) $custom_text .= '<br>';

	// variable type processing
		$type = gettype($arg1);
		switch ($type){
			case 'boolean': $arg1  = $arg1 ? 'TRUE' : 'FALSE'; 		break;
			case 'string':	$type .= ' - '.strlen($arg1).' chars'; 	break;
			case 'array':	$type .= ' - '.count($arg1). ' elems'; 	break;
			default: break;
		}
	
	// cs-cart default ajax error reporting
		if(defined('AJAX_REQUEST')){
			fn_set_notification('N', fn_get_lang_var('notice'), $custom_text.'<span style="color: green">('.$type.')</span><pre style="max-height: 550px;overflow: auto;">'.print_r($arg1, TRUE).'</pre>', 'K');
			return;
		}

	// display
		echo "
			<!-- flaviu@cimpan.ro  - DEBUGGER -->
				<div class='fla_dbgr'>
					<div class='fla_debug'>".$custom_text."<span style='color: yellow'>(".$type.")</span><pre id='fla_pre'>".htmlentities(print_r($arg1, TRUE))."</pre></div>
					<div class='fla_close' onClick='hide_fla(this)'>D</div>
				</div>
				<script>function hide_fla(e){var el=e.parentNode,notes=null
					for(var i=0; i<el.childNodes.length; i++){if (el.childNodes[i].className=='fla_debug'){notes=el.childNodes[i];break}}
					var style=window.getComputedStyle(notes),disp=style.getPropertyValue('display');notes.style.display=(disp=='block')?'none':'block'}
				</script>
				<style>
					.fla_close{position:absolute;right:0px;top:0px;font-size:9px;padding:4px;cursor:pointer;background-color:dimgray;color:dimgray;margin:5px}
					.fla_debug{background-color:dimgray;border:1px solid black;padding:10px;color:white;text-align:left;margin-bottom:1px}
					.fla_dbgr{position:relative;min-height:40px;min-width:40px;opacity:0.7;transition:opacity 0.2s ease-in-out}
					.fla_dbgr:hover{opacity:1}.fla_close:hover{background-color:red;color:white}
					.fla_dbgr pre{white-space:pre-wrap;white-space:-moz-pre-wrap;white-space:-pre-wrap;white-space:-o-pre-wrap;word-wrap:break-word;font-family:'Courier New',Courier,monospace!important}
					.fla_debug span{font-family:'Courier New',Courier,monospace!important;font-weight:bold}
				</style>
			<!-- flaviu@cimpan.ro   -->

		";

	// die if necessary
		if($die) exit;
}

?>