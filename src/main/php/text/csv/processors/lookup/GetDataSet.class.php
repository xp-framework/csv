<?php namespace text\csv\processors\lookup;

use rdbms\finder\FinderMethod;
use text\csv\CellProcessor;


/**
 * Returns cell values as a DataSet
 *
 * @test    xp://text.csv.unittest.DataSetCellProcessorTest
 * @see     xp://text.csv.CellProcessor
 */
class GetDataSet extends CellProcessor {
  protected $method= null;

  /**
   * Creates a new instance of this processor.
   *
   * @param   rdbms.finder.FinderMethod
   * @param   rdbms.Criteria c if omitted, the peer's primary key is used
   * @param   text.csv.CellProcessor if omitted, no further processing will be done
   */
  public function __construct(FinderMethod $method, CellProcessor $next= null) {
    parent::__construct($next);
    $this->method= $method;
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
      return $this->method->getFinder()->get($this->method->invoke([$in]));
    } catch (FinderException $e) {
      throw new \lang\FormatException($e->getMessage());
    }
  }
}