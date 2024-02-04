<?php namespace text\csv\unittest;

use lang\{IllegalArgumentException, IllegalStateException};
use text\csv\{CsvFormat, Quoting};
use test\Assert;
use test\{Expect, Test};

/**
 * TestCase
 *
 * @see      xp://text.csv.CsvFormat
 */
class CsvFormatTest {

  #[Test]
  public function defaultFormat() {
    $format= CsvFormat::$DEFAULT;
    Assert::equals(';', $format->getDelimiter());
    Assert::equals('"', $format->getQuote());
  }

  #[Test]
  public function pipesFormat() {
    $format= CsvFormat::$PIPES;
    Assert::equals('|', $format->getDelimiter());
    Assert::equals('"', $format->getQuote());
  }

  #[Test]
  public function commasFormat() {
    $format= CsvFormat::$COMMAS;
    Assert::equals(',', $format->getDelimiter());
    Assert::equals('"', $format->getQuote());
  }

  #[Test]
  public function tabsFormat() {
    $format= CsvFormat::$TABS;
    Assert::equals("\t", $format->getDelimiter());
    Assert::equals('"', $format->getQuote());
  }

  #[Test]
  public function quoteAccessors() {
    $format= new CsvFormat();
    $format->setQuote('`');
    Assert::equals('`', $format->getQuote());
  }

  #[Test]
  public function withQuoteAccessor() {
    $format= new CsvFormat();
    Assert::equals($format, $format->withQuote('`'));
    Assert::equals('`', $format->getQuote());
  }

  #[Test]
  public function delimiterAccessors() {
    $format= new CsvFormat();
    $format->setDelimiter(' ');
    Assert::equals(' ', $format->getDelimiter());
  }

  #[Test]
  public function withDelimiterAccessor() {
    $format= new CsvFormat();
    Assert::equals($format, $format->withDelimiter(' '));
    Assert::equals(' ', $format->getDelimiter());
  }

  #[Test]
  public function quotingAccessors() {
    $format= new CsvFormat();
    $format->setQuoting(Quoting::$ALWAYS);
    Assert::equals(Quoting::$ALWAYS, $format->getQuoting());
  }

  #[Test]
  public function withQuotingAccessor() {
    $format= new CsvFormat();
    Assert::equals($format, $format->withQuoting(Quoting::$ALWAYS));
    Assert::equals(Quoting::$ALWAYS, $format->getQuoting());
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
    Assert::false($format === CsvFormat::$DEFAULT);
  }

  #[Test, Expect(IllegalStateException::class)]
  public function defaultFormatUnchangeableBySetQuote() {
    CsvFormat::$DEFAULT->setQuote("'");
  }

  #[Test]
  public function withQuoteClonesDefaults() {
    $format= CsvFormat::$DEFAULT->withQuote("'");
    Assert::false($format === CsvFormat::$DEFAULT);
  }

  #[Test, Expect(IllegalStateException::class)]
  public function defaultFormatUnchangeableBySetQuoting() {
    CsvFormat::$DEFAULT->setQuoting(Quoting::$ALWAYS);
  }

  #[Test]
  public function withQuotingClonesDefaults() {
    $format= CsvFormat::$DEFAULT->withQuoting(Quoting::$ALWAYS);
    Assert::false($format === CsvFormat::$DEFAULT);
  }
}