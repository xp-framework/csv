<?php namespace text\csv;

use io\streams\TextReader;
use lang\XPClass;

/**
 * Reads values from CSV lines into objects.
 *
 * Example
 *
 * ```php
 * class Person {
 *   protected $name= '';
 * }
 * 
 * // ...
 * $beanreader->read(['name']);
 * ```
 *
 * The read creates a Person instance and sets its name property to
 * the value read.
 *
 * @see      xp://text.csv.CsvBeanReader
 * @test     xp://text.csv.unittest.CsvObjectReaderTest
 */
class CsvObjectReader extends CsvReader {
  protected $class;

  /**
   * Creates a new CSV reader reading data from a given TextReader
   * creating objects for a given class.
   *
   * @param  io.streams.Reader|io.streams.InputStream|io.Channel|string $in
   * @param  string|lang.XPClass $class
   * @param  text.csv.CsvFormat $format
   */
  public function  __construct($in, $class, CsvFormat $format= null) {
    parent::__construct($in, $format);
    $this->class= $class instanceof XPClass ? $class : XPClass::forName($class);
  }
  
  /**
   * Read a record
   *
   * @param  string[] $fields if omitted, class fields are used in order of appearance
   * @return object or NULL if end of the file is reached
   */
  public function read(array $fields= []) {
    if (null === ($values= $this->readValues())) return null;

    $instance= $this->class->newInstance();
    if ($fields) {
      foreach ($fields as $i => $name) {
        $this->class->getField($name)->setAccessible(true)->set($instance, $values[$i]);
      }
    } else {
      $i= 0;
      foreach ($this->class->getFields() as $field) {
        if (!($field->getModifiers() & MODIFIER_STATIC)) {
          $field->setAccessible(true)->set($instance, $values[$i++]);
        }
      }
    }

    return $instance;
  }    
}