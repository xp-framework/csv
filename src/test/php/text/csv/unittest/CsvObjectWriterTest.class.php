<?php namespace text\csv\unittest;

use io\streams\MemoryOutputStream;
use text\csv\{CsvFormat, CsvObjectWriter};
use test\Assert;
use test\Test;

class CsvObjectWriterTest extends CsvWriterTest {

  /**
   * Creates a new CSV writer fixture
   *
   * @param  io.streams.Writer|io.streams.OutputStream|io.Channel|string $out
   * @param  text.csv.CsvFormat $format
   * @return text.csv.CsvWriter
   */
  protected function newFixture($out, $format= null) {
    return new CsvObjectWriter($out, $format);
  }

  #[Test]
  public function writePerson() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(new Person(1549, 'Timm', 'friebe@example.com'));

    Assert::equals("1549;Timm;friebe@example.com\n", $out->bytes());
  }

  #[Test]
  public function writePersonReSorted() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(new Person(1549, 'Timm', 'friebe@example.com'), ['email', 'id', 'name']);

    Assert::equals("friebe@example.com;1549;Timm\n", $out->bytes());
  }

  #[Test]
  public function writePersonPartially() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(new Person(1549, 'Timm', 'friebe@example.com'), ['id', 'name']);

    Assert::equals("1549;Timm\n", $out->bytes());
  }

  #[Test]
  public function writeAddress() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(new Address('Timm', 'Karlsruhe', '76137'));

    Assert::equals("Timm;Karlsruhe;76137\n", $out->bytes());
  }

  #[Test]
  public function writeAddressPartially() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(new Address('Timm', 'Karlsruhe', '76137'), ['city', 'zip']);

    Assert::equals("Karlsruhe;76137\n", $out->bytes());
  }
}