<?php namespace text\csv\processors;

use lang\FormatException;
use text\csv\CellProcessor;
use util\Objects;

/**
 * Formats a given number given the formatting options
 *
 * @test    xp://text.csv.unittest.CellProcessorTest
 * @see     xp://text.csv.CellProcessor
 */
class FormatNumber extends CellProcessor {
  protected
    $decimals           = 2,
    $decimalPoint       = '.',
    $thousandsSeparator = '';
  
  /**
   * Set formatting options
   *
   * @param   int decimals default 2
   * @param   string decimalPoint default "."
   * @param   string thousandsSeparator default ""
   * @return  text.csv.processors.FormatNumber
   */
  public function withFormat($decimals= 2, $decimalPoint= '.', $thousandsSeparator= '') {
    $this->decimals= $decimals;
    $this->decimalPoint= $decimalPoint;
    $this->thousandsSeparator= $thousandsSeparator;
    return $this;
  }

  /**
   * Processes cell value
   *
   * @param   var in
   * @return  var
   * @throws  lang.FormatException
   */
  public function process($in) {
    if (!(null === $in || is_numeric($in))) {
      throw new FormatException('Cannot format non-number '.Objects::stringOf($in));
    }
    return $this->proceed(number_format($in, $this->decimals, $this->decimalPoint, $this->thousandsSeparator));
  }
}
