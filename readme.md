# API.PHP

Minimalistic API Gateway using Apache/.htaccess url rewriting, PHP and PDO. The output is always of JSON type except in case of exceptions raised.

This is an example purpose __Proof of Concept__ work, and may not be suitable for production. However, you can expand it, add security features and use.


## Installation

Upload everything in this project to root of your api gateway (subdomain).
If you installed in subdirectory, mark your offset path correctly.


## Configuration

 * Offset path in [api-v1.php](api-v1.php)
 * Database connections in [class.model_abstracts.inc.php](classes/abstracts/class.model_abstracts.inc.php)


## API Structure

	/resource/method/[data/id,...]


## APIs Served

Following are the valid endpoints to test this application. Results are calculated from the database server.

	/
	/age
	/age/today
	/age/yesterday
	/age/old
	/age/old/8
	/age/tomorrow
	/age/future
	/age/future/40


## Benefits

 * Slim, less than 10 KB, including source code comments.
 * To add an API, just add a new method to the resource.
 * Independent of bulky third party libraries.
 * Separation of concerns
 * Useful in light-weight micro-services


## Program Entry

For an API like __{resource}/{method}/data...__, the program executures like:

api-v1.php => controllers/controller_{resource}/:{http_verb}_{method}($data=array())

 * .htaccess always redirects to api-v1.php.
 * It seeks for a valid controller resource
 * It seeks for valid method in that controller
 * Executes controller's method and outputs in JSON.


## DIY

 * Versioning: Create a whole new set of applications and update .htaccess to point new API Processor.
 * Logging: Manage access logs yourself
 * API Security: beyond the scope, manage yourself
 