<?php namespace text\csv;

use io\streams\TextReader;

/**
 * Reads values from CSV lines into maps.
 *
 * @see      xp://text.csv.CsvListReader
 * @test     xp://text.csv.unittest.CsvMapReaderTest
 */
class CsvMapReader extends CsvReader {
  protected $keys= [];

  /**
   * Creates a new CSV reader reading data from a given TextReader
   * creating Beans for a given class.
   *
   * @param   io.streams.TextReader reader
   * @param   string[] keys
   * @param   text.csv.CsvFormat format
   */
  public function  __construct(TextReader $reader, array $keys= [], CsvFormat $format= null) {
    parent::__construct($reader, $format);
    $this->keys= $keys;
  }
  
  /**
   * Set keys
   *
   * @param   string[] keys
   */
  public function setKeys(array $keys) {
    $this->keys= $keys;
  }

  /**
   * Set keys
   *
   * @param   string[] keys
   * @return  text.csv.CsvMapReader this reader
   */
  public function withKeys(array $keys) {
    $this->keys= $keys;
    return $this;
  }

  /**
   * Get keys
   *
   * @return  string[] keys
   */
  public function getKeys() {
    return $this->keys;
  }

  /**
   * Read a record
   *
   * @return  [:var] or NULL if end of the file is reached
   */
  public function read() {
    if (null === ($values= $this->readValues())) return null;

    $map= [];
    $s= sizeof($values)- 1;
    foreach ($this->keys as $i => $key) {
      $map[$key]= $i > $s ? null : $values[$i];
    }
    return $map;
  }    
}