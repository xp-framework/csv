<?php namespace text\csv;

/**
 * Writes a list of values to CSV lines
 *
 * @test     xp://text.csv.unittest.CsvListWriterTest
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