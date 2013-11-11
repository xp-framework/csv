<?php namespace text\csv\unittest;

use text\csv\CsvListReader;
use text\csv\processors\lookup\GetDataSet;
use text\csv\processors\lookup\FindDataSet;
use rdbms\unittest\dataset\Job;
use rdbms\unittest\dataset\JobFinder;
use rdbms\unittest\mock\MockConnection;
use rdbms\unittest\mock\MockResultSet;
use io\streams\MemoryInputStream;

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
      throw new \unittest\PrerequisitesNotMetError('rdbms module not available', null, array('rdbms'));
    }
  }

  /**
   * Mock connection registration
   */  
  #[@beforeClass]
  public static function registerMockConnection() {
    \rdbms\DriverManager::register('mock', \lang\XPClass::forName('rdbms.unittest.mock.MockConnection'));
    Job::getPeer()->setConnection(\rdbms\DriverManager::getConnection('mock://mock/JOBS?autoconnect=1'));
  }

  /**
   * Creates a new list reader
   *
   * @param   string str
   * @param   text.csv.CsvFormat format
   * @return  text.csv.CsvListReader
   */
  protected function newReader($str, \text\csv\CsvFormat $format= null) {
    return new CsvListReader(new \io\streams\TextReader(new MemoryInputStream($str)), $format);
  }

  /**
   * Test successful lookup
   *
   */
  #[@test]
  public function getByPrimary() {
    Job::getPeer()->getConnection()->setResultSet(new MockResultSet(array(
      array('job_id' => 1549, 'title' => 'Developer')
    )));
    $in= $this->newReader("job_id;title\n1549;10248")->withProcessors(array(
      new GetDataSet(create(new JobFinder())->method('byPrimary')),
      null
    ));
    $in->getHeaders();
    $list= $in->read();
    $this->assertClass($list[0], 'net.xp_framework.unittest.rdbms.dataset.Job');
    $this->assertEquals(1549, $list[0]->getJob_id());
  }

  /**
   * Test successful lookup
   *
   */
  #[@test]
  public function findByTitle() {
    Job::getPeer()->getConnection()->setResultSet(new MockResultSet(array(
      array('job_id' => 1549, 'title' => 'Developer')
    )));
    $in= $this->newReader("title;external_id\nDeveloper;10248")->withProcessors(array(
      new GetDataSet(create(new JobFinder())->method('similarTo')),
      null
    ));
    $in->getHeaders();
    $list= $in->read();
    $this->assertClass($list[0], 'rdbms.unittest.dataset.Job');
    $this->assertEquals(1549, $list[0]->getJob_id());
  }

  /**
   * Test lookup not returning a result
   *
   */
  #[@test]
  public function getNotFound() {
    Job::getPeer()->getConnection()->setResultSet(new MockResultSet(array()));
    $in= $this->newReader("job_id;title\n1549;Developer")->withProcessors(array(
      new GetDataSet(create(new JobFinder())->method('byPrimary')),
      null
    ));
    $in->getHeaders();
    try {
      $in->read();
      $this->fail('Lookup succeeded', null, 'lang.FormatException');
    } catch (\lang\FormatException $expected) { }
  }

  /**
   * Test lookup not returning a result
   *
   */
  #[@test]
  public function findNotFound() {
    Job::getPeer()->getConnection()->setResultSet(new MockResultSet(array()));
    $in= $this->newReader("job_id;title\n1549;Developer")->withProcessors(array(
      new FindDataSet(create(new JobFinder())->method('byPrimary')),
      null
    ));
    $in->getHeaders();
    $list= $in->read();
    $this->assertNull($list[0]);
  }

  /**
   * Test lookup returning more than one result
   *
   */
  #[@test]
  public function ambiguous() {
    Job::getPeer()->getConnection()->setResultSet(new MockResultSet(array(
      array('job_id' => 1549, 'title' => 'Developer'),
      array('job_id' => 1549, 'title' => 'Doppelgänger'),
    )));
    $in= $this->newReader("job_id;title\n1549;10248")->withProcessors(array(
      new GetDataSet(create(new JobFinder())->method('byPrimary')),
      null
    ));
    $in->getHeaders();
    try {
      $in->read();
      $this->fail('Lookup succeeded', null, 'lang.FormatException');
    } catch (\lang\FormatException $expected) { }
  }
}
