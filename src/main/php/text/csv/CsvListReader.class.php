<?php namespace text\csv;



/**
 * Reads values from CSV lines into a list
 *
 * @test     xp://net.xp_framework.unittest.text.csv.CsvListReaderTest
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
