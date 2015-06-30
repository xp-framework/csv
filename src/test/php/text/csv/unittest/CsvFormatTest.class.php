<?php namespace text\csv\unittest;

use text\csv\CsvFormat;
use text\csv\Quoting;

/**
 * TestCase
 *
 * @see      xp://text.csv.CsvFormat
 */
class CsvFormatTest extends \unittest\TestCase {

  #[@test]
  public function defaultFormat() {
    $format= CsvFormat::$DEFAULT;
    $this->assertEquals(';', $format->getDelimiter());
    $this->assertEquals('"', $format->getQuote());
  }

  #[@test]
  public function pipesFormat() {
    $format= CsvFormat::$PIPES;
    $this->assertEquals('|', $format->getDelimiter());
    $this->assertEquals('"', $format->getQuote());
  }

  #[@test]
  public function commasFormat() {
    $format= CsvFormat::$COMMAS;
    $this->assertEquals(',', $format->getDelimiter());
    $this->assertEquals('"', $format->getQuote());
  }

  #[@test]
  public function tabsFormat() {
    $format= CsvFormat::$TABS;
    $this->assertEquals("\t", $format->getDelimiter());
    $this->assertEquals('"', $format->getQuote());
  }

  #[@test]
  public function quoteAccessors() {
    $format= new CsvFormat();
    $format->setQuote('`');
    $this->assertEquals('`', $format->getQuote());
  }

  #[@test]
  public function withQuoteAccessor() {
    $format= new CsvFormat();
    $this->assertEquals($format, $format->withQuote('`'));
    $this->assertEquals('`', $format->getQuote());
  }

  #[@test]
  public function delimiterAccessors() {
    $format= new CsvFormat();
    $format->setDelimiter(' ');
    $this->assertEquals(' ', $format->getDelimiter());
  }

  #[@test]
  public function withDelimiterAccessor() {
    $format= new CsvFormat();
    $this->assertEquals($format, $format->withDelimiter(' '));
    $this->assertEquals(' ', $format->getDelimiter());
  }

  #[@test]
  public function quotingAccessors() {
    $format= new CsvFormat();
    $format->setQuoting(Quoting::$ALWAYS);
    $this->assertEquals(Quoting::$ALWAYS, $format->getQuoting());
  }

  #[@test]
  public function withQuotingAccessor() {
    $format= new CsvFormat();
    $this->assertEquals($format, $format->withQuoting(Quoting::$ALWAYS));
    $this->assertEquals(Quoting::$ALWAYS, $format->getQuoting());
  }

  #[@test, @expect('lang.IllegalArgumentException')]
  public function quoteCharMayNotBeLongerThanOneCharacter() {
    (new CsvFormat())->withQuote('Hello');
  }

  #[@test, @expect('lang.IllegalArgumentException')]
  public function quoteCharMayNotBeEmpty() {
    (new CsvFormat())->withQuote('');
  }

  #[@test, @expect('lang.IllegalArgumentException')]
  public function delimiterCharMayNotBeLongerThanOneCharacter() {
    (new CsvFormat())->withDelimiter('Hello');
  }

  #[@test, @expect('lang.IllegalArgumentException')]
  public function delimiterCharMayNotBeEmpty() {
    (new CsvFormat())->withDelimiter('');
  }

  #[@test, @expect('lang.IllegalStateException')]
  public function defaultFormatUnchangeableBySetDelimiter() {
    CsvFormat::$DEFAULT->setDelimiter(',');
  }

  #[@test]
  public function withDelimiterClonesDefaults() {
    $format= CsvFormat::$DEFAULT->withDelimiter(',');
    $this->assertFalse($format === CsvFormat::$DEFAULT);
  }

  #[@test, @expect('lang.IllegalStateException')]
  public function defaultFormatUnchangeableBySetQuote() {
    CsvFormat::$DEFAULT->setQuote("'");
  }

  #[@test]
  public function withQuoteClonesDefaults() {
    $format= CsvFormat::$DEFAULT->withQuote("'");
    $this->assertFalse($format === CsvFormat::$DEFAULT);
  }

  #[@test, @expect('lang.IllegalStateException')]
  public function defaultFormatUnchangeableBySetQuoting() {
    CsvFormat::$DEFAULT->setQuoting(Quoting::$ALWAYS);
  }

  #[@test]
  public function withQuotingClonesDefaults() {
    $format= CsvFormat::$DEFAULT->withQuoting(Quoting::$ALWAYS);
    $this->assertFalse($format === CsvFormat::$DEFAULT);
  }
}
