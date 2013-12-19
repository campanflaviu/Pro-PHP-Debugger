fla_debug
=========

simple php debugger

usage
=========

    fla($target_variable);

 custom output
 
    fla('_funcs');		// all user declared and available functions
    fla('_vars');		// all user defined variables
    fla('_post');   	// $_POST variables
    fla('_get');    	// $_GET variables
    fla('_cookies');	// $_COOKIE variables
    fla('_files');   	// $_FILES variables
    fla('_request'); 	// $_REQUEST variables
    fla('_server');  	// $_SERVER variables
    fla('_trace');   	// backtrace

changelog
=========

    20.11.2013 - Foldable array elements
    19.11.2013 - Fixed multiline array display bug, array css styling