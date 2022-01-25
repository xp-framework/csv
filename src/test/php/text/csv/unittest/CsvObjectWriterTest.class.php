<?php namespace text\csv\unittest;

use io\streams\{MemoryOutputStream, TextWriter};
use text\csv\{CsvFormat, CsvObjectWriter};
use unittest\Test;

class CsvObjectWriterTest extends CsvWriterTest {

  /**
   * Creates a new CSV writer fixture
   *
   * @param  io.streams.OutputStream $stream
   * @param  text.csv.CsvFormat $format
   * @return text.csv.CsvWriter
   */
  protected function newFixture($stream, $format= null) {
    return new CsvObjectWriter(new TextWriter($stream), $format);
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

  #[Test]
  public function writeAddress() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(new Address('Timm', 'Karlsruhe', '76137'));

    $this->assertEquals("Timm;Karlsruhe;76137\n", $out->bytes());
  }

  #[Test]
  public function writeAddressPartially() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(new Address('Timm', 'Karlsruhe', '76137'), ['city', 'zip']);

    $this->assertEquals("Karlsruhe;76137\n", $out->bytes());
  }
}