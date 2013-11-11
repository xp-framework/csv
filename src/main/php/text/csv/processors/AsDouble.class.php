<?php namespace text\csv\processors;

use text\csv\CellProcessor;


/**
 * Returns cell values as an integer
 *
 * @test    xp://net.xp_framework.unittest.text.csv.CellProcessorTest
 * @see     xp://text.csv.CellProcessor
 */
class AsDouble extends CellProcessor {

  /**
   * Processes cell value
   *
   * @param   var in
   * @return  var
   * @throws  lang.FormatException
   */
  public function process($in) {
    if (1 !== sscanf($in, '%f', $out)) {
      throw new \lang\FormatException('Cannot parse "'.$in.'" into an double');
    }
    return $this->proceed($out);
  }
}
