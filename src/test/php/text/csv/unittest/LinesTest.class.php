<?php namespace text\csv\unittest;

use io\streams\MemoryInputStream;
use io\streams\TextReader;
use text\csv\CsvListReader;
use text\csv\Lines;

class LinesTest extends \unittest\TestCase {

  /** @return text.csv.Lines */
  private function newFixture($input) {
    return new Lines(new CsvListReader(new TextReader(new MemoryInputStream($input))));
  }

  #[@test, @values(['', "\n", "\n\n"])]
  public function iteration_of_empty($input) {
    $this->assertEquals([], iterator_to_array($this->newFixture($input)));
  }

  #[@test, @values([
  #  ["Timm;1549", [['Timm', '1549']]],
  #  ["Timm;1549\n", [['Timm', '1549']]],
  #  ["Timm;1549\n\n", [['Timm', '1549']]],
  #  ["Timm;1549\nAlex;1552", [['Timm', '1549'], ['Alex', '1552']]],
  #  ["Timm;1549\n\nAlex;1552", [['Timm', '1549'], ['Alex', '1552']]]
  #])]
  public function iteration($input, $expected) {
    $this->assertEquals($expected, iterator_to_array($this->newFixture($input)));
  }
}