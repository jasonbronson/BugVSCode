<?php
/**
 * Site Options
 *
 *
 */
namespace Libraries;

class Options {

  protected $dateFormat = 'M j, Y';

  public function __construct(){
    $this->addOptions();
    $this->getDateFormat();
  }

  public function addOptions(){
    acf_add_options_page(
      array(
        'page_title' 	=> 'Site Options',
        'menu_title'	=> 'Site Options',
        'menu_slug' 	=> 'site-options',
        'capability'	=> 'edit_posts',
        'redirect'		=> true
      )
    );

    acf_add_options_sub_page(
      array(
        'page_title' 	=> 'Social Icons',
        'menu_title'	=> 'Social Icons',
        'parent_slug'	=> 'site-options',
      )
    );
  }

  public function setDateFormat($format){
    $this->dateFormat = $format;
  }

  public function getDateFormat(){
      return $this->dateFormat;
  }

}
