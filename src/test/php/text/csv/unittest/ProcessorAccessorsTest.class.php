<?php namespace text\csv\unittest;

use text\csv\AbstractCsvProcessor;
use text\csv\processors\constraint\{Optional, Required};
use unittest\{Test, TestCase};

class ProcessorAccessorsTest extends TestCase {
  protected $fixture= null;

  /** @return void */
  public function setUp() {
    $this->fixture= new class() extends AbstractCsvProcessor { };
  }
  
  #[Test]
  public function setProcessors() {
    $processors= [new Optional(), new Required()];
    $this->fixture->setProcessors($processors);
    $this->assertEquals($processors, $this->fixture->getProcessors());
  }

  #[Test]
  public function withProcessors() {
    $processors= [new Optional(), new Required()];
    $this->fixture->withProcessors($processors);
    $this->assertEquals($processors, $this->fixture->getProcessors());
  }

  #[Test]
  public function withProcessors_returns_fixture() {
    $this->assertEquals($this->fixture, $this->fixture->withProcessors([]));
  }

  #[Test]
  public function addProcessor() {
    $processors= [new Optional(), new Required()];
    $this->fixture->addProcessor($processors[0]);
    $this->fixture->addProcessor($processors[1]);
    $this->assertEquals($processors, $this->fixture->getProcessors());
  }

  #[Test]
  public function addProcessor_returns_added_processor() {
    $processor= new Optional();
    $this->assertEquals($processor, $this->fixture->addProcessor($processor));
  }
}