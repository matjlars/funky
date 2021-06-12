General
=======

Funky is a PHP framework that makes making anything is PHP much easier.

This is the scale, where words represent everything you can do with the Funky framework:
|raw---basic----simple----pretty neat-----complicated----complex----advanced-----funky|


Installation
============

- first, create a new directory for your project and cd into it. for example, run `mkdir my-cool-site && cd my-cool-site`
- then, just run `composer require mistermashu/funky:dev-main && vendor/mistermashu/funky/install.sh`

The first bit of that command uses composer to download the funky package. The second bit runs the install script that basically scaffolds out some files you will need.


Usage
=====

Here is a list of basic concepts of the Funky framework.


Services
-------

The general idea of Funky is that your "global functions" are all separated out into *services*.
*services* allow you you organize your functions and easily override any logic of the whole framework on a per-site basis.

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

This causes the funky framework to automatically load the greeter class, instantiate it as an object and save that reference, so the second time you use f()->greeter, it is actually the same object.
This is literally all the Funky framework does (which is a good thing, that means it's *very* lightweight), and from there, it's all about the great services that only get loaded if you use them.
Additionally, this allows you to override any logic in the whole framework because it's all services all the way down.

All you need to do is define a service in your site and funky will automatically instantiate that new one instead of the one in the framework.
If you want to keep most of the functionality of a built-in service, but make a little change or addition, simply make your service class extend the built-in one, like this:

```php
<?php
// filename: src/services/request.php
namespace services;

class request extends \funky\services\request
{
	// this function is now the main entry point for this request.
	public function perform()
	{
		// you could put some PHP here and it would happen at the beginning of every single request.

		// this is the normal entry-point for requests.
		// you can see this function in vendor/mistermashu/funky/src/services/request.php
		parent::perform();

		// some PHP here would happen at the end of every single request.
	}
}
```


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
<?php
// filename: src/models/post.php
namespace models;

class post extends \funky\model
{
	public static function from_slug($slug)
	{
		return static::query()->where(['slug'=>$slug])->first();
	}

	// this function is required for every model.
	// this is what the migrator uses to automatically generation db migrations
	public static function fields()
	{
		return f()->load->fields([
			['title', 'text'],
			['slug', 'slug'],
			['content', 'markdown'],
			['tags', 'set', ['values'=>[
				'personal',
				'professional',
				'gaming',
			]]],
			['date', 'date', ['default'=>'now']],
		]);
	}
}

```

```php
<?php
// filename: src/controllers/blog.php
namespace controllers;

class blog{
	public function index(){
		return f()->view->load('blog/index');
	}
	public function show($slug){
		return f()->view->load('blog/show', [
			'post'=>\models\post::find_by_slug($slug),
		]);
	}
}
```

3) Make a new file for your blog index page ([PROJECTROOT]/views/blog/index.php)

```php
<h1>that one blog</h1>
<p><?=$message?></p>
```

Notice how the load view function is `f()->view->load()`.  This means that it is using the `view` service's `load` function.

Contributors
============

[Matt Larson](http://mistermashu.com)