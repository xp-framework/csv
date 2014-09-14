<?php namespace text\csv\processors\constraint;

use text\csv\CellProcessor;


/**
 * Throws an exception if a value is encountered more than once.
 *
 * @test    xp://text.csv.unittest.CellProcessorTest
 * @see     xp://text.csv.CellProcessor
 */
class Unique extends CellProcessor {
  protected $values= array();

  /**
   * Processes cell value
   *
   * @param   var in
   * @return  var
   * @throws  lang.FormatException
   */
  public function process($in) {
    if (isset($this->values[$in])) {
      throw new \lang\FormatException('Value "'.$in.'" already encountered');
    }
    $this->values[$in]= true;
    return $this->proceed($in);
  }
}
