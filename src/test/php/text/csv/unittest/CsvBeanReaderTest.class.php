<?php namespace text\csv\unittest;

use text\csv\CsvBeanReader;
use io\streams\MemoryInputStream;
use io\streams\TextReader;
use lang\XPClass;

/**
 * TestCase
 *
 * @see      xp://text.csv.CsvBreanReader
 */
class CsvBeanReaderTest extends \unittest\TestCase {

  /**
   * Creates a new object reader
   *
   * @param   string str
   * @param   lang.XPClass class
   * @return  text.csv.CsvBeanReader
   */
  protected function newReader($str, XPClass $class) {
    return new CsvBeanReader(new TextReader(new MemoryInputStream($str)), $class);
  }

  #[@test]
  public function readPerson() {
    $in= $this->newReader('1549;Timm;friebe@example.com', XPClass::forName('text.csv.unittest.Person'));
    $this->assertEquals(
      new Person('1549', 'Timm', 'friebe@example.com'), 
      $in->read(['id', 'name', 'email'])
    );
  }

  #[@test]
  public function readPersonReSorted() {
    $in= $this->newReader('friebe@example.com;1549;Timm', XPClass::forName('text.csv.unittest.Person'));
    $this->assertEquals(
      new Person('1549', 'Timm', 'friebe@example.com'), 
      $in->read(['email', 'id', 'name'])
    );
  }

  #[@test]
  public function readPersonCompletely() {
    $in= $this->newReader('1549;Timm;friebe@example.com', XPClass::forName('text.csv.unittest.Person'));
    $this->assertEquals(
      new Person('1549', 'Timm', 'friebe@example.com'), 
      $in->read()
    );
  }

  #[@test]
  public function readPersonPartially() {
    $in= $this->newReader('1549;Timm;friebe@example.com', XPClass::forName('text.csv.unittest.Person'));
    $this->assertEquals(
      new Person('1549', 'Timm', ''), 
      $in->read(['id', 'name'])
    );
  }

  #[@test]
  public function readEmpty() {
    $in= $this->newReader('', XPClass::forName('text.csv.unittest.Person'));
    $this->assertNull($in->read(['id', 'name', 'email']));
  }
}
