<?php namespace text\csv\unittest;

use io\streams\{InputStream, MemoryInputStream};
use unittest\{Test, TestCase};

abstract class CsvReaderTest extends TestCase {

  /**
   * Creates a new CSV reader fixture
   *
   * @param  io.streams.InputStream $stream
   * @param  text.csv.CsvFormat $format
   * @return text.csv.CsvReader
   */
  protected abstract function newFixture($stream, $format= null);

  #[Test]
  public function can_create() {
    $this->newFixture(new MemoryInputStream(''));
  }

  #[Test]
  public function can_close() {
    $in= new class() implements InputStream {
      public $closed= false;
      public function available() { return 0; }
      public function read($length= 8192) { /** NOOP */ }
      public function close() { $this->closed= true; }
    };
    $this->newFixture($in)->close();

    $this->assertTrue($in->closed);
  }
}