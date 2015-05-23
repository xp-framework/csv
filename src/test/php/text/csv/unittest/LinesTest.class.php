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
  public function iteration() {
    $in= $this->newFixture("Timm;1549\nAlex;1552");
    $this->assertEquals(
      [['Timm', '1549'], ['Alex', '1552']],
      iterator_to_array($in)
    );
  }
} 