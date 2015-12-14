<?php namespace text\csv\unittest;

use lang\IllegalStateException;
use unittest\TestCase;
use text\csv\CsvListWriter;
use text\csv\CsvFormat;
use text\csv\processors\FormatDate;
use io\streams\TextWriter;
use io\streams\MemoryOutputStream;

/**
 * TestCase
 *
 * @see      xp://text.csv.CsvListWriter
 */
class CsvListWriterTest extends TestCase {
  protected $out= null;

  /**
   * Creates a new list writer
   *
   * @param   text.csv.CsvFormat format
   * @return  text.csv.CsvListWriter
   */
  protected function newWriter(CsvFormat $format= null) {
    $this->out= new MemoryOutputStream();
    return new CsvListWriter(new TextWriter($this->out), $format);
  }

  #[@test]
  public function writeLine() {
    $this->newWriter()->write(['Timm', 'Karlsruhe', '76137']);
    $this->assertEquals("Timm;Karlsruhe;76137\n", $this->out->getBytes());
  }

  #[@test]
  public function writeEmptyValue() {
    $this->newWriter()->write(['Timm', '', '76137']);
    $this->assertEquals("Timm;;76137\n", $this->out->getBytes());
  }

  #[@test]
  public function setHeaders() {
    $writer= $this->newWriter();
    $writer->setHeaders(['name', 'city', 'zip']);
    $writer->write(['Timm', 'Karlsruhe', '76137']);
    $this->assertEquals("name;city;zip\nTimm;Karlsruhe;76137\n", $this->out->getBytes());
  }

  #[@test, @expect(IllegalStateException::class)]
  public function cannotSetHeadersAfterWriting() {
    $writer= $this->newWriter();
    $writer->write(['Timm', 'Karlsruhe', '76137']);
    $writer->setHeaders(['name', 'city', 'zip']);
  }

  #[@test]
  public function writeUnixMultiLineValue() {
    $this->newWriter((new CsvFormat())->withQuote("'"))->write(['Timm', "76137\nKarlsruhe\nGermany"]);
    $this->assertEquals("Timm;'76137\nKarlsruhe\nGermany'\n", $this->out->getBytes());
  }

  #[@test]
  public function writeMacMultiLineValue() {
    $this->newWriter((new CsvFormat())->withQuote("'"))->write(['Timm', "76137\rKarlsruhe\rGermany"]);
    $this->assertEquals("Timm;'76137\rKarlsruhe\rGermany'\n", $this->out->getBytes());
  }

  #[@test]
  public function writeWindowsMultiLineValue() {
    $this->newWriter((new CsvFormat())->withQuote("'"))->write(['Timm', "76137\r\nKarlsruhe\r\nGermany"]);
    $this->assertEquals("Timm;'76137\r\nKarlsruhe\r\nGermany'\n", $this->out->getBytes());
  }

  #[@test]
  public function valueWithDelimiterIsQuoted() {
    $this->newWriter((new CsvFormat())->withQuote("'"))->write(['Timm;Friebe', 'Karlsruhe', '76137']);
    $this->assertEquals("'Timm;Friebe';Karlsruhe;76137\n", $this->out->getBytes());
  }

  #[@test]
  public function delimiterIsQuoted() {
    $this->newWriter((new CsvFormat())->withQuote("'"))->write([';', 'Karlsruhe', '76137']);
    $this->assertEquals("';';Karlsruhe;76137\n", $this->out->getBytes());
  }

  #[@test]
  public function quotesAroundValueAreEscaped() {
    $this->newWriter((new CsvFormat())->withQuote("'"))->write(["'Hello'", 'Karlsruhe', '76137']);
    $this->assertEquals("'''Hello''';Karlsruhe;76137\n", $this->out->getBytes());
  }

  #[@test]
  public function quotesInsideValueAreEscaped() {
    $this->newWriter((new CsvFormat())->withQuote("'"))->write(["He said 'Hello' to me", 'Karlsruhe', '76137']);
    $this->assertEquals("'He said ''Hello'' to me';Karlsruhe;76137\n", $this->out->getBytes());
  }

  #[@test]
  public function quotesAroundEmptyAreEscaped() {
    $this->newWriter((new CsvFormat())->withQuote("'"))->write(["''", 'Karlsruhe', '76137']);
    $this->assertEquals("'''''';Karlsruhe;76137\n", $this->out->getBytes());
  }

  #[@test]
  public function writeLineFromMap() {
    $this->newWriter()->write(['name' => 'Timm', 'city' => 'Karlsruhe', 'zip' => '76137']);
    $this->assertEquals("Timm;Karlsruhe;76137\n", $this->out->getBytes());
  }

  #[@test]
  public function writeLineFromMapWithProcessor() {
    $writer= $this->newWriter();
    $writer->setProcessor(1, new FormatDate('d.m.Y'));
    $writer->write(['id' => 1, 'date' => new \util\Date('2012-02-10')]);
    $this->assertEquals("1;10.02.2012\n", $this->out->getBytes());
  }
}
