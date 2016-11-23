Models
======

Models are your primary way of using the database in Funky.

When you define models, database schemas are automatically generated based on the return value of the static "fields()" for your model.

Example
=============

Here is a basic example of making a fully functioning "user" model that contains a first name, last name, and email address.

```php
<?php
namespace models;

class user extends \model{
	public static function fields(){
		return f()->load->fields([
			['firstname', 'text'],
			['lastname', 'text'],
			['email', 'text', ['maxlength'=>200]],
		]);
	}
}
```

Notes about the example
-----------------------

- the namespace applies to all files in funky. Because this is in the namespace "models", both you and the framework knows this file is located in the "src/models/" directory.
- it extends the \model class, which provides a lot of basic functions that you will certainly need for database models.
- the static "fields()" function must return an array of object of type "\fields\field", so there is a handy loader function that simplifies this function significantly.
- the fields are then used when you log into the "admin admin" section to generate and run database migrations for this model.
- the "maxlength" on the email field does a number of things:
	- sets the length in the database schema
	- determines which database type to use (<255 is VARCHAR, <4096 is SMALLTEXT, etc..)
	- creates a validator for the model to provide a nice error message when somebody tries to type more than the max length.
- it's easy to make your own field type by putting it in "src/fields/" which means "namespace fields" and extend the "\fields\field" class.
	- if you want examples, just look at the ones in "funky/src/fields"


Saving data to the database
===========================

