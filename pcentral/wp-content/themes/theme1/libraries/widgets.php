<?php

/**
 * Loads any widgets needed
 * 
 */

namespace Libraries;
use \Libraries\Loader;

class Widgets {


	public function __construct(){

		$loader = new Loader();
		$functions = array('registerHomeSidebar', 'registerPageSidebar');
		foreach($functions as $function){
			$loader->add_action('widgets_init', $this, $function );
		}
        $loader->run();
		
	}

	public function registerHomeSidebar(){
		register_sidebar( array(
			'name'          => 'Homepage Sidebar',
			'id'            => 'home-page-sidebar',
			'before_widget' => false,
			'after_widget'  => false,
			'before_title'  => '<h2>',
			'after_title'   => '</h2>'
		) );
	}

	public function registerPageSidebar(){
		register_sidebar( array(
			'name'          => 'Page Sidebar',
			'id'            => 'page-sidebar',
			'before_widget' => false,
			'after_widget'  => false,
			'before_title'  => '<h2>',
			'after_title'   => '</h2>'
		) );
	}



}
