<?php namespace text\csv\unittest;

use io\streams\MemoryOutputStream;
use lang\IllegalStateException;
use text\csv\processors\FormatDate;
use text\csv\{CsvFormat, CsvListWriter};
use unittest\{Expect, Test};

class CsvListWriterTest extends CsvWriterTest {

  /**
   * Creates a new CSV writer fixture
   *
   * @param  io.streams.Writer|io.streams.OutputStream|io.Channel|string $out
   * @param  text.csv.CsvFormat $format
   * @return text.csv.CsvWriter
   */
  protected function newFixture($out, $format= null) {
    return new CsvListWriter($out, $format);
  }

  #[Test]
  public function write_line() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(['Timm', 'Karlsruhe', '76137']);

    $this->assertEquals("Timm;Karlsruhe;76137\n", $out->bytes());
  }

  #[Test]
  public function write_emptyValue() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(['Timm', '', '76137']);

    $this->assertEquals("Timm;;76137\n", $out->bytes());
  }

  #[Test]
  public function set_headers() {
    $out= new MemoryOutputStream();
    $writer= $this->newFixture($out);
    $writer->setHeaders(['name', 'city', 'zip']);
    $writer->write(['Timm', 'Karlsruhe', '76137']);

    $this->assertEquals("name;city;zip\nTimm;Karlsruhe;76137\n", $out->bytes());
  }

  #[Test, Expect(IllegalStateException::class)]
  public function cannot_set_headers_after_writing() {
    $out= new MemoryOutputStream();
    $writer= $this->newFixture($out);
    $writer->write(['Timm', 'Karlsruhe', '76137']);
    $writer->setHeaders(['name', 'city', 'zip']);
  }

  #[Test]
  public function write_unix_multi_line_value() {
    $out= new MemoryOutputStream();
    $this->newFixture($out, (new CsvFormat())->withQuote("'"))->write(['Timm', "76137\nKarlsruhe\nGermany"]);
    $this->assertEquals("Timm;'76137\nKarlsruhe\nGermany'\n", $out->bytes());
  }

  #[Test]
  public function write_mac_multi_line_value() {
    $out= new MemoryOutputStream();
    $this->newFixture($out, (new CsvFormat())->withQuote("'"))->write(['Timm', "76137\rKarlsruhe\rGermany"]);
    $this->assertEquals("Timm;'76137\rKarlsruhe\rGermany'\n", $out->bytes());
  }

  #[Test]
  public function write_windows_multiline_value() {
    $out= new MemoryOutputStream();
    $this->newFixture($out, (new CsvFormat())->withQuote("'"))->write(['Timm', "76137\r\nKarlsruhe\r\nGermany"]);
    $this->assertEquals("Timm;'76137\r\nKarlsruhe\r\nGermany'\n", $out->bytes());
  }

  #[Test]
  public function value_with_delimiter_is_quoted() {
    $out= new MemoryOutputStream();
    $this->newFixture($out, (new CsvFormat())->withQuote("'"))->write(['Timm;Friebe', 'Karlsruhe', '76137']);
    $this->assertEquals("'Timm;Friebe';Karlsruhe;76137\n", $out->bytes());
  }

  #[Test]
  public function delimiter_is_quoted() {
    $out= new MemoryOutputStream();
    $this->newFixture($out, (new CsvFormat())->withQuote("'"))->write([';', 'Karlsruhe', '76137']);
    $this->assertEquals("';';Karlsruhe;76137\n", $out->bytes());
  }

  #[Test]
  public function quotes_around_value_are_escaped() {
    $out= new MemoryOutputStream();
    $this->newFixture($out, (new CsvFormat())->withQuote("'"))->write(["'Hello'", 'Karlsruhe', '76137']);
    $this->assertEquals("'''Hello''';Karlsruhe;76137\n", $out->bytes());
  }

  #[Test]
  public function quotes_inside_value_are_escaped() {
    $out= new MemoryOutputStream();
    $this->newFixture($out, (new CsvFormat())->withQuote("'"))->write(["He said 'Hello' to me", 'Karlsruhe', '76137']);
    $this->assertEquals("'He said ''Hello'' to me';Karlsruhe;76137\n", $out->bytes());
  }

  #[Test]
  public function quotes_around_empty_are_escaped() {
    $out= new MemoryOutputStream();
    $this->newFixture($out, (new CsvFormat())->withQuote("'"))->write(["''", 'Karlsruhe', '76137']);
    $this->assertEquals("'''''';Karlsruhe;76137\n", $out->bytes());
  }

  #[Test]
  public function write_line_from_map() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(['name' => 'Timm', 'city' => 'Karlsruhe', 'zip' => '76137']);

    $this->assertEquals("Timm;Karlsruhe;76137\n", $out->bytes());
  }

  #[Test]
  public function write_line_from_map_with_processor() {
    $out= new MemoryOutputStream();
    $writer= $this->newFixture($out);
    $writer->setProcessor(1, new FormatDate('d.m.Y'));
    $writer->write(['id' => 1, 'date' => new \util\Date('2012-02-10')]);

    $this->assertEquals("1;10.02.2012\n", $out->bytes());
  }
}