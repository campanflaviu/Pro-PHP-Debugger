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
    19.11.2013 - Fixed multiline array display bug, array css styling, detect ajax call with $_SERVER['HTTP_X_REQUESTED_WITH'] and display result as json

todo
=========
    - memory usage
    - cpu usage? (w/ exec if available or sys_getloadavg)
    - highlight_file('file.php')
    - php_uname (host OS), php_sapi_name (interface between web server and PHP)
    - PHP_VERSION
    - on/off switch
    - IP filter ($_SERVER["REMOTE_ADDR"] and $_SERVER['HTTP_X_FORWARDED_FOR'])
    - dockable option
    - optimize css include
    - features array for easy disabling (to increase performance)