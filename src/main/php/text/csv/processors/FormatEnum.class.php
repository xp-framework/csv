<?php namespace text\csv\processors;

use text\csv\CellProcessor;
use lang\Enum;


/**
 * Formats enums as cell values. Uses the enum's name member as string
 * representation.
 *
 * @test    xp://net.xp_framework.unittest.text.csv.CellProcessorTest
 * @see     xp://text.csv.CellProcessor
 */
class FormatEnum extends CellProcessor {
  
  /**
   * Processes cell value
   *
   * @param   var in
   * @return  var
   * @throws  lang.FormatException
   */
  public function process($in) {
    if (!$in->getClass()->isEnum()) {
      throw new \lang\FormatException('Cannot format non-enum '.\xp::stringOf($in));
    }
    return $this->proceed($in->name());
  }
}
