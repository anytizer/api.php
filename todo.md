Autoload includer with composer

Generate more logs
	output.failed/suppressed
	authorization.failed

Include tokens
	bearer token
	x protection token

Relocate logs/*.log (out of src/)
	mkdir logs
	touch logs/api-events.log

Put controller and models together within package:
This way, there is a chance to create a drop-off ready package.
packages > NAMES > model, controller.

Current scenario is:
pachage > NAME > models, > controllers - as a group.
