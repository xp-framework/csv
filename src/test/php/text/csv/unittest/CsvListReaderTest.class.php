<?php namespace text\csv\unittest;

use io\streams\{MemoryInputStream, TextReader};
use lang\{FormatException, IllegalStateException};
use text\csv\{CsvFormat, CsvListReader};
use test\Assert;
use test\{Expect, Ignore, Test};

class CsvListReaderTest extends CsvReaderTest {

  /**
   * Creates a new CSV reader fixture
   *
   * @param  io.streams.Reader|io.streams.InputStream|io.Channel|string $in
   * @param  text.csv.CsvFormat $format
   * @return text.csv.CsvReader
   */
  protected function newFixture($in, $format= null) {
    return new CsvListReader($in, $format);
  }

  #[Test]
  public function read_line() {
    $in= $this->newFixture(new MemoryInputStream('Timm;Karlsruhe;76137'));
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function read_line_delimited_by_commas() {
    $in= $this->newFixture(new MemoryInputStream('Timm,Karlsruhe,76137'), (new CsvFormat())->withDelimiter(','));
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function read_line_delimited_by_pipes() {
    $in= $this->newFixture(new MemoryInputStream('Timm|Karlsruhe|76137'), (new CsvFormat())->withDelimiter('|'));
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function read_empty() {
    $in= $this->newFixture(new MemoryInputStream(''));
    Assert::null($in->read());
  }

  #[Test]
  public function read_multiple_lines() {
    $in= $this->newFixture(new MemoryInputStream('Timm;Karlsruhe;76137'."\n".'Alex;Karlsruhe;76131'));
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
    Assert::equals(['Alex', 'Karlsruhe', '76131'], $in->read());
  }

  #[Test]
  public function get_headers() {
    $in= $this->newFixture(new MemoryInputStream('name;city;zip'."\n".'Alex;Karlsruhe;76131'));
    Assert::equals(['name', 'city', 'zip'], $in->getHeaders());
    Assert::equals(['Alex', 'Karlsruhe', '76131'], $in->read());
  }

  #[Test, Expect(IllegalStateException::class)]
  public function cannot_get_headers_after_reading() {
    $in= $this->newFixture(new MemoryInputStream('Timm;Karlsruhe;76137'));
    $in->read();
    $in->getHeaders();
  }

  #[Test]
  public function leading_whitespace() {
    $in= $this->newFixture(new MemoryInputStream(' Timm;Karlsruhe;76137'));
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function leading_tab() {
    $in= $this->newFixture(new MemoryInputStream("\tTimm;Karlsruhe;76137"));
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function leading_tab_and_whitespace() {
    $in= $this->newFixture(new MemoryInputStream("\t  Timm;Karlsruhe;76137"));
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function leading_whitespaces() {
    $in= $this->newFixture(new MemoryInputStream('Timm;    Karlsruhe;    76137'));
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function leading_tabs() {
    $in= $this->newFixture(new MemoryInputStream("Timm;\tKarlsruhe;\t76137"));
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function trailingWhitespace() {
    $in= $this->newFixture(new MemoryInputStream('Timm ;Karlsruhe;76137'));
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function trailing_tab() {
    $in= $this->newFixture(new MemoryInputStream("Timm\t;Karlsruhe;76137"));
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function surrounding_whitespace() {
    $in= $this->newFixture(new MemoryInputStream('Timm   ;   Karlsruhe   ;   76137'));
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function whitespace_and_empty() {
    $in= $this->newFixture(new MemoryInputStream('       ;   Karlsruhe   ;   76137'));
    Assert::equals(['', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function read_quoted_value() {
    $in= $this->newFixture(new MemoryInputStream('"Timm";Karlsruhe;76137'));
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function read_quoted_value_with_surrounding_whitespace() {
    $in= $this->newFixture(new MemoryInputStream('   "Timm"    ;Karlsruhe;76137'));
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function read_quoted_value_including_whitespace() {
    $in= $this->newFixture(new MemoryInputStream('"   Timm    ";Karlsruhe;76137'));
    Assert::equals(['   Timm    ', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function read_quoted_values() {
    $in= $this->newFixture(new MemoryInputStream('"Timm";"Karlsruhe";"76137"'));
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function read_quoted_with_single_quotes() {
    $in= $this->newFixture(new MemoryInputStream("Timm;'Karlsruhe';76137"), (new CsvFormat())->withQuote("'"));
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function read_quoted_value_with_separator() {
    $in= $this->newFixture(new MemoryInputStream('"Friebe;Timm";Karlsruhe;76137'));
    Assert::equals(['Friebe;Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function read_quoted_value_with_separator_in_middle() {
    $in= $this->newFixture(new MemoryInputStream('Timm;"Karlsruhe;Germany";76137'));
    Assert::equals(['Timm', 'Karlsruhe;Germany', '76137'], $in->read());
  }

  #[Test]
  public function read_quoted_value_with_separator_at_end() {
    $in= $this->newFixture(new MemoryInputStream('Timm;Karlsruhe;"76131;76135;76137"'));
    Assert::equals(['Timm', 'Karlsruhe', '76131;76135;76137'], $in->read());
  }

  #[Test]
  public function read_quoted_value_with_quotes() {
    $in= $this->newFixture(new MemoryInputStream('"""Hello""";Karlsruhe;76137'));
    Assert::equals(['"Hello"', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function read_empty_quoted_value() {
    $in= $this->newFixture(new MemoryInputStream('"";Karlsruhe;76137'));
    Assert::equals(['', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function read_quoted_value_with_quotes_inside() {
    $in= $this->newFixture(new MemoryInputStream('"Timm""Karlsruhe";76137'));
    Assert::equals(['Timm"Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function quotes_inside_unquoted() {
    $in= $this->newFixture(new MemoryInputStream('He said "Hello";Karlsruhe;76137'));
    Assert::equals(['He said "Hello"', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function quote_inside_unquoted() {
    $in= $this->newFixture(new MemoryInputStream('A single " is OK;Karlsruhe;76137'));
    Assert::equals(['A single " is OK', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function multi_line() {
    $in= $this->newFixture(
      new MemoryInputStream("14:30-15:30;Development;'- Fix unittests\n- QA: Apidoc'"),
      (new CsvFormat())->withQuote("'")
    );
    Assert::equals(
      ['14:30-15:30', 'Development', "- Fix unittests\n- QA: Apidoc"], 
      $in->read()
    );
  }

  #[Test]
  public function multi_lines() {
    $in= $this->newFixture(
      new MemoryInputStream("14:30-15:30;Development;'- Fix unittests\n- QA: Apidoc'\n15:30-15:49;Report;- Tests"),
      (new CsvFormat())->withQuote("'")
    );
    Assert::equals(['14:30-15:30', 'Development', "- Fix unittests\n- QA: Apidoc"], $in->read());
    Assert::equals(['15:30-15:49', 'Report', '- Tests'], $in->read());
  }

  #[Test, Ignore('Is this really allowed?')]
  public function partialquoting() {
    $in= $this->newFixture(new MemoryInputStream('"Timm"|"Karlsruhe";76137'));
    Assert::equals(['Timm|Karlsruhe', '76131'], $in->read());
  }

  #[Test, Ignore('Is this really allowed?')]
  public function partial_quoting_delimiter() {
    $in= $this->newFixture(new MemoryInputStream('Timm";"Karlsruhe;76137'));
    Assert::equals(['Timm;Karlsruhe', '76131'], $in->read());
  }

  #[Test, Expect(FormatException::class)]
  public function unterminated_quote() {
    $this->newFixture(new MemoryInputStream('"Unterminated;Karlsruhe;76131'))->read();
  }

  #[Test, Expect(FormatException::class)]
  public function unterminated_quote_in_the_middle() {
    $this->newFixture(new MemoryInputStream('Timm;"Unterminated;76131'))->read();
  }

  #[Test, Expect(FormatException::class)]
  public function unterminated_quote_right_before_separator() {
    $this->newFixture(new MemoryInputStream('";Karlsruhe;76131'))->read();
  }

  #[Test, Expect(FormatException::class)]
  public function unterminated_quote_in_the_middle_right_before_separator() {
    $this->newFixture(new MemoryInputStream('Timm;";76131'))->read();
  }

  #[Test, Expect(FormatException::class)]
  public function unterminated_quote_at_end() {
    $this->newFixture(new MemoryInputStream('A;B;"'))->read();
  }

  #[Test, Expect(FormatException::class)]
  public function unterminated_quote_at_end_with_trailing_separator() {
    $this->newFixture(new MemoryInputStream('A;B;";'))->read();
  }

  #[Test, Expect(FormatException::class)]
  public function unterminated_quote_at_beginning() {
    $this->newFixture(new MemoryInputStream('"'))->read();
  }

  #[Test]
  public function reading_continues_after_broken_line() {
    $in= $this->newFixture(new MemoryInputStream('"Hello"-;Karlsruhe;76131'."\n".'Timm;Karlsruhe;76137'));
    try {
      $in->read();
      $this->fail('Unterminated literal not detected', null, 'lang.FormatException');
    } catch (\lang\FormatException $expected) { }
    Assert::equals(['Timm', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function read_empty_value() {
    $in= $this->newFixture(new MemoryInputStream('Timm;;76137'));
    Assert::equals(['Timm', '', '76137'], $in->read());
  }

  #[Test]
  public function read_empty_value_at_beginning() {
    $in= $this->newFixture(new MemoryInputStream(';Karlsruhe;76137'));
    Assert::equals(['', 'Karlsruhe', '76137'], $in->read());
  }

  #[Test]
  public function read_empty_value_at_end() {
    $in= $this->newFixture(new MemoryInputStream('Timm;Karlsruhe;'));
    Assert::equals(['Timm', 'Karlsruhe', ''], $in->read());
  }

  #[Test]
  public function read_empty_value_at_end_with_trailing_delimiter() {
    $in= $this->newFixture(new MemoryInputStream('Timm;Karlsruhe;;'));
    Assert::equals(['Timm', 'Karlsruhe', '', ''], $in->read());
  }

  #[Test, Expect(FormatException::class)]
  public function illegal_quoting() {
    $this->newFixture(new MemoryInputStream('"Timm"Karlsruhe";76137'))->read();
  }

  #[Test]
  public function tab_delimiter() {
    $in= $this->newFixture(new MemoryInputStream("A\tB\tC"), CsvFormat::$TABS);
    Assert::equals(['A', 'B', 'C'], $in->read());
  }

  #[Test]
  public function space_delimiter() {
    $in= $this->newFixture(new MemoryInputStream('A B C'), (new CsvFormat())->withDelimiter(' '));
    Assert::equals(['A', 'B', 'C'], $in->read());
  }

  #[Test]
  public function wikipedia_example() {
    $r= $this->newFixture(new MemoryInputStream(
      '1997,Ford,E350,"ac, abs, moon",3000.00'."\n".
      '1999,Chevy,"Venture ""Extended Edition""","",4900.00'."\n".
      '1996,Jeep,Grand Cherokee,"MUST SELL!'."\n".
      'air, moon roof, loaded",4799.00'."\n"),
      CsvFormat::$COMMAS
    );
    Assert::equals(['1997', 'Ford', 'E350', 'ac, abs, moon', '3000.00'], $r->read());
    Assert::equals(['1999', 'Chevy', 'Venture "Extended Edition"', '', '4900.00'], $r->read());
    Assert::equals(['1996', 'Jeep', 'Grand Cherokee', "MUST SELL!\nair, moon roof, loaded", '4799.00'], $r->read());
    Assert::null($r->read());
  }
}