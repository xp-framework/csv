<?php namespace text\csv;



/**
 * Writes a list of values to CSV lines
 *
 * @test     xp://net.xp_framework.unittest.text.csv.CsvListWriterTest
 */
class CsvListWriter extends CsvWriter {
  
  /**
   * Write a record
   *
   * @param   string[]
   */
  public function write(array $values) {
    $this->writeValues($values);
  }    
}
