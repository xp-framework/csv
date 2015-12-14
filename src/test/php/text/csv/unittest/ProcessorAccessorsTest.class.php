<?php namespace text\csv\unittest;

use text\csv\AbstractCsvProcessor;
use text\csv\processors\constraint\Optional;
use text\csv\processors\constraint\Required;

/**
 * TestCase
 *
 * @see      xp://text.csv.AbstractCsvProcessor
 */
class ProcessorAccessorsTest extends \unittest\TestCase {
  protected $fixture= null;

  /**
   * Sets up test case
   *
   * @return void
   */
  public function setUp() {
    $this->fixture= newinstance(AbstractCsvProcessor::class, []);
  }
  
  #[@test]
  public function setProcessors() {
    $processors= [new Optional(), new Required()];
    $this->fixture->setProcessors($processors);
    $this->assertEquals($processors, $this->fixture->getProcessors());
  }

  #[@test]
  public function withProcessors() {
    $processors= [new Optional(), new Required()];
    $this->fixture->withProcessors($processors);
    $this->assertEquals($processors, $this->fixture->getProcessors());
  }

  #[@test]
  public function withProcessors_returns_fixture() {
    $this->assertEquals($this->fixture, $this->fixture->withProcessors([]));
  }

  #[@test]
  public function addProcessor() {
    $processors= [new Optional(), new Required()];
    $this->fixture->addProcessor($processors[0]);
    $this->fixture->addProcessor($processors[1]);
    $this->assertEquals($processors, $this->fixture->getProcessors());
  }

  #[@test]
  public function addProcessor_returns_added_processor() {
    $processor= new Optional();
    $this->assertEquals($processor, $this->fixture->addProcessor($processor));
  }
}
