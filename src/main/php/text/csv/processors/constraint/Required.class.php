<?php namespace text\csv\processors\constraint;

use text\csv\CellProcessor;


/**
 * Throws an exception if an empty string is encountered, returns
 * the value otherwise.
 *
 * @test    xp://net.xp_framework.unittest.text.csv.CellProcessorTest
 * @see     xp://text.csv.Optional
 * @see     xp://text.csv.CellProcessor
 */
class Required extends CellProcessor {

  /**
   * Processes cell value
   *
   * @param   var in
   * @return  var
   * @throws  lang.FormatException
   */
  public function process($in) {
    if ('' === $in || null === $in) {
      throw new \lang\FormatException('Empty and NULL values not allowed here');
    }
    return $this->proceed($in);
  }
}
