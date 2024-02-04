<?php namespace text\csv\unittest;

use test\{Assert, Before, Test};
use text\csv\AbstractCsvProcessor;
use text\csv\processors\constraint\{Optional, Required};

class ProcessorAccessorsTest {
  private $fixture;

  #[Before]
  public function setUp() {
    $this->fixture= new class() extends AbstractCsvProcessor { };
  }
  
  #[Test]
  public function setProcessors() {
    $processors= [new Optional(), new Required()];
    $this->fixture->setProcessors($processors);
    Assert::equals($processors, $this->fixture->getProcessors());
  }

  #[Test]
  public function withProcessors() {
    $processors= [new Optional(), new Required()];
    $this->fixture->withProcessors($processors);
    Assert::equals($processors, $this->fixture->getProcessors());
  }

  #[Test]
  public function withProcessors_returns_fixture() {
    Assert::equals($this->fixture, $this->fixture->withProcessors([]));
  }

  #[Test]
  public function addProcessor() {
    $processors= [new Optional(), new Required()];
    $this->fixture->addProcessor($processors[0]);
    $this->fixture->addProcessor($processors[1]);
    Assert::equals($processors, $this->fixture->getProcessors());
  }

  #[Test]
  public function addProcessor_returns_added_processor() {
    $processor= new Optional();
    Assert::equals($processor, $this->fixture->addProcessor($processor));
  }
}