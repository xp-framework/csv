<?php namespace text\csv;

use lang\Generic;

/**
 * Writes objects to CSV lines
 *
 * @test     xp://text.csv.unittest.CsvObjectWriterTest
 */
class CsvObjectWriter extends CsvWriter {

  /**
   * Write a record
   *
   * @param   object object
   * @param   string[] fields if omitted, all fields will be written
   */
  public function write($object, array $fields= []) {
    $values= [];
    $class= typeof($object);

    if ($fields) {
      foreach ($fields as $name) {
        $values[]= $class->getField($name)->setAccessible(true)->get($object);
      }
    } else {
      foreach ($class->getFields() as $field) {
        if (!($field->getModifiers() & MODIFIER_STATIC)) {
          $values[]= $field->setAccessible(true)->get($object);
        }
      }
    }
    return $this->writeValues($values);
  }
}