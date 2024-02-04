<?php namespace text\csv\unittest;

use io\streams\MemoryOutputStream;
use lang\IllegalStateException;
use test\Assert;
use test\{Expect, Test};
use text\csv\{CsvFormat, CsvListWriter};

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

    Assert::equals("Timm;Karlsruhe;76137\n", $out->bytes());
  }

  #[Test]
  public function write_emptyValue() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(['Timm', '', '76137']);

    Assert::equals("Timm;;76137\n", $out->bytes());
  }

  #[Test]
  public function set_headers() {
    $out= new MemoryOutputStream();
    $writer= $this->newFixture($out);
    $writer->setHeaders(['name', 'city', 'zip']);
    $writer->write(['Timm', 'Karlsruhe', '76137']);

    Assert::equals("name;city;zip\nTimm;Karlsruhe;76137\n", $out->bytes());
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
    Assert::equals("Timm;'76137\nKarlsruhe\nGermany'\n", $out->bytes());
  }

  #[Test]
  public function write_mac_multi_line_value() {
    $out= new MemoryOutputStream();
    $this->newFixture($out, (new CsvFormat())->withQuote("'"))->write(['Timm', "76137\rKarlsruhe\rGermany"]);
    Assert::equals("Timm;'76137\rKarlsruhe\rGermany'\n", $out->bytes());
  }

  #[Test]
  public function write_windows_multiline_value() {
    $out= new MemoryOutputStream();
    $this->newFixture($out, (new CsvFormat())->withQuote("'"))->write(['Timm', "76137\r\nKarlsruhe\r\nGermany"]);
    Assert::equals("Timm;'76137\r\nKarlsruhe\r\nGermany'\n", $out->bytes());
  }

  #[Test]
  public function value_with_delimiter_is_quoted() {
    $out= new MemoryOutputStream();
    $this->newFixture($out, (new CsvFormat())->withQuote("'"))->write(['Timm;Friebe', 'Karlsruhe', '76137']);
    Assert::equals("'Timm;Friebe';Karlsruhe;76137\n", $out->bytes());
  }

  #[Test]
  public function delimiter_is_quoted() {
    $out= new MemoryOutputStream();
    $this->newFixture($out, (new CsvFormat())->withQuote("'"))->write([';', 'Karlsruhe', '76137']);
    Assert::equals("';';Karlsruhe;76137\n", $out->bytes());
  }

  #[Test]
  public function quotes_around_value_are_escaped() {
    $out= new MemoryOutputStream();
    $this->newFixture($out, (new CsvFormat())->withQuote("'"))->write(["'Hello'", 'Karlsruhe', '76137']);
    Assert::equals("'''Hello''';Karlsruhe;76137\n", $out->bytes());
  }

  #[Test]
  public function quotes_inside_value_are_escaped() {
    $out= new MemoryOutputStream();
    $this->newFixture($out, (new CsvFormat())->withQuote("'"))->write(["He said 'Hello' to me", 'Karlsruhe', '76137']);
    Assert::equals("'He said ''Hello'' to me';Karlsruhe;76137\n", $out->bytes());
  }

  #[Test]
  public function quotes_around_empty_are_escaped() {
    $out= new MemoryOutputStream();
    $this->newFixture($out, (new CsvFormat())->withQuote("'"))->write(["''", 'Karlsruhe', '76137']);
    Assert::equals("'''''';Karlsruhe;76137\n", $out->bytes());
  }

  #[Test]
  public function write_line_from_map() {
    $out= new MemoryOutputStream();
    $this->newFixture($out)->write(['name' => 'Timm', 'city' => 'Karlsruhe', 'zip' => '76137']);

    Assert::equals("Timm;Karlsruhe;76137\n", $out->bytes());
  }
}