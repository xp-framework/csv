<?php namespace text\csv\unittest;

use lang\{IllegalArgumentException, IllegalStateException};
use text\csv\{CsvFormat, Quoting};
use unittest\{Expect, Test};

/**
 * TestCase
 *
 * @see      xp://text.csv.CsvFormat
 */
class CsvFormatTest extends \unittest\TestCase {

  #[Test]
  public function defaultFormat() {
    $format= CsvFormat::$DEFAULT;
    $this->assertEquals(';', $format->getDelimiter());
    $this->assertEquals('"', $format->getQuote());
  }

  #[Test]
  public function pipesFormat() {
    $format= CsvFormat::$PIPES;
    $this->assertEquals('|', $format->getDelimiter());
    $this->assertEquals('"', $format->getQuote());
  }

  #[Test]
  public function commasFormat() {
    $format= CsvFormat::$COMMAS;
    $this->assertEquals(',', $format->getDelimiter());
    $this->assertEquals('"', $format->getQuote());
  }

  #[Test]
  public function tabsFormat() {
    $format= CsvFormat::$TABS;
    $this->assertEquals("\t", $format->getDelimiter());
    $this->assertEquals('"', $format->getQuote());
  }

  #[Test]
  public function quoteAccessors() {
    $format= new CsvFormat();
    $format->setQuote('`');
    $this->assertEquals('`', $format->getQuote());
  }

  #[Test]
  public function withQuoteAccessor() {
    $format= new CsvFormat();
    $this->assertEquals($format, $format->withQuote('`'));
    $this->assertEquals('`', $format->getQuote());
  }

  #[Test]
  public function delimiterAccessors() {
    $format= new CsvFormat();
    $format->setDelimiter(' ');
    $this->assertEquals(' ', $format->getDelimiter());
  }

  #[Test]
  public function withDelimiterAccessor() {
    $format= new CsvFormat();
    $this->assertEquals($format, $format->withDelimiter(' '));
    $this->assertEquals(' ', $format->getDelimiter());
  }

  #[Test]
  public function quotingAccessors() {
    $format= new CsvFormat();
    $format->setQuoting(Quoting::$ALWAYS);
    $this->assertEquals(Quoting::$ALWAYS, $format->getQuoting());
  }

  #[Test]
  public function withQuotingAccessor() {
    $format= new CsvFormat();
    $this->assertEquals($format, $format->withQuoting(Quoting::$ALWAYS));
    $this->assertEquals(Quoting::$ALWAYS, $format->getQuoting());
  }

  #[Test, Expect(IllegalArgumentException::class)]
  public function quoteCharMayNotBeLongerThanOneCharacter() {
    (new CsvFormat())->withQuote('Hello');
  }

  #[Test, Expect(IllegalArgumentException::class)]
  public function quoteCharMayNotBeEmpty() {
    (new CsvFormat())->withQuote('');
  }

  #[Test, Expect(IllegalArgumentException::class)]
  public function delimiterCharMayNotBeLongerThanOneCharacter() {
    (new CsvFormat())->withDelimiter('Hello');
  }

  #[Test, Expect(IllegalArgumentException::class)]
  public function delimiterCharMayNotBeEmpty() {
    (new CsvFormat())->withDelimiter('');
  }

  #[Test, Expect(IllegalStateException::class)]
  public function defaultFormatUnchangeableBySetDelimiter() {
    CsvFormat::$DEFAULT->setDelimiter(',');
  }

  #[Test]
  public function withDelimiterClonesDefaults() {
    $format= CsvFormat::$DEFAULT->withDelimiter(',');
    $this->assertFalse($format === CsvFormat::$DEFAULT);
  }

  #[Test, Expect(IllegalStateException::class)]
  public function defaultFormatUnchangeableBySetQuote() {
    CsvFormat::$DEFAULT->setQuote("'");
  }

  #[Test]
  public function withQuoteClonesDefaults() {
    $format= CsvFormat::$DEFAULT->withQuote("'");
    $this->assertFalse($format === CsvFormat::$DEFAULT);
  }

  #[Test, Expect(IllegalStateException::class)]
  public function defaultFormatUnchangeableBySetQuoting() {
    CsvFormat::$DEFAULT->setQuoting(Quoting::$ALWAYS);
  }

  #[Test]
  public function withQuotingClonesDefaults() {
    $format= CsvFormat::$DEFAULT->withQuoting(Quoting::$ALWAYS);
    $this->assertFalse($format === CsvFormat::$DEFAULT);
  }
}