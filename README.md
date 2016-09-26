General
=======

Funky is a PHP framework that makes making anything is PHP much easier.

This is the scale, where words represent everything you can do with the Funky framework:
|raw---basic----simple----pretty neat-----complicated----complex----advanced-----funky|


Usage
=====

Here is a list of basic concepts of the Funky framework.


Services
-------

The general idea of Funky is that your "global functions" are all separated out into *services*.
*services* allow you you organize your imperitive functions and override any logic of the whole framework on a per-site basis.

A simple service would be like this:

```php
class greeter{
	function greet(){
		echo 'hello funky!!!';
	}
}
```

then, to call that function from *anywhere* (yes, anywhere. within raw php pages, models, views, controllers, service functions, *actually anywhere*), you simply type:

```php
j()->greeter->greet();
```

This causes the funky framework to automatically load the greeter class, instantiate it as an object and save that reference, so the second time you use j()->greeter, it is actually the same object.
This is literally all the Funky framework does (which is a good thing, that means it's *very* lightweight), and from there, it's all about the great services that only get loaded if you use them.


Models, Views, and Controllers
--------------------------

Since MVC is great, there are some great ways of organizing larger projects using MVC in Funky.

First: controllers.
1) In order to use controllers, you need the .htaccess file that funky comes with in order to redirect all requests to the top level index.php page.
2) On that page (top level index.php), you must call the router service's route function.

```php
j()->router->route();
```

That is a simple function that sees if a file is being requested and routes to that, otherwise it checks for controller functions.
Each public controller function represents an endpoint (or a uri) for your site.
You can have private controller functions that contain controller logic, but are not endpoints.
This way, routes are handled automatically based on your function naming, and you can get multiple routes easily and automatically just by having a controller.

There are a few different ways to make controllers, depending on what you are trying to do.

The simplest way is to make a controller that only one site can use:


MVC Example / Tutorial:
-----------

Let's make a simple blog. This will solidify using Funky services, as well as how to use MVC ideas.

1) Make a new file for you blog controller (DOCROOT/../controllers/blog.php)
2) Make a basic class called `blog` (NOTE: it is important that the class name matches the file name)

for example:

```php
class blog{
	public function index(){
		echo 'i haz a blog';
	}
}
```

3) Make a view instead of echoing like a pleb. Make a new file for your blog index page (DOCROOT/../views/blog/index.php)

```php
<h1>that one blog</h1>
<p><?=$message?></p>
```

4) Make your controller function load the view, so your controller should look like this:

```php
class blog{
	public function index(){
		// specify view arguments:
		// any arguments passed into the view function will be local variables in your view
		$args = array();
		$args['message'] = 'i haz a blog';
		
		// load the view:
		j()->view('blog/index', $args);
	}
}
```

5) Note how the message key in the array passed to the view gets converted to a local variable within the view.
6) Also note how the load view function is **j()->view**.  This means that it is using the **view** service.

Contributors
============

[Matt Larson](http://mistermashu.com)