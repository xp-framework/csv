<?php namespace text\csv\unittest;

use io\streams\{OutputStream, MemoryOutputStream};
use unittest\{Test, TestCase};

abstract class CsvWriterTest extends TestCase {

  /**
   * Creates a new CSV writer fixture
   *
   * @param  io.streams.OutputStream $stream
   * @param  text.csv.CsvFormat $format
   * @return text.csv.CsvWriter
   */
  protected abstract function newFixture($stream, $format= null);

  #[Test]
  public function can_create() {
    $this->newFixture(new MemoryOutputStream());
  }

  #[Test]
  public function can_close() {
    $out= new class() implements OutputStream {
      public $closed= false;
      public function write($bytes) { /** NOOP */ }
      public function flush() { /** NOOP */ }
      public function close() { $this->closed= true; }
    };
    $this->newFixture($out)->close();

    $this->assertTrue($out->closed);
  }
}