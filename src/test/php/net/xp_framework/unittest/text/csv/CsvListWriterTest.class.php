<?php namespace net\xp_framework\unittest\text\csv;

use unittest\TestCase;
use text\csv\CsvListWriter;
use text\csv\processors\FormatDate;
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
  protected function newWriter(\text\csv\CsvFormat $format= null) {
    $this->out= new MemoryOutputStream();
    return new CsvListWriter(new \io\streams\TextWriter($this->out), $format);
  }

  /**
   * Test writing a single line
   *
   */
  #[@test]
  public function writeLine() {
    $this->newWriter()->write(array('Timm', 'Karlsruhe', '76137'));
    $this->assertEquals("Timm;Karlsruhe;76137\n", $this->out->getBytes());
  }

  /**
   * Test writing an empty value
   *
   */
  #[@test]
  public function writeEmptyValue() {
    $this->newWriter()->write(array('Timm', '', '76137'));
    $this->assertEquals("Timm;;76137\n", $this->out->getBytes());
  }

  /**
   * Test writing headers
   *
   */
  #[@test]
  public function setHeaders() {
    $writer= $this->newWriter();
    $writer->setHeaders(array('name', 'city', 'zip'));
    $writer->write(array('Timm', 'Karlsruhe', '76137'));
    $this->assertEquals("name;city;zip\nTimm;Karlsruhe;76137\n", $this->out->getBytes());
  }

  /**
   * Test writing headers
   *
   */
  #[@test, @expect('lang.IllegalStateException')]
  public function cannotSetHeadersAfterWriting() {
    $writer= $this->newWriter();
    $writer->write(array('Timm', 'Karlsruhe', '76137'));
    $writer->setHeaders(array('name', 'city', 'zip'));
  }

  /**
   * Test writing a multiline value with "\n"
   *
   */
  #[@test]
  public function writeUnixMultiLineValue() {
    $this->newWriter(create(new \text\csv\CsvFormat())->withQuote("'"))->write(array('Timm', "76137\nKarlsruhe\nGermany"));
    $this->assertEquals("Timm;'76137\nKarlsruhe\nGermany'\n", $this->out->getBytes());
  }

  /**
   * Test writing a multiline value with "\r"
   *
   */
  #[@test]
  public function writeMacMultiLineValue() {
    $this->newWriter(create(new \text\csv\CsvFormat())->withQuote("'"))->write(array('Timm', "76137\rKarlsruhe\rGermany"));
    $this->assertEquals("Timm;'76137\rKarlsruhe\rGermany'\n", $this->out->getBytes());
  }

  /**
   * Test writing a multiline value with "\r\n"
   *
   */
  #[@test]
  public function writeWindowsMultiLineValue() {
    $this->newWriter(create(new \text\csv\CsvFormat())->withQuote("'"))->write(array('Timm', "76137\r\nKarlsruhe\r\nGermany"));
    $this->assertEquals("Timm;'76137\r\nKarlsruhe\r\nGermany'\n", $this->out->getBytes());
  }

  /**
   * Test delimiter is quoted
   *
   */
  #[@test]
  public function valueWithDelimiterIsQuoted() {
    $this->newWriter(create(new \text\csv\CsvFormat())->withQuote("'"))->write(array('Timm;Friebe', 'Karlsruhe', '76137'));
    $this->assertEquals("'Timm;Friebe';Karlsruhe;76137\n", $this->out->getBytes());
  }

  /**
   * Test delimiter is quoted
   *
   */
  #[@test]
  public function delimiterIsQuoted() {
    $this->newWriter(create(new \text\csv\CsvFormat())->withQuote("'"))->write(array(';', 'Karlsruhe', '76137'));
    $this->assertEquals("';';Karlsruhe;76137\n", $this->out->getBytes());
  }

  /**
   * Test quotes are escaped
   *
   */
  #[@test]
  public function quotesAroundValueAreEscaped() {
    $this->newWriter(create(new \text\csv\CsvFormat())->withQuote("'"))->write(array("'Hello'", 'Karlsruhe', '76137'));
    $this->assertEquals("'''Hello''';Karlsruhe;76137\n", $this->out->getBytes());
  }

  /**
   * Test quotes are escaped
   *
   */
  #[@test]
  public function quotesInsideValueAreEscaped() {
    $this->newWriter(create(new \text\csv\CsvFormat())->withQuote("'"))->write(array("He said 'Hello' to me", 'Karlsruhe', '76137'));
    $this->assertEquals("'He said ''Hello'' to me';Karlsruhe;76137\n", $this->out->getBytes());
  }

  /**
   * Test quotes are escaped
   *
   */
  #[@test]
  public function quotesAroundEmptyAreEscaped() {
    $this->newWriter(create(new \text\csv\CsvFormat())->withQuote("'"))->write(array("''", 'Karlsruhe', '76137'));
    $this->assertEquals("'''''';Karlsruhe;76137\n", $this->out->getBytes());
  }

  /**
   * Test writing a single line
   *
   */
  #[@test]
  public function writeLineFromMap() {
    $this->newWriter()->write(array('name' => 'Timm', 'city' => 'Karlsruhe', 'zip' => '76137'));
    $this->assertEquals("Timm;Karlsruhe;76137\n", $this->out->getBytes());
  }

  /**
   * Test writing a single line
   *
   */
  #[@test]
  public function writeLineFromMapWithProcessor() {
    $writer= $this->newWriter();
    $writer->setProcessor(1, new FormatDate('d.m.Y'));
    $writer->write(array('id' => 1, 'date' => new \util\Date('2012-02-10')));
    $this->assertEquals("1;10.02.2012\n", $this->out->getBytes());
  }
}
