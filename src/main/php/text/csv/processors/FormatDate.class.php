<?php namespace text\csv\processors;

use lang\FormatException;
use text\csv\CellProcessor;
use util\Date;
use util\Objects;

/**
 * Formats dates as cell values
 *
 * @test    xp://text.csv.unittest.CellProcessorTest
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
      throw new FormatException('Cannot format non-date '.Objects::stringOf($in));
    } else {
      $date= $in;
    }
    return $this->proceed($date->toString($this->format));
  }
}
