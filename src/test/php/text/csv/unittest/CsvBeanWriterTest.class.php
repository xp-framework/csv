<?php namespace text\csv\unittest;

use unittest\TestCase;
use text\csv\CsvBeanWriter;
use text\csv\CsvFormat;
use io\streams\MemoryOutputStream;
use io\streams\TextWriter;

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

  #[@test]
  public function writePerson() {
    $this->newWriter()->write(new Person(1549, 'Timm', 'friebe@example.com'));
    $this->assertEquals("1549;Timm;friebe@example.com\n", $this->out->getBytes());
  }

  #[@test]
  public function writePersonReSorted() {
    $this->newWriter()->write(new Person(1549, 'Timm', 'friebe@example.com'), array('email', 'id', 'name'));
    $this->assertEquals("friebe@example.com;1549;Timm\n", $this->out->getBytes());
  }

  #[@test]
  public function writePersonPartially() {
    $this->newWriter()->write(new Person(1549, 'Timm', 'friebe@example.com'), array('id', 'name'));
    $this->assertEquals("1549;Timm\n", $this->out->getBytes());
  }
}
