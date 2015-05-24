<?php namespace text\csv\unittest;

use text\csv\CsvListReader;
use text\csv\Lines;
use io\streams\TextReader;
use io\streams\MemoryInputStream;

class LinesTest extends \unittest\TestCase {

  /** @return text.csv.Lines */
  private function newFixture($input) {
    return new Lines(new CsvListReader(new TextReader(new MemoryInputStream($input))));
  }

  #[@test]
  public function iteration_of_empty() {
    $this->assertEquals([], iterator_to_array($this->newFixture('')));
  }

  #[@test, @values([
  #  ["Timm;1549", [['Timm', '1549']]],
  #  ["Timm;1549\n", [['Timm', '1549']]],
  #  ["Timm;1549\nAlex;1552", [['Timm', '1549'], ['Alex', '1552']]]
  #])]
  public function iteration($input, $expected) {
    $this->assertEquals($expected, iterator_to_array($this->newFixture($input)));
  }
}