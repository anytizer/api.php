# API.PHP

Minimalistic API Server Gateway using Apache, .htaccess url rewriting, PHP and PDO MySQL.

Output format: [JSON](http://json.org/).

This is an example purpose __proof of concept__ work, and may not be instantly suitable for your production environment.
However, you can expand it, add security features into it and use.

This project will leave a very small footprint as your API Server script.
In less than 15 KB file size (including source code comments), you will get a lot of benefits.


## Benefits

 * Handles GET/POST/PUT/PATCH etc. separately.
 * To add an API, just add a new method to the controller resource. Controller actions are dynamic.
 * Free from bulky third party libraries.
 * Separation of concerns (controllers, models, logging).
 * Useful in light-weight micro-services.
 * Uses PDO MySQL.
 * Has access logs.
 * Has event dispatchers.


## Important Files

  |- src/.htaccess
  |- src/api-v1.php
  |- packages
    - system
	  - abstracts
	    - *.*
	  - dispatchers
	    - *.*
	  - class.api_manager.inc.php
	- [YOUR PACKAGE]
      - models / class.model_*.inc.php
	  - controllers / class.controller_*.inc.php
  |- logs/

 `api-v1.php` attemps to route to proper package access. The logic defined in `class.api_manager.inc.php`.


## Controller method naming

Controllers are located at: /packages/{YOURPACKAGE}/controllers/controller_{NAME}.
The prefixes and namespaces are used **purposefully to discourage accessing PHP's default classes**  as controller names.

In your controllers, write functions like: _get_index()_ or _post_index()_ to access /index API using GET or POST methods.
Every such method receives $data=array() parameter from the remaining URL. For example, if you are accessing `/index/7/12` api using GET,
you will write: get_index($data=array()) function, where, the value of $data will be:

  array(
    [0] => 7
    [1] => 12
  );

and so on. In the example, 7 and 12 are your own defined numbers.


## File naming

PSR-4 like, but file name in the format: /name/space/class.{NAME}.inc.php.
File `models\class.age.inc.php` to access class `new models\age()`.


## Contents

This script hosts barely no real API. ie. Interfacing read/write to the database is NOT done, until you exapand it.
The calendar api calculates the dates without a need of any real MySQL Tables.
Implementation of a real API is left to the users.


## Installation

Upload everything in this project to root of your api gateway (subdomain).

    composer require anytizer/api.php:dev-master


### Configuration

 * Download this application somewhere you can manage virtual host.
 * Set `api.example.com 127.0.0.1` in `hosts` file.
 * Set virtual host files. See `vhosts-demo.conf` for an example.
 * If you installed in subdirectory, note your offset path correctly to reach /src/.
 * Modify your API's offset path in [api-v1.php](api-v1.php).
 * Enter database connection details in [class.model_abstracts.inc.php](classes/abstracts/class.model_abstracts.inc.php).
 * Done!


## API Structure

    /{package}/{resource}/{method}/[data/id,...]

All other parameters in $_GET, $_POST, $_SERVER headers remain unchanged. They are available globally. Data found in php://input will however replace the empty $_POST. This may be useful in case your API Gateway is receiving conents from AngularJS like clients.


## APIs Served

Following are the valid example purpose endpoints to test this application.
Results are calculated from the database server.

 * `/auto/auto/index`
 * `/calendar/age/old`
 * `/calendar/age/old/8`
 * `/calendar/age/old/-8`
 * `/calendar/age/yesterday`
 * `/calendar/age/today`
 * `/calendar/age/tomorrow`
 * `/calendar/age/future`
 * `/calendar/age/future/50`
 * `/calendar/age/future/-10`

See [class.controller_age.inc.php](classes/controllers/class.controller_age.inc.php) on how __/age/__ APIs work.
Any method name starting as get_, post_, put_ will serve as the `{method}` in your URL.
Any class name inside controllers\\controller_* will serve as the `{resource}` in your URL.



## Program Entry

For an API like __{resource}/{method}/data...__, the program executures like:

api-v1.php => controllers/controller___{resource}__/:{http_verb}___{method}__($data=array())

 * .htaccess __always__ redirects to `api-v1.php`.
 * It seeks for a valid controller resource.
 * It seeks for valid method in that controller.
 * Executes controller's method and outputs in JSON.


## Testing with cURL

This application comes with a sample calendar age calculator that connects to MySQL Database for this purpose.
Right after you install it correctly, the following `curl` endpoints will show date-related output.

### cURL based testing

 * `ping api.example.com`
 * `curl api.example.com:88/calendar/age/old`
 * `curl api.example.com:88/calendar/age/old/8`
 * `curl api.example.com:88/calendar/age/old/-8`
 * `curl api.example.com:88/calendar/age/yesterday`
 * `curl api.example.com:88/calendar/age/today`
 * `curl api.example.com:88/calendar/age/tomorrow`
 * `curl api.example.com:88/calendar/age/future`
 * `curl api.example.com:88/calendar/age/future/50`
 * `curl api.example.com:88/calendar/age/future/-10`


 ### PHPUnit testing

 PHPUnit can be used to test this API Server.
 It is included with the application.
 In the command line, change directory to the phpunit. And run `phpunit` command.


## DIY

 * __Server Script__: `api-v1.php` can be further chunked into settings, etc. But listed in full-body for educational purpose.
 * __Versioning__: Create a whole new set of applications and update .htaccess to point new API Processor.
 * __Access Logs__: beyond the scope, manage yourself
 * __Event Dispatching__: beyond the scope, manage yourself
 * __API Security__: beyond the scope, manage yourself


## Independent APIs

 * Your APIs may run independent of your website.
 * In that case, create a virtual host or subdomain.
