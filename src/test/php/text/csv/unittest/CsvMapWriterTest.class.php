<?php namespace text\csv\unittest;

use io\streams\MemoryOutputStream;
use text\csv\{CsvFormat, CsvMapWriter};
use test\Assert;
use test\Test;

class CsvMapWriterTest extends CsvWriterTest {

  /**
   * Creates a new CSV writer fixture
   *
   * @param  io.streams.Writer|io.streams.OutputStream|io.Channel|string $out
   * @param  text.csv.CsvFormat $format
   * @return text.csv.CsvWriter
   */
  protected function newFixture($out, $format= null) {
    return new CsvMapWriter($out, $format);
  }

  #[Test]
  public function write_record() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(['id' => 1549, 'name' => 'Timm', 'email' => 'friebe@example.com']);

    Assert::equals("1549;Timm;friebe@example.com\n", $out->bytes());
  }

  #[Test]
  public function write_record_with_headers() {
    $out= new MemoryOutputStream();
    $fixture= $this->newFixture($out);
    $fixture->setHeaders(['id', 'name', 'email']);
    $fixture->write(['id' => 1549, 'name' => 'Timm', 'email' => 'friebe@example.com']);

    Assert::equals("id;name;email\n1549;Timm;friebe@example.com\n", $out->bytes());
  }

  #[Test]
  public function write_unordered_record_with_headers() {
    $out= new MemoryOutputStream();
    $fixture= $this->newFixture($out);
    $fixture->setHeaders(['id', 'name', 'email']);
    $fixture->write(['email' => 'friebe@example.com', 'name' => 'Timm', 'id' => 1549]);

    Assert::equals("id;name;email\n1549;Timm;friebe@example.com\n", $out->bytes());
  }


  #[Test]
  public function write_incomplete_record_with_headers() {
    $out= new MemoryOutputStream();
    $fixture= $this->newFixture($out);
    $fixture->setHeaders(['id', 'name', 'email']);
    $fixture->write(['id' => 1549, 'email' => 'friebe@example.com']);

    Assert::equals("id;name;email\n1549;;friebe@example.com\n", $out->bytes());
  }

  #[Test]
  public function write_empty_record_with_headers() {
    $out= new MemoryOutputStream();
    $fixture= $this->newFixture($out);
    $fixture->setHeaders(['id', 'name', 'email']);
    $fixture->write([]);

    Assert::equals("id;name;email\n;;\n", $out->bytes());
  }

  #[Test]
  public function write_record_with_extra_data_with_headers() {
    $out= new MemoryOutputStream();
    $fixture= $this->newFixture($out);
    $fixture->setHeaders(['id', 'name', 'email']);
    $fixture->write(['id' => 1549, 'name' => 'Timm', 'email' => 'friebe@example.com', 'extra' => 'WILL_NOT_APPEAR']);

    Assert::equals("id;name;email\n1549;Timm;friebe@example.com\n", $out->bytes());
  }
}