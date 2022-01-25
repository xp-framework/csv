<?php namespace text\csv\unittest;

use io\streams\MemoryOutputStream;
use text\csv\{CsvBeanWriter, CsvFormat};
use unittest\Test;

class CsvBeanWriterTest extends CsvWriterTest {

  /**
   * Creates a new CSV writer fixture
   *
   * @param  io.streams.Writer|io.streams.OutputStream|io.Channel|string $out
   * @param  text.csv.CsvFormat $format
   * @return text.csv.CsvWriter
   */
  protected function newFixture($out, $format= null) {
    return new CsvBeanWriter($out, $format);
  }

  #[Test]
  public function writePerson() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(new Person(1549, 'Timm', 'friebe@example.com'));

    $this->assertEquals("1549;Timm;friebe@example.com\n", $out->bytes());
  }

  #[Test]
  public function writePersonReSorted() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(new Person(1549, 'Timm', 'friebe@example.com'), ['email', 'id', 'name']);

    $this->assertEquals("friebe@example.com;1549;Timm\n", $out->bytes());
  }

  #[Test]
  public function writePersonPartially() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(new Person(1549, 'Timm', 'friebe@example.com'), ['id', 'name']);

    $this->assertEquals("1549;Timm\n", $out->bytes());
  }
}