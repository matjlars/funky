General
=======

This folder (/views) is for all of your views.
A view is simply a PHP file that is meant to render out some HTML.
You can easily pass it variables when loading the view.
You can load views from anywhere, including from inside other views.


Usage
=====

Given that you have a view at /views/users/show.php with these contents:

  
  <section>
    <h2>User</h2>
    <p>Name: <?=$user->name?></p>
    <p>Email: <?=$user->email?></p>
  </section>
  

You can then load that view from anywhere like this:

  
  // get the user with id 1:
  $user = new user(1);
  
  // pass this user to the show user view:
  j()->load->view('users/show', array(
    'user'=>$user,
  ));
  

Advanced
========

If you are using controllers, it is generally good practice to make a folder in /views with the same name as your controller.
Then, you can easily tell which view is being called from which controller, and even which function if you are *really* good.
