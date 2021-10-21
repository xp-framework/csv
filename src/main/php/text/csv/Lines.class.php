<?php namespace text\csv;

use ReturnTypeWillChange, Iterator;

/**
 * Lines in a CSV file
 *
 * @test  xp://text.csv.unittest.LinesTest
 */
class Lines  implements Iterator {
  const EOF = null;
  private $reader, $line, $number;

  /**
   * Creates a new lines instance
   *
   * @param  text.csv.CsvReader $reader
   */
  public function __construct(CsvReader $reader) {
    $this->reader= $reader;
  }

  /** @return var */
  #[ReturnTypeWillChange]
  public function current() { return $this->line; }

  /** @return int */
  #[ReturnTypeWillChange]
  public function key() { return $this->number; }

  /** @return bool */
  #[ReturnTypeWillChange]
  public function valid() { return self::EOF !== $this->number; }

  /** @return void */
  #[ReturnTypeWillChange]
  public function next() {
    if (self::EOF === $this->number) {
      // Already at EOF, don't attempt further reads
    } else if (null === ($this->line= $this->reader->read())) {
      $this->number= self::EOF;
    } else {
      $this->number++;
    }
  }

  /** @return void */
  #[ReturnTypeWillChange]
  public function rewind() {
    $this->number= -1;
    $this->next();
  }
}