<?php namespace text\csv\unittest;
/**
 * Address value object
 */
class Address  {
  public 
    $name   = '', 
    $city   = '',
    $zip    = '';
  
  /**
   * Constructor
   *
   * @param  string $name
   * @param  string $city
   * @param  string $zip
   */
  public function __construct($name= '', $city= '', $zip= '') {
    $this->name= $name;
    $this->city= $city;
    $this->zip= $zip;
  }
}