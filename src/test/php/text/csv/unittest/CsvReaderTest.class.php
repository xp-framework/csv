<?php namespace text\csv\unittest;

use io\Channel;
use io\streams\{InputStream, MemoryInputStream, TextReader};
use unittest\{Test, TestCase};

abstract class CsvReaderTest extends TestCase {

  /**
   * Creates a new CSV reader fixture
   *
   * @param  io.streams.Reader|io.streams.InputStream|io.Channel|string $in
   * @param  text.csv.CsvFormat $format
   * @return text.csv.CsvReader
   */
  protected abstract function newFixture($in, $format= null);

  #[Test]
  public function can_create_from_channel() {
    $this->newFixture(new class() implements Channel {
      public function in() { return new MemoryInputStream(''); }
      public function out() { /** NOOP */ }
    });
  }

  #[Test]
  public function can_create_from_stream() {
    $this->newFixture(new MemoryInputStream(''));
  }

  #[Test]
  public function can_create_from_reader() {
    $this->newFixture(new TextReader(new MemoryInputStream('')));
  }

  #[Test]
  public function can_create_from_string() {
    $this->newFixture('');
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