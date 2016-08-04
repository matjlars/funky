General
=======

This folder (/models) is for all of your models.
They are auto-loaded if you use the class name corresponding to the file name.


Usage
=====

If you have a "user" model, put it in /models/user.php.  That way, when you have the code:

  $user = new user();

It will automatically include the file /models/user.php because you referenced the "user" class.


Advanced
========

You do not have to, but if you want a really nice and simple model for a database table, you can extend the site_model class.

For example, here is a really simple user model that references a table in your database called users

  <?php
  class user extends site_model
  {
    protected $table = 'users';
  }
  
  // anywhere else (a controller, or even a view if you are a goofy goober)
  $user = new user(1); // get user with id 1
  echo $user->name; // output the user name
  
  // insert a new with name bob:
  $newuser = new user(array('name'=>'bob'));
  