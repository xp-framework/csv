<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  uses('text.csv.CellProcessor');

  /**
   * Abstract base class for CSV processors
   *
   * @see     http://en.wikipedia.org/wiki/Comma-separated_values
   * @see     rfc://4180
   * @test    xp://net.xp_framework.unittest.text.csv.ProcessorAccessorsTest
   */
  abstract class AbstractCsvProcessor extends Object {
    protected $processors= array();

    /**
     * Adds a processor
     *
     * @return  text.csv.CellProcessor processor
     */
    public function addProcessor(CellProcessor $processor) {
      $this->processors[]= $processor;
      return $processor;
    }
    
    /**
     * Sets processors and return this writer
     *
     * @param   text.csv.CellProcessor[] processors
     * @return  text.csv.AbstractCsvProcessor this processor
     */
    public function withProcessors(array $processors) {
      $this->processors= $processors;
      return $this;
    }

    /**
     * Sets processors
     *
     * @param   text.csv.CellProcessor[] processors
     */
    public function setProcessors(array $processors) {
      $this->processors= $processors;
    }

    /**
     * Sets processor for a specific column
     *
     * @param   int offset
     * @param   text.csv.CellProcessor[] processors
     * @return  text.csv.AbstractCsvProcessor this processor
     */
    public function withProcessor($offset, CellProcessor $processor) {
      $this->processors[$offset]= $processor;
      return $this;
    }

    /**
     * Sets processor for a specific column
     *
     * @param   int offset
     * @param   text.csv.CellProcessor[] processors
     */
    public function setProcessor($offset, CellProcessor $processor) {
      $this->processors[$offset]= $processor;
    }

    /**
     * Gets processors
     *
     * @return  text.csv.CellProcessor[] processors
     */
    public function getProcessors() {
      return $this->processors;
    }
  }
?>
