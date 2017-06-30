General
=======

Funky is a PHP framework that makes making anything is PHP much easier.

This is the scale, where words represent everything you can do with the Funky framework:
|raw---basic----simple----pretty neat-----complicated----complex----advanced-----funky|


Usage
=====

To start using it, use composer to require it into your project by running the following command:

`composer require mistermashu/funky:dev-master`

Here is a list of basic concepts of the Funky framework.


Services
-------

The general idea of Funky is that your "global functions" are all separated out into *services*.
*services* allow you you organize your imperitive functions and override any logic of the whole framework on a per-site basis.

A simple service would be like this:

```php
namespace services;

class greeter{
	function greet(){
		echo 'hello funky!!!';
	}
}
```

Then, to call that function from *anywhere* (yes, anywhere. within raw php pages, models, views, controllers, service functions, *actually anywhere*), you simply type:

```php
f()->greeter->greet();
```

This causes the funky framework to automatically load the greeter class, instantiate it as an object and save that reference, so the second time you use j()->greeter, it is actually the same object.
This is literally all the Funky framework does (which is a good thing, that means it's *very* lightweight), and from there, it's all about the great services that only get loaded if you use them.


Models, Views, and Controllers
--------------------------

Since MVC is great, there are some great ways of organizing larger projects using MVC in Funky. You don't have to use any of this, but you should because it's awesome.

First: controllers.

Each public controller function represents an endpoint (or a uri) for your site.
You can have private controller functions that contain controller logic, but are not endpoints.
This way, routes are handled automatically based on your function naming, and you can get multiple routes easily and automatically just by having a controller.
Follow the little tutorial below to learn how to make a controller.

MVC Example / Tutorial:
-----------

Let's make a simple blog. This will solidify using Funky services, as well as how to use MVC concepts.

1) Make a new file for you blog controller ([PROJECTROOT]/src/controllers/blog.php)
2) Make a basic class called `blog` in the `controllers` namespace (NOTE: it is important that the class name matches the file name)
3) NOTE: It is in the `controllers` namespace because it is in the `controllers` directory. This way, funky can efficiently find all controllers.

for example:

```php
namespace controllers;

class blog{
	public function index(){
		echo 'i haz a blog';
	}
}
```

3) Make a view instead of echoing like a pleb. Make a new file for your blog index page ([PROJECTROOT]/views/blog/index.php)

```php
<h1>that one blog</h1>
<p><?=$message?></p>
```

4) Make your controller function load the view, so your controller should look like this:

```php
namespace controllers;

class blog{
	public function index(){
		// specify view arguments:
		// any arguments passed into the view function will be local variables in your view
		$args = array();
		$args['message'] = 'i haz a blog';
		
		// load the view:
		return j()->view->load('blog/index', $args);
	}
}
```

5) Note how the message key in the array passed to the view gets converted to a local variable within the view.
6) Also note how the load view function is `j()->view->load()`.  This means that it is using the `view` service's `load` function.

Contributors
============

[Matt Larson](http://mistermashu.com)