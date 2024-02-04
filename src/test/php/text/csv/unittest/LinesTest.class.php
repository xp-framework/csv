<?php namespace text\csv\unittest;

use io\streams\{MemoryInputStream, TextReader};
use text\csv\{CsvListReader, Lines};
use test\Assert;
use test\{Test, Values};

class LinesTest {

  /** @return text.csv.Lines */
  private function newFixture($input) {
    return new Lines(new CsvListReader(new TextReader(new MemoryInputStream($input))));
  }

  #[Test, Values(['', "\n", "\n\n"])]
  public function iteration_of_empty($input) {
    Assert::equals([], iterator_to_array($this->newFixture($input)));
  }

  #[Test, Values([["Timm;1549", [['Timm', '1549']]], ["Timm;1549\n", [['Timm', '1549']]], ["Timm;1549\n\n", [['Timm', '1549']]], ["Timm;1549\nAlex;1552", [['Timm', '1549'], ['Alex', '1552']]], ["Timm;1549\n\nAlex;1552", [['Timm', '1549'], ['Alex', '1552']]]])]
  public function iteration($input, $expected) {
    Assert::equals($expected, iterator_to_array($this->newFixture($input)));
  }
}