<?php namespace text\csv\unittest;

use io\streams\MemoryInputStream;
use text\csv\CsvObjectReader;
use unittest\{Test, TestCase};

/**
 * TestCase
 *
 * @see      xp://text.csv.CsvObjectReader
 */
class CsvObjectReaderTest extends TestCase {

  /**
   * Creates a new object reader
   *
   * @param   string str
   * @param   lang.XPClass class
   * @return  text.csv.CsvObjectReader
   */
  protected function newReader($str, \lang\XPClass $class) {
    return new CsvObjectReader(new \io\streams\TextReader(new MemoryInputStream($str)), $class);
  }

  #[Test]
  public function readAddress() {
    $in= $this->newReader('Timm;Karlsruhe;76137', \lang\XPClass::forName('text.csv.unittest.Address'));
    $this->assertEquals(
      new Address('Timm', 'Karlsruhe', '76137'), 
      $in->read(['name', 'city', 'zip'])
    );
  }

  #[Test]
  public function readPerson() {
    $in= $this->newReader('1549;Timm;friebe@example.com', \lang\XPClass::forName('text.csv.unittest.Person'));
    $this->assertEquals(
      new Person('1549', 'Timm', 'friebe@example.com'), 
      $in->read(['id', 'name', 'email'])
    );
  }

  #[Test]
  public function readPersonReSorted() {
    $in= $this->newReader('friebe@example.com;1549;Timm', \lang\XPClass::forName('text.csv.unittest.Person'));
    $this->assertEquals(
      new Person('1549', 'Timm', 'friebe@example.com'), 
      $in->read(['email', 'id', 'name'])
    );
  }

  #[Test]
  public function readPersonCompletely() {
    $in= $this->newReader('1549;Timm;friebe@example.com', \lang\XPClass::forName('text.csv.unittest.Person'));
    $this->assertEquals(
      new Person('1549', 'Timm', 'friebe@example.com'), 
      $in->read()
    );
  }

  #[Test]
  public function readPersonPartially() {
    $in= $this->newReader('1549;Timm;friebe@example.com', \lang\XPClass::forName('text.csv.unittest.Person'));
    $this->assertEquals(
      new Person('1549', 'Timm', ''), 
      $in->read(['id', 'name'])
    );
  }

  #[Test]
  public function readEmpty() {
    $in= $this->newReader('', \lang\XPClass::forName('text.csv.unittest.Address'));
    $this->assertNull($in->read(['name', 'city', 'zip']));
  }
}