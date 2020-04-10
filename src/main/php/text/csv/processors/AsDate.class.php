<?php namespace text\csv\processors;

use text\DateFormat;
use text\csv\CellProcessor;
use util\Date;


/**
 * Returns cell values as a date objects
 *
 * @test    xp://text.csv.unittest.CellProcessorTest
 * @see     xp://text.csv.CellProcessor
 */
class AsDate extends CellProcessor {
  protected $default= null;
  protected $format= null;
  
  /**
   * Set default when empty columns are encountered
   *
   * @param   util.Date default
   * @return  text.csv.processors.AsDate
   */
  public function withDefault(Date $default= null) {
    $this->default= $default;
    return $this;
  }

  /**
   * Set date format
   *
   * @param   text.DateFormat format
   * @return  text.csv.processors.AsDate
   */
  public function withFormat(DateFormat $format) {
    $this->format= $format;
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
    if ('' !== $in) {
      try {
        if ($this->format) {
          $date= $this->format->parse($in);
        } else {
          $date= new Date($in);
        }
      } catch (\lang\IllegalArgumentException $e) {
        throw new \lang\FormatException($e->getMessage());
      }
    } else if (null === $this->default) {
      throw new \lang\FormatException('Cannot parse empty date');
    } else {
      $date= $this->default;
    }
    return $this->proceed($date);
  }
}