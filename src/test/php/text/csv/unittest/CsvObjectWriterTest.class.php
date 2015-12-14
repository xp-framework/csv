<?php namespace text\csv\unittest;

use unittest\TestCase;
use text\csv\CsvObjectWriter;
use text\csv\CsvFormat;
use io\streams\MemoryOutputStream;
use io\streams\TextWriter;

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

  #[@test]
  public function writePerson() {
    $this->newWriter()->write(new Person(1549, 'Timm', 'friebe@example.com'));
    $this->assertEquals("1549;Timm;friebe@example.com\n", $this->out->getBytes());
  }

  #[@test]
  public function writePersonReSorted() {
    $this->newWriter()->write(new Person(1549, 'Timm', 'friebe@example.com'), ['email', 'id', 'name']);
    $this->assertEquals("friebe@example.com;1549;Timm\n", $this->out->getBytes());
  }

  #[@test]
  public function writePersonPartially() {
    $this->newWriter()->write(new Person(1549, 'Timm', 'friebe@example.com'), ['id', 'name']);
    $this->assertEquals("1549;Timm\n", $this->out->getBytes());
  }

  #[@test]
  public function writeAddress() {
    $this->newWriter()->write(new Address('Timm', 'Karlsruhe', '76137'));
    $this->assertEquals("Timm;Karlsruhe;76137\n", $this->out->getBytes());
  }

  #[@test]
  public function writeAddressPartially() {
    $this->newWriter()->write(new Address('Timm', 'Karlsruhe', '76137'), ['city', 'zip']);
    $this->assertEquals("Karlsruhe;76137\n", $this->out->getBytes());
  }
}
