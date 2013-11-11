<?php namespace text\csv\processors;

use text\csv\CellProcessor;
use util\Date;


/**
 * Formats dates as cell values
 *
 * @test    xp://net.xp_framework.unittest.text.csv.CellProcessorTest
 * @see     xp://text.csv.CellProcessor
 */
class FormatDate extends CellProcessor {
  protected $format= '';
  protected $default= null;

  /**
   * Creates a new date formatter
   *
   * @see     xp://util.Date#toString for format string composition
   * @param   string format
   * @param   text.csv.CellProcessor if omitted, no further processing will be done
   */
  public function __construct($format, CellProcessor $next= null) {
    parent::__construct($next);
    $this->format= $format;
  }

  /**
   * Set default when empty columns are encountered
   *
   * @param   util.Date default
   * @return  text.csv.processors.FormatDate
   */
  public function withDefault(Date $default= null) {
    $this->default= $default;
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
    if (null === $in && null !== $this->default) {
      $date= $this->default;
    } else if (!$in instanceof Date) {
      throw new \lang\FormatException('Cannot format non-date '.\xp::stringOf($in));
    } else {
      $date= $in;
    }
    return $this->proceed($date->toString($this->format));
  }
}
