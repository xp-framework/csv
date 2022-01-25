<?php namespace text\csv\unittest;

use io\streams\{OutputStream, MemoryOutputStream, TextWriter};
use text\csv\{CsvMapWriter, CsvFormat};
use unittest\Test;

class CsvMapWriterTest extends CsvWriterTest {

  /**
   * Creates a new CSV writer fixture
   *
   * @param  io.streams.OutputStream $stream
   * @param  text.csv.CsvFormat $format
   * @return text.csv.CsvWriter
   */
  protected function newFixture($stream, $format= null) {
    return new CsvMapWriter(new TextWriter($stream), $format);
  }

  #[Test]
  public function write_record() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(['id' => 1549, 'name' => 'Timm', 'email' => 'friebe@example.com']);

    $this->assertEquals("1549;Timm;friebe@example.com\n", $out->bytes());
  }

  #[Test]
  public function write_record_with_headers() {
    $out= new MemoryOutputStream();
    $fixture= $this->newFixture($out);
    $fixture->setHeaders(['id', 'name', 'email']);
    $fixture->write(['id' => 1549, 'name' => 'Timm', 'email' => 'friebe@example.com']);

    $this->assertEquals("id;name;email\n1549;Timm;friebe@example.com\n", $out->bytes());
  }

  #[Test]
  public function write_unordered_record_with_headers() {
    $out= new MemoryOutputStream();
    $fixture= $this->newFixture($out);
    $fixture->setHeaders(['id', 'name', 'email']);
    $fixture->write(['email' => 'friebe@example.com', 'name' => 'Timm', 'id' => 1549]);

    $this->assertEquals("id;name;email\n1549;Timm;friebe@example.com\n", $out->bytes());
  }


  #[Test]
  public function write_incomplete_record_with_headers() {
    $out= new MemoryOutputStream();
    $fixture= $this->newFixture($out);
    $fixture->setHeaders(['id', 'name', 'email']);
    $fixture->write(['id' => 1549, 'email' => 'friebe@example.com']);

    $this->assertEquals("id;name;email\n1549;;friebe@example.com\n", $out->bytes());
  }

  #[Test]
  public function write_empty_record_with_headers() {
    $out= new MemoryOutputStream();
    $fixture= $this->newFixture($out);
    $fixture->setHeaders(['id', 'name', 'email']);
    $fixture->write([]);

    $this->assertEquals("id;name;email\n;;\n", $out->bytes());
  }

  #[Test]
  public function write_record_with_extra_data_with_headers() {
    $out= new MemoryOutputStream();
    $fixture= $this->newFixture($out);
    $fixture->setHeaders(['id', 'name', 'email']);
    $fixture->write(['id' => 1549, 'name' => 'Timm', 'email' => 'friebe@example.com', 'extra' => 'WILL_NOT_APPEAR']);

    $this->assertEquals("id;name;email\n1549;Timm;friebe@example.com\n", $out->bytes());
  }
}