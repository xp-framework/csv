<?php namespace text\csv;

/**
 * Processes a cell's value
 */
abstract class CellProcessor extends \lang\Object {
  protected $next= null;

  /**
   * Creates a new cell processor
   *
   * @param   text.csv.CellProcessor $next if omitted, no further processing will be done
   */
  public function __construct(CellProcessor $next= null) {
    $this->next= $next;
  }
  
  /**
   * Processes a cell value
   *
   * @param   var $in
   * @return  var
   */
  public abstract function process($in);

  /**
   * Processes a cell value
   *
   * @param   var $in
   * @return  var
   */
  public function proceed($in) {
    return $this->next ? $this->next->process($in) : $in;
  }
}
