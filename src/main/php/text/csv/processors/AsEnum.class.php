<?php namespace text\csv\processors;

use text\csv\CellProcessor;
use lang\Enum;


/**
 * Returns cell values as an enum. Uses the enum's name member to 
 * construct an enumeration member.
 *
 * @test    xp://net.xp_framework.unittest.text.csv.CellProcessorTest
 * @see     xp://text.csv.CellProcessor
 */
class AsEnum extends CellProcessor {
  protected $enum= null;

  /**
   * Creates a new instance of this processor.
   *
   * @param   lang.XPClass<? extends lang.Enum> enum
   * @param   text.csv.CellProcessor if omitted, no further processing will be done
   */
  public function __construct(\lang\XPClass $enum, CellProcessor $next= null) {
    parent::__construct($next);
    $this->enum= $enum;
  }
  
  /**
   * Processes cell value
   *
   * @param   var in
   * @return  var
   * @throws  lang.FormatException
   */
  public function process($in) {
    try {
      return $this->proceed(Enum::valueOf($this->enum, $in));
    } catch (\lang\IllegalArgumentException $e) {
      throw new \lang\FormatException($e->getMessage());
    }
  }
}
