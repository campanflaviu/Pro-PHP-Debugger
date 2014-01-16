Pro PHP Debugger
=========

simple php debugger

usage
=========

 ```php
    fla($target_variable, $second_param);
```
`$second_param` can be a custom string or if `TRUE` it would exit atfer the output is made
```php
    if(fla_sandbox()){
     // sandboxed code here
  }
```


 custom output
 
 ```php
    fla('_funcs');		// all user declared and available functions
    fla('_vars');		// all user defined variables
    fla('_trace');    // backtrace
    fla('_server');   	// server details
```

changelog
=========

    v0.5   - added server details (_server) - memory usage, cpu load, OS, hostname, interface between browser and php, php version
    v0.4   - removed post, get, cookies, files, request, server -> redundant
           - added fla_sandbox() 
    v0.3.1 - fix css rule
           - change LIVE with VIEW_FILTER
           - added USER_AGENT_VAR filter 
    v0.3   - added object to array folding system (fixed error)
           - config zone (LIVE_MODE - viewable only for provided DEBUG_IP or localhost)
           - updated css rules
           - if the second parameter is FALSE, then it would die, otherwise it would print the text
           - on/off switch
           - IP filter
    v0.2   - Fixed multiline array display bug, array css styling, detect ajax call with $_SERVER['HTTP_X_REQUESTED_WITH'] and display result as json
    v0.1   - Foldable array elements

todo
=========
    - [x] on/off switch
    - [x] IP filter
    - [x] memory usage
    - [x] cpu usage? (sys_getloadavg)
    - [ ] highlight_file('file.php')
    - [x] php_uname (host OS), php_sapi_name (interface between web server and PHP)
    - [x] PHP_VERSION
    - [ ] dockable option
    - [ ] optimize css include
    - [ ] features array for easy disabling (to increase performance)
    - [ ] array fold indicator