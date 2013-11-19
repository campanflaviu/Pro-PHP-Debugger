fla_debug
=========

simple php debugger

USAGE
=========

    fla($target_variable);

 custom output
 
    fla('_funcs'); 		// display all user declared and available functions
    fla('_vars');  		// display all user defined variables
    fla('_post')   		// display $_POST variables
    fla('_get')    		// display $_GET variables
    fla('_cookies')   // display $_COOKIE variables
    fla('_files')     // display $_FILES variables
    fla('_request')   // display $_REQUEST variables
    fla('_server')   	// display $_SERVER variables
    fla('_trace')   	// display backtrace
    
