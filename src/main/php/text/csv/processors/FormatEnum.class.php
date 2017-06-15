<?php namespace text\csv\processors;

use text\csv\CellProcessor;
use lang\Enum;
use lang\FormatException;

/**
 * Formats enums as cell values. Uses the enum's name member as string
 * representation.
 *
 * @test    xp://text.csv.unittest.CellProcessorTest
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
    if ($in instanceof Enum) return $this->proceed($in->name());

    throw new FormatException('Cannot format non-enum '.typeof($in)->getName());
  }
}
