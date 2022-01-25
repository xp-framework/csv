<?php namespace text\csv;

use io\streams\TextReader;
use lang\XPClass;

/**
 * Reads values from CSV lines into Beans. Works like the object reader
 * but instead of directly accessing the properties uses setter methods.
 *
 * Example:
 * <code>
 *   class Person extends Object {
 *     protected $name= '';
 *
 *     public function setName($name) { $this->name= $name; }
 *     public function getName() { return $this->name; }
 *   }
 *   
 *   // ...
 *   $beanreader->read(array('name'));
 * </code>
 *
 * The read creates a Person instance and invokes its setName() method
 * with the value read.
 *
 * @see      xp://text.csv.CsvObjectReader
 * @test     xp://text.csv.unittest.CsvBeanReaderTest
 */
class CsvBeanReader extends CsvReader {
  protected $class= null;

  /**
   * Creates a new CSV reader reading data from a given TextReader
   * creating Beans for a given class.
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

    if (!$fields) foreach ($this->class->getFields() as $f) {
      $fields[]= $f->getName();
    }
    
    $instance= $this->class->newInstance();
    foreach ($fields as $i => $name) {
      $this->class->getMethod('set'.ucfirst($name))->invoke($instance, [$values[$i]]);
    }
    return $instance;
  }    
}