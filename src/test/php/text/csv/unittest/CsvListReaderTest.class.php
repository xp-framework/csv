<?php namespace text\csv\unittest;

use unittest\TestCase;
use text\csv\CsvListReader;
use text\csv\CsvFormat;
use io\streams\MemoryInputStream;
use io\streams\TextReader;

/**
 * TestCase
 *
 * @see   xp://text.csv.CsvListReader
 * @see   http://en.wikipedia.org/wiki/Comma-separated_values
 */
class CsvListReaderTest extends TestCase {

  /**
   * Ctreates a new list reader
   *
   * @param   string str
   * @param   text.csv.CsvFormat format
   * @return  text.csv.CsvListReader
   */
  protected function newReader($str, CsvFormat $format= null) {
    return new CsvListReader(new TextReader(new MemoryInputStream($str)), $format);
  }

  #[@test]
  public function readLine() {
    $in= $this->newReader('Timm;Karlsruhe;76137');
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[@test]
  public function readLineDelimitedByCommas() {
    $in= $this->newReader('Timm,Karlsruhe,76137', (new CsvFormat())->withDelimiter(','));
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[@test]
  public function readLineDelimitedByPipes() {
    $in= $this->newReader('Timm|Karlsruhe|76137', (new CsvFormat())->withDelimiter('|'));
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[@test]
  public function readEmpty() {
    $in= $this->newReader('');
    $this->assertNull($in->read());
  }

  #[@test]
  public function readMultipleLines() {
    $in= $this->newReader('Timm;Karlsruhe;76137'."\n".'Alex;Karlsruhe;76131');
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
    $this->assertEquals(['Alex', 'Karlsruhe', '76131'], $in->read());
  }

  #[@test]
  public function getHeaders() {
    $in= $this->newReader('name;city;zip'."\n".'Alex;Karlsruhe;76131');
    $this->assertEquals(array('name', 'city', 'zip'), $in->getHeaders());
    $this->assertEquals(['Alex', 'Karlsruhe', '76131'], $in->read());
  }

  #[@test, @expect('lang.IllegalStateException')]
  public function cannotGetHeadersAfterReading() {
    $in= $this->newReader('Timm;Karlsruhe;76137');
    $in->read();
    $in->getHeaders();
  }

  #[@test]
  public function leadingWhitespace() {
    $in= $this->newReader(' Timm;Karlsruhe;76137');
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[@test]
  public function leadingTab() {
    $in= $this->newReader("\tTimm;Karlsruhe;76137");
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[@test]
  public function leadingTabAndWhiteSpace() {
    $in= $this->newReader("\t  Timm;Karlsruhe;76137");
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[@test]
  public function leadingWhitespaces() {
    $in= $this->newReader('Timm;    Karlsruhe;    76137');
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[@test]
  public function leadingTabs() {
    $in= $this->newReader("Timm;\tKarlsruhe;\t76137");
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[@test]
  public function trailingWhitespace() {
    $in= $this->newReader('Timm ;Karlsruhe;76137');
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[@test]
  public function trailingTab() {
    $in= $this->newReader("Timm\t;Karlsruhe;76137");
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[@test]
  public function surroundingWhitespace() {
    $in= $this->newReader('Timm   ;   Karlsruhe   ;   76137');
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[@test]
  public function whiteSpaceAndEmpty() {
    $in= $this->newReader('       ;   Karlsruhe   ;   76137');
    $this->assertEquals(array('', 'Karlsruhe', '76137'), $in->read());
  }

  #[@test]
  public function readQuotedValue() {
    $in= $this->newReader('"Timm";Karlsruhe;76137');
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[@test]
  public function readQuotedValueWithSurroundingWhitespace() {
    $in= $this->newReader('   "Timm"    ;Karlsruhe;76137');
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[@test]
  public function readQuotedValueIncludingWhitespace() {
    $in= $this->newReader('"   Timm    ";Karlsruhe;76137');
    $this->assertEquals(array('   Timm    ', 'Karlsruhe', '76137'), $in->read());
  }

  #[@test]
  public function readQuotedValues() {
    $in= $this->newReader('"Timm";"Karlsruhe";"76137"');
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[@test]
  public function readQuotedWithSingleQuotes() {
    $in= $this->newReader("Timm;'Karlsruhe';76137", (new CsvFormat())->withQuote("'"));
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  /**
   * Test reading a quoted value containing the separator character
   *
   */
  #[@test]
  public function readQuotedValueWithSeparator() {
    $in= $this->newReader('"Friebe;Timm";Karlsruhe;76137');
    $this->assertEquals(array('Friebe;Timm', 'Karlsruhe', '76137'), $in->read());
  }

  /**
   * Test reading a quoted value containing the separator character
   *
   */
  #[@test]
  public function readQuotedValueWithSeparatorInMiddle() {
    $in= $this->newReader('Timm;"Karlsruhe;Germany";76137');
    $this->assertEquals(array('Timm', 'Karlsruhe;Germany', '76137'), $in->read());
  }

  /**
   * Test reading a quoted value containing the separator character
   *
   */
  #[@test]
  public function readQuotedValueWithSeparatorAtEnd() {
    $in= $this->newReader('Timm;Karlsruhe;"76131;76135;76137"');
    $this->assertEquals(array('Timm', 'Karlsruhe', '76131;76135;76137'), $in->read());
  }

  #[@test]
  public function readQuotedValueWithQuotes() {
    $in= $this->newReader('"""Hello""";Karlsruhe;76137');
    $this->assertEquals(array('"Hello"', 'Karlsruhe', '76137'), $in->read());
  }

  #[@test]
  public function readEmptyQuotedValue() {
    $in= $this->newReader('"";Karlsruhe;76137');
    $this->assertEquals(array('', 'Karlsruhe', '76137'), $in->read());
  }

  #[@test]
  public function readQuotedValueWithQuotesInside() {
    $in= $this->newReader('"Timm""Karlsruhe";76137');
    $this->assertEquals(array('Timm"Karlsruhe', '76137'), $in->read());
  }

  #[@test]
  public function quotesInsideUnquoted() {
    $in= $this->newReader('He said "Hello";Karlsruhe;76137');
    $this->assertEquals(array('He said "Hello"', 'Karlsruhe', '76137'), $in->read());
  }

  #[@test]
  public function quoteInsideUnquoted() {
    $in= $this->newReader('A single " is OK;Karlsruhe;76137');
    $this->assertEquals(array('A single " is OK', 'Karlsruhe', '76137'), $in->read());
  }

  #[@test]
  public function multiLine() {
    $in= $this->newReader(
      "14:30-15:30;Development;'- Fix unittests\n- QA: Apidoc'",
      (new CsvFormat())->withQuote("'")
    );
    $this->assertEquals(
      array('14:30-15:30', 'Development', "- Fix unittests\n- QA: Apidoc"), 
      $in->read()
    );
  }

  #[@test]
  public function multiLines() {
    $in= $this->newReader(
      "14:30-15:30;Development;'- Fix unittests\n- QA: Apidoc'\n15:30-15:49;Report;- Tests",
      (new CsvFormat())->withQuote("'")
    );
    $this->assertEquals(array('14:30-15:30', 'Development', "- Fix unittests\n- QA: Apidoc"), $in->read());
    $this->assertEquals(array('15:30-15:49', 'Report', '- Tests'), $in->read());
  }

  #[@test, @ignore('Is this really allowed?')]
  public function partialQuoting() {
    $in= $this->newReader('"Timm"|"Karlsruhe";76137');
    $this->assertEquals(array('Timm|Karlsruhe', '76131'), $in->read());
  }

  #[@test, @ignore('Is this really allowed?')]
  public function partialQuotingDelimiter() {
    $in= $this->newReader('Timm";"Karlsruhe;76137');
    $this->assertEquals(array('Timm;Karlsruhe', '76131'), $in->read());
  }

  #[@test, @expect('lang.FormatException')]
  public function unterminatedQuote() {
    $this->newReader('"Unterminated;Karlsruhe;76131')->read();
  }

  #[@test, @expect('lang.FormatException')]
  public function unterminatedQuoteInTheMiddle() {
    $this->newReader('Timm;"Unterminated;76131')->read();
  }

  #[@test, @expect('lang.FormatException')]
  public function unterminatedQuoteRightBeforeSeparator() {
    $this->newReader('";Karlsruhe;76131')->read();
  }

  #[@test, @expect('lang.FormatException')]
  public function unterminatedQuoteInTheMiddleRightBeforeSeparator() {
    $this->newReader('Timm;";76131')->read();
  }

  #[@test, @expect('lang.FormatException')]
  public function unterminatedQuoteAtEnd() {
    $this->newReader('A;B;"')->read();
  }

  #[@test, @expect('lang.FormatException')]
  public function unterminatedQuoteAtEndWithTrailingSeparator() {
    $this->newReader('A;B;";')->read();
  }

  #[@test, @expect('lang.FormatException')]
  public function unterminatedQuoteAtBeginning() {
    $this->newReader('"')->read();
  }

  #[@test]
  public function readingContinuesAfterBrokenLine() {
    $in= $this->newReader('"Hello"-;Karlsruhe;76131'."\n".'Timm;Karlsruhe;76137');
    try {
      $in->read();
      $this->fail('Unterminated literal not detected', null, 'lang.FormatException');
    } catch (\lang\FormatException $expected) { }
    $this->assertEquals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[@test]
  public function readEmptyValue() {
    $in= $this->newReader('Timm;;76137');
    $this->assertEquals(['Timm', '', '76137'], $in->read());
  }

  #[@test]
  public function readEmptyValueAtBeginning() {
    $in= $this->newReader(';Karlsruhe;76137');
    $this->assertEquals(['', 'Karlsruhe', '76137'], $in->read());
  }

  #[@test]
  public function readEmptyValueAtEnd() {
    $in= $this->newReader('Timm;Karlsruhe;');
    $this->assertEquals(['Timm', 'Karlsruhe', ''], $in->read());
  }

  #[@test]
  public function readEmptyValueAtEndWithTrailingDelimiter() {
    $in= $this->newReader('Timm;Karlsruhe;;');
    $this->assertEquals(['Timm', 'Karlsruhe', '', ''], $in->read());
  }

  #[@test, @expect('lang.FormatException')]
  public function illegalQuoting() {
    $this->newReader('"Timm"Karlsruhe";76137')->read();
  }

  #[@test]
  public function tabDelimiter() {
    $in= $this->newReader("A\tB\tC", CsvFormat::$TABS);
    $this->assertEquals(array('A', 'B', 'C'), $in->read());
  }

  #[@test]
  public function spaceDelimiter() {
    $in= $this->newReader('A B C', (new CsvFormat())->withDelimiter(' '));
    $this->assertEquals(array('A', 'B', 'C'), $in->read());
  }

  #[@test]
  public function wikipediaExample() {
    $r= $this->newReader(
      '1997,Ford,E350,"ac, abs, moon",3000.00'."\n".
      '1999,Chevy,"Venture ""Extended Edition""","",4900.00'."\n".
      '1996,Jeep,Grand Cherokee,"MUST SELL!'."\n".
      'air, moon roof, loaded",4799.00'."\n",
      CsvFormat::$COMMAS
    );
    $this->assertEquals(array('1997', 'Ford', 'E350', 'ac, abs, moon', '3000.00'), $r->read());
    $this->assertEquals(array('1999', 'Chevy', 'Venture "Extended Edition"', '', '4900.00'), $r->read());
    $this->assertEquals(array('1996', 'Jeep', 'Grand Cherokee', "MUST SELL!\nair, moon roof, loaded", '4799.00'), $r->read());
    $this->assertNull($r->read());
  }
}
