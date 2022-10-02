<?php
namespace funky\services;

class sitemap{
	// must return an array of all links to display in the sitemap
	// the array values can be strings or arrays
	// a string value means it's the full URL
	// an array value means it should display all of those tags.
	// ... the key is the tag name, the value is the tag value.
	public function urls(){
		return $this->docroot();
	}

	// returns urls to all php files within the docroot
	public function docroot(){
		$urls = [];
		$dir = f()->path->docroot();
		foreach(scandir($dir) as $file){
			// home page has a special URL:
			if($file == 'index.php'){
				$urls[] = f()->url->get('/');
				continue;
			}
			if(is_file($file) && pathinfo($file, PATHINFO_EXTENSION) == 'php'){
				$urls[] = f()->url->get(pathinfo($file, PATHINFO_FILENAME).'/');
			}
		}
		return $urls;
	}

	// gathers all links by calling all funcs defined in funcs()
	// and returns a string which is a rendered XML sitemap for all the links
	public function render(){
		return f()->view->load('sitemap', [
			'urls'=>$this->urls(),
		]);
	}
}
