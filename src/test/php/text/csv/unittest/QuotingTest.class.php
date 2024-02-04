<?php namespace text\csv\unittest;

use test\{Assert, Test, Values};
use text\csv\{Quoting, QuotingStrategy};

class QuotingTest {

  /** @return iterable */
  private function quotingStrategies() {
    return [Quoting::$DEFAULT, Quoting::$EMPTY];
  }

  #[Test, Values(from: 'quotingStrategies')]
  public function delimiter_is_quoted($strategy) {
    Assert::true($strategy->necessary(';', ';', '"'));
  }

  #[Test, Values(from: 'quotingStrategies')]
  public function quote_is_quoted($strategy) {
    Assert::true($strategy->necessary('"', ';', '"'));
  }

  #[Test, Values(from: 'quotingStrategies')]
  public function mac_newline_is_quoted($strategy) {
    Assert::true($strategy->necessary("\r", ';', '"'));
  }

  #[Test, Values(from: 'quotingStrategies')]
  public function unix_newline_is_quoted($strategy) {
    Assert::true($strategy->necessary("\n", ';', '"'));
  }

  #[Test, Values(from: 'quotingStrategies')]
  public function windows_newline_is_quoted($strategy) {
    Assert::true($strategy->necessary("\r\n", ';', '"'));
  }

  #[Test, Values(from: 'quotingStrategies')]
  public function single_word_and_newline_is_not_quoted($strategy) {
    Assert::true($strategy->necessary("Test\n", ';', '"'));
  }

  #[Test, Values(from: 'quotingStrategies')]
  public function single_word_is_not_quoted($strategy) {
    Assert::false($strategy->necessary('Test', ';', '"'));
  }

  #[Test, Values(from: 'quotingStrategies')]
  public function two_words_separated_by_space_are_not_quoted($strategy) {
    Assert::false($strategy->necessary('Hello World', ';', '"'));
  }

  #[Test]
  public function emtpy_string_not_quoted_with_default() {
    Assert::false(Quoting::$DEFAULT->necessary('', ';', '"'));
  }

  #[Test]
  public function emtpy_string_quoted_with_empty() {
    Assert::true(Quoting::$EMPTY->necessary('', ';', '"'));
  }

  #[Test, Values(['', ';', '"', "\r", "\n", "\r\n", 'A', 'Hello'])]
  public function anything_is_quoted_with_always_strategy($value) {
    Assert::true(Quoting::$ALWAYS->necessary($value, ';', '"'), $value);
  }

  #[Test, Values(['', ';', '"', "\r", "\n", "\r\n", 'A', 'Hello'])]
  public function nothing_is_quoted_with_never_strategy($value) {
    $never= new class() implements QuotingStrategy {
      public function necessary($value, $delimiter, $quote) {
        return false;
      }
    };

    Assert::false($never->necessary($value, ';', '"'));
  }
}