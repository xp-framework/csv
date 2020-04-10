<?php namespace text\csv;

/**
 * Writes maps to CSV lines
 *
 * @test     xp://text.csv.unittest.CsvMapWriterTest
 */
class CsvMapWriter extends CsvWriter {
  protected $keys= null;

  /**
   * Set header line
   *
   * @param   string[] headers
   * @throws  lang.IllegalStateException if writing has already started
   */
  public function setHeaders($headers) {
    parent::setHeaders($headers);
    $this->keys= $headers;
  }
  
  /**
   * Write a record
   *
   * @param   lang.Generic object
   * @param   string[] fields if omitted, all fields will be written
   */
  public function write(array $map) {
    $values= [];
    if (null === $this->keys) {
      foreach ($map as $key => $value) {
        $values[]= $value;
      }
    } else {
      foreach ($this->keys as $key) {
        $values[]= $map[$key] ?? null;
      }
    }
    return $this->writeValues($values);
  }
}