<?php namespace text\csv\processors;

use text\csv\CellProcessor;


/**
 * Formats bools as cell values. Uses the string "true" for TRUE values
 * the string "false" for FALSE values per default, which may be changed
 * by supplying other values in the constructor.
 *
 * @test    xp://text.csv.unittest.CellProcessorTest
 * @see     xp://text.csv.CellProcessor
 */
class FormatBool extends CellProcessor {
  protected $true= '';
  protected $false= '';

  /**
   * Creates a new bool formatter
   *
   * @param   string true
   * @param   string false
   * @param   text.csv.CellProcessor if omitted, no further processing will be done
   */
  public function __construct($true= 'true', $false= 'false', CellProcessor $next= null) {
    parent::__construct($next);
    $this->true= $true;
    $this->false= $false;
  }
  
  /**
   * Processes cell value
   *
   * @param   var in
   * @return  var
   * @throws  lang.FormatException
   */
  public function process($in) {
    return $this->proceed($in ? $this->true : $this->false);
  }
}
