<?php namespace text\csv;

/**
 * Writes beans to CSV lines
 *
 * @test     xp://  .CsvBeanWriterTest
 */
class CsvBeanWriter extends CsvWriter {
    
  /**
   * Write a record
   *
   * @param   object $object
   * @param   string[] fields if omitted, all fields will be written
   */
  public function write($object, array $fields= []) {
    $values= [];
    $class= typeof($object);
    if (!$fields) {
      foreach ($class->getFields() as $f) {
        $values[]= $class->getMethod('get'.ucfirst($f->getName()))->invoke($object);
      }
    } else {
      foreach ($fields as $name) {
        $values[]= $class->getMethod('get'.ucfirst($name))->invoke($object);
      }
    }
    return $this->writeValues($values);
  }
}
