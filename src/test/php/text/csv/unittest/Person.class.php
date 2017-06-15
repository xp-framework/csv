<?php namespace text\csv\unittest;

/**
 * Person value object
 */
class Person {
  private $id, $name, $email;
  
  /**
   * Constructor
   *
   * @param   string id
   * @param   string name
   * @param   string email
   */
  public function __construct($id= '', $name= '', $email= '') {
    $this->id= $id;
    $this->name= $name;
    $this->email= $email;
  }
  
  /**
   * Sets id member
   *
   * @param   string id
   */
  public function setId($id) {
    $this->id= $id;
  }

  /**
   * Gets id member
   *
   * @return  string id
   */
  public function getId() {
    return $this->id;
  }
  
  /**
   * Sets name member
   *
   * @param   string name
   */
  public function setName($name) {
    $this->name= $name;
  }

  /**
   * Gets name member
   *
   * @return  string name
   */
  public function getName() {
    return $this->name;
  }
  
  /**
   * Sets email member
   *
   * @param   string email
   */
  public function setEmail($email) {
    $this->email= $email;
  }

  /**
   * Gets email member
   *
   * @return  string email
   */
  public function getEmail() {
    return $this->email;
  }
}
