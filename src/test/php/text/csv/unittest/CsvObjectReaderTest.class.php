<?php namespace text\csv\unittest;

use io\streams\{MemoryInputStream, TextReader};
use lang\XPClass;
use text\csv\CsvObjectReader;
use unittest\Test;

class CsvObjectReaderTest extends CsvReaderTest {

  /**
   * Creates a new CSV reader fixture
   *
   * @param  io.streams.Reader|io.streams.InputStream|io.Channel|string $in
   * @param  text.csv.CsvFormat $format
   * @return text.csv.CsvReader
   */
  protected function newFixture($in, $format= null) {
    return new CsvObjectReader($in, new XPClass(Person::class), $format);
  }

  #[Test]
  public function read_person() {
    $in= $this->newFixture(new MemoryInputStream('1549;Timm;friebe@example.com'));
    $this->assertEquals(
      new Person('1549', 'Timm', 'friebe@example.com'), 
      $in->read(['id', 'name', 'email'])
    );
  }

  #[Test]
  public function read_person_resorted() {
    $in= $this->newFixture(new MemoryInputStream('friebe@example.com;1549;Timm'));
    $this->assertEquals(
      new Person('1549', 'Timm', 'friebe@example.com'), 
      $in->read(['email', 'id', 'name'])
    );
  }

  #[Test]
  public function read_person_completely() {
    $in= $this->newFixture(new MemoryInputStream('1549;Timm;friebe@example.com'));
    $this->assertEquals(
      new Person('1549', 'Timm', 'friebe@example.com'), 
      $in->read()
    );
  }

  #[Test]
  public function read_person_partially() {
    $in= $this->newFixture(new MemoryInputStream('1549;Timm;friebe@example.com'));
    $this->assertEquals(
      new Person('1549', 'Timm', ''), 
      $in->read(['id', 'name'])
    );
  }

  #[Test]
  public function read_empty() {
    $in= $this->newFixture(new MemoryInputStream(''));
    $this->assertNull($in->read(['id', 'name', 'email']));
  }
}