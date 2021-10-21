<?php namespace text\csv\unittest;

use io\streams\{MemoryOutputStream, TextWriter};
use text\csv\{CsvBeanWriter, CsvFormat};
use unittest\{Test, TestCase};

/**
 * TestCase
 *
 * @see      xp://text.csv.CsvBeanWriter
 */
class CsvBeanWriterTest extends TestCase {
  protected $out= null;

  /**
   * Creates a new bean writer
   *
   * @param   text.csv.CsvFormat format
   * @return  text.csv.CsvBeanWriter
   */
  protected function newWriter(CsvFormat $format= null) {
    $this->out= new MemoryOutputStream();
    return new CsvBeanWriter(new TextWriter($this->out), $format);
  }

  #[Test]
  public function writePerson() {
    $this->newWriter()->write(new Person(1549, 'Timm', 'friebe@example.com'));
    $this->assertEquals("1549;Timm;friebe@example.com\n", $this->out->bytes());
  }

  #[Test]
  public function writePersonReSorted() {
    $this->newWriter()->write(new Person(1549, 'Timm', 'friebe@example.com'), ['email', 'id', 'name']);
    $this->assertEquals("friebe@example.com;1549;Timm\n", $this->out->bytes());
  }

  #[Test]
  public function writePersonPartially() {
    $this->newWriter()->write(new Person(1549, 'Timm', 'friebe@example.com'), ['id', 'name']);
    $this->assertEquals("1549;Timm\n", $this->out->bytes());
  }
}