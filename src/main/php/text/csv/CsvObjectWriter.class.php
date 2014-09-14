<?php namespace text\csv;



/**
 * Writes objects to CSV lines
 *
 * @test     xp://text.csv.unittest.CsvObjectWriterTest
 */
class CsvObjectWriter extends CsvWriter {

  /**
   * Returns field value
   *
   * @param   [:var] array
   * @param   lang.reflect.Field f
   * @return  var
   */
  protected function fieldValue($array, $f) {
    switch ($f->getModifiers() & (MODIFIER_PUBLIC | MODIFIER_PROTECTED | MODIFIER_PRIVATE)) {
      case MODIFIER_PUBLIC: return $array[$f->getName()]; break;
      case MODIFIER_PROTECTED: return $array["\0*\0".$f->getName()]; break;
      case MODIFIER_PRIVATE: return $array["\0".$n."\0".$f->getName()]; break;
    }
  }
  
  /**
   * Write a record
   *
   * @param   lang.Generic object
   * @param   string[] fields if omitted, all fields will be written
   */
  public function write(\lang\Generic $object, array $fields= array()) {
    $values= array();
    $class= $object->getClass();

    // Use the array-cast trick to access private and protected members
    $array= (array)$object;
    if ($fields) {
      foreach ($fields as $name) {
        $values[]= $this->fieldValue($array, $class->getField($name));
      }
    } else {
      foreach ($class->getFields() as $f) {
        $values[]= $this->fieldValue($array, $f);
      }
    }
    return $this->writeValues($values);
  }
}
