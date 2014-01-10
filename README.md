Pro PHP Debugger
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

    v0.1 - Foldable array elements
    v0.2 - Fixed multiline array display bug, array css styling, detect ajax call with $_SERVER['HTTP_X_REQUESTED_WITH'] and display result as json
    v0.3 - added object to array folding system (fixed error)
         - config zone (LIVE_MODE - viewable only for provided DEBUG_IP or localhost)
         - updated css rules
         - if the second parameter is FALSE, then it would die, otherwise it would print the text
         - on/off switch
         - IP filter

todo
=========
    - memory usage
    - cpu usage? (w/ exec if available or sys_getloadavg)
    - highlight_file('file.php')
    - php_uname (host OS), php_sapi_name (interface between web server and PHP)
    - PHP_VERSION
    <del>- on/off switch</del>
    <del>- IP filter ($_SERVER["REMOTE_ADDR"] and $_SERVER['HTTP_X_FORWARDED_FOR'])</del>
    - dockable option
    - optimize css include
    - features array for easy disabling (to increase performance)

uncommited fixes
