<?php namespace text\csv\unittest;

use io\streams\MemoryInputStream;
use io\streams\TextReader;
use lang\XPClass;
use rdbms\DriverManager;
use rdbms\unittest\dataset\Job;
use rdbms\unittest\dataset\JobFinder;
use rdbms\unittest\mock\MockConnection;
use rdbms\unittest\mock\MockResultSet;
use text\csv\CsvFormat;
use text\csv\CsvListReader;
use text\csv\processors\lookup\FindDataSet;
use text\csv\processors\lookup\GetDataSet;
use unittest\PrerequisitesNotMetError;

/**
 * TestCase
 *
 * @see      xp://text.csv.CellProcessor
 */
class DataSetCellProcessorTest extends \unittest\TestCase {

  /**
   * Verify RDBMS module is available
   */  
  #[@beforeClass]
  public static function verifyRdbms() {
    if (!class_exists('rdbms\DriverManager')) {
      throw new PrerequisitesNotMetError('rdbms module not available', null, ['rdbms']);
    }
  }

  /**
   * Mock connection registration
   */  
  #[@beforeClass]
  public static function registerMockConnection() {
    DriverManager::register('mock', XPClass::forName('rdbms.unittest.mock.MockConnection'));
    Job::getPeer()->setConnection(DriverManager::getConnection('mock://mock/JOBS?autoconnect=1'));
  }

  /**
   * Creates a new list reader
   *
   * @param   string str
   * @param   text.csv.CsvFormat format
   * @return  text.csv.CsvListReader
   */
  protected function newReader($str, CsvFormat $format= null) {
    return new CsvListReader(new TextReader(new MemoryInputStream($str)), $format);
  }

  #[@test]
  public function getByPrimary() {
    Job::getPeer()->getConnection()->setResultSet(new MockResultSet([
      ['job_id' => 1549, 'title' => 'Developer']
    ]));
    $in= $this->newReader("job_id;title\n1549;10248")->withProcessors([
      new GetDataSet(create(new JobFinder())->method('byPrimary')),
      null
    ]);
    $in->getHeaders();
    $list= $in->read();
    $this->assertClass($list[0], 'net.xp_framework.unittest.rdbms.dataset.Job');
    $this->assertEquals(1549, $list[0]->getJob_id());
  }

  #[@test]
  public function findByTitle() {
    Job::getPeer()->getConnection()->setResultSet(new MockResultSet([
      ['job_id' => 1549, 'title' => 'Developer']
    ]));
    $in= $this->newReader("title;external_id\nDeveloper;10248")->withProcessors([
      new GetDataSet(create(new JobFinder())->method('similarTo')),
      null
    ]);
    $in->getHeaders();
    $list= $in->read();
    $this->assertClass($list[0], 'rdbms.unittest.dataset.Job');
    $this->assertEquals(1549, $list[0]->getJob_id());
  }

  #[@test]
  public function getNotFound() {
    Job::getPeer()->getConnection()->setResultSet(new MockResultSet([]));
    $in= $this->newReader("job_id;title\n1549;Developer")->withProcessors([
      new GetDataSet(create(new JobFinder())->method('byPrimary')),
      null
    ]);
    $in->getHeaders();
    try {
      $in->read();
      $this->fail('Lookup succeeded', null, 'lang.FormatException');
    } catch (\lang\FormatException $expected) { }
  }

  #[@test]
  public function findNotFound() {
    Job::getPeer()->getConnection()->setResultSet(new MockResultSet([]));
    $in= $this->newReader("job_id;title\n1549;Developer")->withProcessors([
      new FindDataSet(create(new JobFinder())->method('byPrimary')),
      null
    ]);
    $in->getHeaders();
    $list= $in->read();
    $this->assertNull($list[0]);
  }

  #[@test]
  public function ambiguous() {
    Job::getPeer()->getConnection()->setResultSet(new MockResultSet([
      ['job_id' => 1549, 'title' => 'Developer'],
      ['job_id' => 1549, 'title' => 'Doppelgänger'],
    ]));
    $in= $this->newReader("job_id;title\n1549;10248")->withProcessors([
      new GetDataSet(create(new JobFinder())->method('byPrimary')),
      null
    ]);
    $in->getHeaders();
    try {
      $in->read();
      $this->fail('Lookup succeeded', null, 'lang.FormatException');
    } catch (\lang\FormatException $expected) { }
  }
}
