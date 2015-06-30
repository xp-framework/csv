<?php namespace text\csv\unittest;

use unittest\TestCase;
use text\csv\CsvMapReader;
use io\streams\MemoryInputStream;
use io\streams\TextReader;

/**
 * TestCase
 *
 * @see      xp://text.csv.CsvMapReader
 */
class CsvMapReaderTest extends TestCase {

  /**
   * Creates a new object reader
   *
   * @param   string $str
   * @param   string[] $keys
   * @return  text.csv.CsvMapReader
   */
  protected function newReader($str, array $keys= []) {
    return new CsvMapReader(new TextReader(new MemoryInputStream($str)), $keys);
  }

  #[@test]
  public function setKeys() {
    with ($keys= ['id', 'name', 'email']); {
      $in= $this->newReader('');
      $in->setKeys($keys);
      $this->assertEquals($keys, $in->getKeys());
    }
  }

  #[@test]
  public function withKeys() {
    with ($keys= ['id', 'name', 'email']); {
      $in= $this->newReader('');
      $this->assertEquals($in, $in->withKeys($keys));
      $this->assertEquals($keys, $in->getKeys());
    }
  }

  #[@test]
  public function readRecord() {
    $in= $this->newReader('1549;Timm;friebe@example.com', ['id', 'name', 'email']);
    $this->assertEquals(
      ['id' => '1549', 'name' => 'Timm', 'email' => 'friebe@example.com'],
      $in->read()
    );
  }

  #[@test]
  public function readRecordWithHeaders() {
    $in= $this->newReader("id;name;email\n1549;Timm;friebe@example.com");
    $in->setKeys($in->getHeaders());
    $this->assertEquals(
      ['id' => '1549', 'name' => 'Timm', 'email' => 'friebe@example.com'],
      $in->read()
    );
  }

  #[@test]
  public function readEmpty() {
    $in= $this->newReader('', ['id', 'name', 'email']);
    $this->assertNull($in->read());
  }

  #[@test]
  public function readRecordWithExcess() {
    $in= $this->newReader('1549;Timm;friebe@example.com;WILL_NOT_APPEAR', ['id', 'name', 'email']);
    $this->assertEquals(
      ['id' => '1549', 'name' => 'Timm', 'email' => 'friebe@example.com'],
      $in->read()
    );
  }

  #[@test]
  public function readRecordWithUnderrun() {
    $in= $this->newReader('1549;Timm', ['id', 'name', 'email']);
    $this->assertEquals(
      ['id' => '1549', 'name' => 'Timm', 'email' => null],
      $in->read()
    );
  }
}
