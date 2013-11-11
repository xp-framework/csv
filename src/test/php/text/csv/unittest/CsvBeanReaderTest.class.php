<?php namespace text\csv\unittest;

use unittest\TestCase;
use text\csv\CsvBeanReader;
use io\streams\MemoryInputStream;


/**
 * TestCase
 *
 * @see      xp://text.csv.CsvBreanReader
 */
class CsvBeanReaderTest extends TestCase {

  /**
   * Creates a new object reader
   *
   * @param   string str
   * @param   lang.XPClass class
   * @return  text.csv.CsvBeanReader
   */
  protected function newReader($str, \lang\XPClass $class) {
    return new CsvBeanReader(new \io\streams\TextReader(new MemoryInputStream($str)), $class);
  }

  /**
   * Test
   *
   */
  #[@test]
  public function readPerson() {
    $in= $this->newReader('1549;Timm;friebe@example.com', \lang\XPClass::forName('text.csv.unittest.Person'));
    $this->assertEquals(
      new Person('1549', 'Timm', 'friebe@example.com'), 
      $in->read(array('id', 'name', 'email'))
    );
  }

  /**
   * Test
   *
   */
  #[@test]
  public function readPersonReSorted() {
    $in= $this->newReader('friebe@example.com;1549;Timm', \lang\XPClass::forName('text.csv.unittest.Person'));
    $this->assertEquals(
      new Person('1549', 'Timm', 'friebe@example.com'), 
      $in->read(array('email', 'id', 'name'))
    );
  }

  /**
   * Test
   *
   */
  #[@test]
  public function readPersonCompletely() {
    $in= $this->newReader('1549;Timm;friebe@example.com', \lang\XPClass::forName('text.csv.unittest.Person'));
    $this->assertEquals(
      new Person('1549', 'Timm', 'friebe@example.com'), 
      $in->read()
    );
  }

  /**
   * Test
   *
   */
  #[@test]
  public function readPersonPartially() {
    $in= $this->newReader('1549;Timm;friebe@example.com', \lang\XPClass::forName('text.csv.unittest.Person'));
    $this->assertEquals(
      new Person('1549', 'Timm', ''), 
      $in->read(array('id', 'name'))
    );
  }

  /**
   * Test
   *
   */
  #[@test]
  public function readEmpty() {
    $in= $this->newReader('', \lang\XPClass::forName('text.csv.unittest.Person'));
    $this->assertNull($in->read(array('id', 'name', 'email')));
  }
}
