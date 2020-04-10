<?php namespace text\csv;

/**
 * CSV quoting strategy enumeration. The following strategies are
 * available:
 *
 * - DEFAULT - quotes according to the RFC
 * - EMPTY   - like default strategy but always quotes empty values
 * - ALWAYS  - quotes regardless of value's content
 *
 * @test     xp://text.csv.unittest.QuotingTest
 * @see      xp://text.csv.QuotingStrategy
 * @see      xp://text.csv.CsvFormat#setQuoting
 */
abstract class Quoting extends \lang\Enum implements QuotingStrategy {
  public static $DEFAULT= null;
  public static $EMPTY= null;
  public static $ALWAYS= null;
  
  static function __static() {
    self::$DEFAULT= newinstance(__CLASS__, [0, 'DEFAULT'], '{
      static function __static() { }
      public function necessary($value, $delimiter, $quote) {
        return strcspn($value, $delimiter.$quote."\r\n") < strlen($value);
      }
    }');
    self::$EMPTY= newinstance(__CLASS__, [1, 'EMPTY'], '{
      static function __static() { }
      public function necessary($value, $delimiter, $quote) {
        return "" === $value || strcspn($value, $delimiter.$quote."\r\n") < strlen($value);
      }
    }');
    self::$ALWAYS= newinstance(__CLASS__, [2, 'ALWAYS'], '{
      static function __static() { }
      public function necessary($value, $delimiter, $quote) {
        return TRUE;
      }
    }');
  }
}