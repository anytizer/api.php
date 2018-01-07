## API.PHP

Minimalistic API Gateway using Apache/.htaccess url rewriting, PHP and PDO.

This work is an example purpose only, as __Proof of Concept__, and may not be suitable for production.


## API Structure

	/resource/method/[data/id,...]


## APIs served

Following are the valid endpoints to this application:

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

 * Slim
 * To add an API, just add a new method to the resource.
 * Does not depend on any third party libraries.


## Program Entry

For an API like __{resource}/{method}/data...__, the program executures like:

api-v1.php => controllers/controller_{resource}/:{http_verb}_{method}($data=array())
