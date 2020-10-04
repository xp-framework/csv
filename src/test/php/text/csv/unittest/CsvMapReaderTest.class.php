<?php namespace text\csv\unittest;

use io\streams\{MemoryInputStream, TextReader};
use text\csv\CsvMapReader;
use unittest\{Test, TestCase, Values};

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

  #[Test]
  public function setKeys() {
    with ($keys= ['id', 'name', 'email']); {
      $in= $this->newReader('');
      $in->setKeys($keys);
      $this->assertEquals($keys, $in->getKeys());
    }
  }

  #[Test]
  public function withKeys() {
    with ($keys= ['id', 'name', 'email']); {
      $in= $this->newReader('');
      $this->assertEquals($in, $in->withKeys($keys));
      $this->assertEquals($keys, $in->getKeys());
    }
  }

  #[Test]
  public function readRecord() {
    $in= $this->newReader('1549;Timm;friebe@example.com', ['id', 'name', 'email']);
    $this->assertEquals(
      ['id' => '1549', 'name' => 'Timm', 'email' => 'friebe@example.com'],
      $in->read()
    );
  }

  #[Test]
  public function readRecordWithHeaders() {
    $in= $this->newReader("id;name;email\n1549;Timm;friebe@example.com");
    $in->setKeys($in->getHeaders());
    $this->assertEquals(
      ['id' => '1549', 'name' => 'Timm', 'email' => 'friebe@example.com'],
      $in->read()
    );
  }

  #[Test, Values(["", "\n", "\n\n"])]
  public function readEmpty($input) {
    $in= $this->newReader($input, ['id', 'name', 'email']);
    $this->assertNull($in->read());
  }

  #[Test]
  public function readRecordWithExcess() {
    $in= $this->newReader('1549;Timm;friebe@example.com;WILL_NOT_APPEAR', ['id', 'name', 'email']);
    $this->assertEquals(
      ['id' => '1549', 'name' => 'Timm', 'email' => 'friebe@example.com'],
      $in->read()
    );
  }

  #[Test]
  public function readRecordWithUnderrun() {
    $in= $this->newReader('1549;Timm', ['id', 'name', 'email']);
    $this->assertEquals(
      ['id' => '1549', 'name' => 'Timm', 'email' => null],
      $in->read()
    );
  }

  #[Test]
  public function readRecordAfterEmptyLine() {
    $in= $this->newReader("\n1549;Timm", ['id', 'name']);
    $this->assertEquals(
      ['id' => '1549', 'name' => 'Timm'],
      $in->read()
    );
  }

  #[Test]
  public function readRecordsWithEmptyLineInBetween() {
    $in= $this->newReader("1549;Timm\n1552;Alex", ['id', 'name']);
    $this->assertEquals(
      [['id' => '1549', 'name' => 'Timm'], ['id' => '1552', 'name' => 'Alex']],
      [$in->read(), $in->read()]
    );
  }
}