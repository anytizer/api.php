# API.PHP

Minimalistic API Gateway using Apache/.htaccess url rewriting, PHP and PDO. The output is always of JSON type except in case of exceptions raised.

This is an example purpose __Proof of Concept__ work, and may not be suitable for production. However, you can expand it, add security features and use.


## Installation

Upload everything in this project to root of your api gateway (subdomain).
If you installed in subdirectory, mark your offset path correctly.

## API Structure

	/resource/method/[data/id,...]


## APIs Served

Following are the valid test endpoints to this application, which connect to the database.

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

 * Slim, less than 10 KB
 * To add an API, just add a new method to the resource.
 * Does not depend on any third party libraries.


## Program Entry

For an API like __{resource}/{method}/data...__, the program executures like:

api-v1.php => controllers/controller_{resource}/:{http_verb}_{method}($data=array())
