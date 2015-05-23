<?php namespace text\csv;

class Lines extends \lang\Object implements \Iterator {
  const EOF = -1;
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
  public function current() { return $this->line; }

  /** @return int */
  public function key() { return $this->number; }

  /** @return bool */
  public function valid() { return self::EOF !== $this->number; }

  /** @return void */
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
  public function rewind() {
    $this->number= 0;
    $this->next();
  }
}
