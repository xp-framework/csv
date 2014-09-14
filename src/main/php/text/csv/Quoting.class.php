<?php namespace text\csv;

use lang\Enum;

/**
 * CSV quoting strategy enumeration. The following strategies are
 * available:
 * <ul>
 *   <li>DEFAULT - quotes according to the RFC</li>
 *   <li>EMPTY   - like default strategy but always quotes empty values</li>
 *   <li>ALWAYS  - quotes regardless of value's content</li>
 * </ul>
 *
 * @test     xp://text.csv.unittest.QuotingTest
 * @see      xp://text.csv.QuotingStrategy
 * @see      xp://text.csv.CsvFormat#setQuoting
 */
abstract class Quoting extends Enum implements QuotingStrategy {
  public static $DEFAULT= null;
  public static $EMPTY= null;
  public static $ALWAYS= null;
  
  static function __static() {
    self::$DEFAULT= newinstance(__CLASS__, array(0, 'DEFAULT'), '{
      static function __static() { }
      public function necessary($value, $delimiter, $quote) {
        return strcspn($value, $delimiter.$quote."\r\n") < strlen($value);
      }
    }');
    self::$EMPTY= newinstance(__CLASS__, array(1, 'EMPTY'), '{
      static function __static() { }
      public function necessary($value, $delimiter, $quote) {
        return "" === $value || strcspn($value, $delimiter.$quote."\r\n") < strlen($value);
      }
    }');
    self::$ALWAYS= newinstance(__CLASS__, array(2, 'ALWAYS'), '{
      static function __static() { }
      public function necessary($value, $delimiter, $quote) {
        return TRUE;
      }
    }');
  }
}
