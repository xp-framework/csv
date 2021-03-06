<?php namespace text\csv\processors;

use text\csv\CellProcessor;


/**
 * Returns cell values as booleans. The following mappings exist per
 * default:
 * <ul>
 *   <li>TRUE: true, 1, Y</li>
 *   <li>FALSE: false, 0, N</li>
 * </ul>
 *
 * Note: The values are recognized case-sensitively!
 *
 * @test    xp://text.csv.unittest.CellProcessorTest
 * @see     xp://text.csv.CellProcessor
 */
class AsBool extends CellProcessor {
  protected $true= [];
  protected $false= [];
  
  /**
   * Creates a new instance of this processor.
   *
   * @param   string[] true
   * @param   string[] false
   * @param   text.csv.CellProcessor if omitted, no further processing will be done
   */
  public function __construct($true= ['true', '1', 'Y'], $false = ['false', '0', 'N'], CellProcessor $next= null) {
    parent::__construct($next);
    $this->true= $true;
    $this->false= $false;
  }

  /**
   * Processes cell value
   *
   * @param   var in
   * @return  var
   * @throws  lang.FormatException if the string cannot be parsed
   */
  public function process($in) {
    if (in_array($in, $this->true, true)) return $this->proceed(true);
    if (in_array($in, $this->false, true)) return $this->proceed(false);
    throw new \lang\FormatException('Cannot parse "'.$in.'"');
  }
}