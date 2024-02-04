<?php namespace text\csv\unittest;

use io\Channel;
use io\streams\{MemoryOutputStream, OutputStream, TextWriter};
use test\Assert;
use test\{Test, TestCase};

abstract class CsvWriterTest {

  /**
   * Creates a new CSV writer fixture
   *
   * @param  io.streams.Writer|io.streams.OutputStream|io.Channel|string $out
   * @param  text.csv.CsvFormat $format
   * @return text.csv.CsvWriter
   */
  protected abstract function newFixture($out, $format= null);

  #[Test]
  public function can_create_from_channel() {
    $this->newFixture(new class() implements Channel {
      public function in() { /** NOOP */ }
      public function out() { return new MemoryOutputStream(''); }
    });
  }

  #[Test]
  public function can_create_from_stream() {
    $this->newFixture(new MemoryOutputStream(''));
  }

  #[Test]
  public function can_create_from_writer() {
    $this->newFixture(new TextWriter(new MemoryOutputStream('')));
  }

  #[Test]
  public function can_create_from_string() {
    $this->newFixture('');
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

    Assert::true($out->closed);
  }
}