<?php namespace text\csv;

/**
 * Reads values from CSV lines into a list
 *
 * @test     xp://text.csv.unittest.CsvListReaderTest
 */
class CsvListReader extends CsvReader {
  
  /**
   * Read a record
   *
   * @return  string[] or NULL if end of the file is reached
   */
  public function read() {
    return $this->readValues();
  }    
}