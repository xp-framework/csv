<?php namespace text\csv\unittest;

use io\streams\{MemoryOutputStream, TextWriter};
use text\csv\{CsvFormat, CsvObjectWriter};
use unittest\{Test, TestCase};

/**
 * TestCase
 *
 * @see      xp://text.csv.CsvObjectWriter
 */
class CsvObjectWriterTest extends TestCase {
  protected $out= null;

  /**
   * Creates a new object writer
   *
   * @param   text.csv.CsvFormat format
   * @return  text.csv.CsvObjectWriter
   */
  protected function newWriter(CsvFormat $format= null) {
    $this->out= new MemoryOutputStream();
    return new CsvObjectWriter(new TextWriter($this->out), $format);
  }

  #[Test]
  public function writePerson() {
    $this->newWriter()->write(new Person(1549, 'Timm', 'friebe@example.com'));
    $this->assertEquals("1549;Timm;friebe@example.com\n", $this->out->getBytes());
  }

  #[Test]
  public function writePersonReSorted() {
    $this->newWriter()->write(new Person(1549, 'Timm', 'friebe@example.com'), ['email', 'id', 'name']);
    $this->assertEquals("friebe@example.com;1549;Timm\n", $this->out->getBytes());
  }

  #[Test]
  public function writePersonPartially() {
    $this->newWriter()->write(new Person(1549, 'Timm', 'friebe@example.com'), ['id', 'name']);
    $this->assertEquals("1549;Timm\n", $this->out->getBytes());
  }

  #[Test]
  public function writeAddress() {
    $this->newWriter()->write(new Address('Timm', 'Karlsruhe', '76137'));
    $this->assertEquals("Timm;Karlsruhe;76137\n", $this->out->getBytes());
  }

  #[Test]
  public function writeAddressPartially() {
    $this->newWriter()->write(new Address('Timm', 'Karlsruhe', '76137'), ['city', 'zip']);
    $this->assertEquals("Karlsruhe;76137\n", $this->out->getBytes());
  }
}