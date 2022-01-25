<?php namespace text\csv\unittest;

use io\streams\{MemoryInputStream, TextReader};
use text\csv\CsvMapReader;
use unittest\{Test, TestCase, Values};

class CsvMapReaderTest extends CsvReaderTest {

  /**
   * Creates a new CSV reader fixture
   *
   * @param  io.streams.InputStream $stream
   * @param  text.csv.CsvFormat $format
   * @return text.csv.CsvReader
   */
  protected function newFixture($stream, $format= null) {
    return new CsvMapReader(new TextReader($stream), [], $format);
  }

  #[Test]
  public function set_keys() {
    with ($keys= ['id', 'name', 'email']); {
      $in= $this->newFixture(new MemoryInputStream(''));
      $in->setKeys($keys);
      $this->assertEquals($keys, $in->getKeys());
    }
  }

  #[Test]
  public function with_keys() {
    with ($keys= ['id', 'name', 'email']); {
      $in= $this->newFixture(new MemoryInputStream(''));
      $this->assertEquals($in, $in->withKeys($keys));
      $this->assertEquals($keys, $in->getKeys());
    }
  }

  #[Test]
  public function read_record() {
    $in= $this->newFixture(new MemoryInputStream('1549;Timm;friebe@example.com'))->withKeys(['id', 'name', 'email']);
    $this->assertEquals(
      ['id' => '1549', 'name' => 'Timm', 'email' => 'friebe@example.com'],
      $in->read()
    );
  }

  #[Test]
  public function read_record_with_headers() {
    $in= $this->newFixture(new MemoryInputStream("id;name;email\n1549;Timm;friebe@example.com"));
    $in->setKeys($in->getHeaders());
    $this->assertEquals(
      ['id' => '1549', 'name' => 'Timm', 'email' => 'friebe@example.com'],
      $in->read()
    );
  }

  #[Test, Values(["", "\n", "\n\n"])]
  public function read_empty($input) {
    $in= $this->newFixture(new MemoryInputStream($input))->withKeys(['id', 'name', 'email']);
    $this->assertNull($in->read());
  }

  #[Test]
  public function read_record_with_excess() {
    $in= $this->newFixture(new MemoryInputStream('1549;Timm;friebe@example.com;WILL_NOT_APPEAR'))->withKeys(['id', 'name', 'email']);
    $this->assertEquals(
      ['id' => '1549', 'name' => 'Timm', 'email' => 'friebe@example.com'],
      $in->read()
    );
  }

  #[Test]
  public function read_record_with_underrun() {
    $in= $this->newFixture(new MemoryInputStream('1549;Timm'))->withKeys(['id', 'name', 'email']);
    $this->assertEquals(
      ['id' => '1549', 'name' => 'Timm', 'email' => null],
      $in->read()
    );
  }

  #[Test]
  public function read_record_after_empty_line() {
    $in= $this->newFixture(new MemoryInputStream("\n1549;Timm"))->withKeys(['id', 'name']);
    $this->assertEquals(
      ['id' => '1549', 'name' => 'Timm'],
      $in->read()
    );
  }

  #[Test]
  public function read_records_with_empty_line_in_between() {
    $in= $this->newFixture(new MemoryInputStream("1549;Timm\n1552;Alex"))->withKeys(['id', 'name']);
    $this->assertEquals(
      [['id' => '1549', 'name' => 'Timm'], ['id' => '1552', 'name' => 'Alex']],
      [$in->read(), $in->read()]
    );
  }
}