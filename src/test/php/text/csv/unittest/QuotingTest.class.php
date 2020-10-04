<?php namespace text\csv\unittest;

use text\csv\{Quoting, QuotingStrategy};
use unittest\{BeforeClass, Test, Values};

/**
 * TestCase
 *
 * @see      xp://text.csv.Quoting
 */
class QuotingTest extends \unittest\TestCase {
  private static $never;

  /**
   * Creating a quoting strategy that never quotes anything. This is
   * for unittesting purposes only, such a strategy would not make
   * sense in real-life situations!
   */
  #[BeforeClass]
  public static function neverQuotingStrategy() {
    self::$never= new class() implements QuotingStrategy {
      public function necessary($value, $delimiter, $quote) {
        return false;
      }
    };
  }

  /**
   * Returns quoting strategies
   *
   * @return  var[]
   */
  public function quotingStrategies() {
    return [Quoting::$DEFAULT, Quoting::$EMPTY];
  }

  #[Test, Values('quotingStrategies')]
  public function delimiter_is_quoted($strategy) {
    $this->assertTrue($strategy->necessary(';', ';', '"'));
  }

  #[Test, Values('quotingStrategies')]
  public function quote_is_quoted($strategy) {
    $this->assertTrue($strategy->necessary('"', ';', '"'));
  }

  #[Test, Values('quotingStrategies')]
  public function mac_newline_is_quoted($strategy) {
    $this->assertTrue($strategy->necessary("\r", ';', '"'));
  }

  #[Test, Values('quotingStrategies')]
  public function unix_newline_is_quoted($strategy) {
    $this->assertTrue($strategy->necessary("\n", ';', '"'));
  }

  #[Test, Values('quotingStrategies')]
  public function windows_newline_is_quoted($strategy) {
    $this->assertTrue($strategy->necessary("\r\n", ';', '"'));
  }

  #[Test, Values('quotingStrategies')]
  public function single_word_and_newline_is_not_quoted($strategy) {
    $this->assertTrue($strategy->necessary("Test\n", ';', '"'));
  }

  #[Test, Values('quotingStrategies')]
  public function single_word_is_not_quoted($strategy) {
    $this->assertFalse($strategy->necessary('Test', ';', '"'));
  }

  #[Test, Values('quotingStrategies')]
  public function two_words_separated_by_space_are_not_quoted($strategy) {
    $this->assertFalse($strategy->necessary('Hello World', ';', '"'));
  }

  #[Test]
  public function emtpy_string_not_quoted_with_default() {
    $this->assertFalse(Quoting::$DEFAULT->necessary('', ';', '"'));
  }

  #[Test]
  public function emtpy_string_quoted_with_empty() {
    $this->assertTrue(Quoting::$EMPTY->necessary('', ';', '"'));
  }

  #[Test, Values(['', ';', '"', "\r", "\n", "\r\n", 'A', 'Hello'])]
  public function anything_is_quoted_with_always_strategy($value) {
    $this->assertTrue(Quoting::$ALWAYS->necessary($value, ';', '"'), $value);
  }

  #[Test, Values(['', ';', '"', "\r", "\n", "\r\n", 'A', 'Hello'])]
  public function nothing_is_quoted_with_never_strategy($value) {
    $this->assertFalse(self::$never->necessary($value, ';', '"'));
  }
}