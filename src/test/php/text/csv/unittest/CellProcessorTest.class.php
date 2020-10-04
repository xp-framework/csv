<?php namespace text\csv\unittest;

use io\streams\{MemoryInputStream, MemoryOutputStream, TextReader, TextWriter};
use lang\{FormatException, XPClass};
use text\csv\processors\constraint\{Optional, Required, Unique};
use text\csv\processors\{AsBool, AsDate, AsDouble, AsEnum, AsInteger, FormatBool, FormatDate, FormatEnum, FormatNumber};
use text\csv\{CellProcessor, CsvFormat, CsvListReader, CsvListWriter};
use unittest\{Expect, Test};
use util\{Date, Objects};

/**
 * TestCase
 *
 * @see      xp://text.csv.CellProcessor
 */
class CellProcessorTest extends \unittest\TestCase {
  protected $out= null;

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

  /**
   * Creates a new list writer
   *
   * @param   text.csv.CsvFormat format
   * @return  text.csv.CsvListWriter
   */
  protected function newWriter(CsvFormat $format= null) {
    $this->out= new MemoryOutputStream();
    return new CsvListWriter(new TextWriter($this->out), $format);
  }

  #[Test]
  public function asInteger() {
    $in= $this->newReader('1549;Timm')->withProcessors([
      new AsInteger(),
      null
    ]);
    $this->assertEquals([1549, 'Timm'], $in->read());
  }

  #[Test, Expect(FormatException::class)]
  public function stringAsInteger() {
    $this->newReader('A;Timm')->withProcessors([
      new AsInteger(),
      null
    ])->read();
  }

  #[Test, Expect(FormatException::class)]
  public function emptyAsInteger() {
    $this->newReader(';Timm')->withProcessors([
      new AsInteger(),
      null
    ])->read();
  }

  #[Test]
  public function asDouble() {
    $in= $this->newReader('1.5;em')->withProcessors([
      new AsDouble(),
      null
    ]);
    $this->assertEquals([1.5, 'em'], $in->read());
  }

  #[Test, Expect(FormatException::class)]
  public function stringAsDouble() {
    $this->newReader('A;em')->withProcessors([
      new AsDouble(),
      null
    ])->read();
  }

  #[Test, Expect(FormatException::class)]
  public function emptyAsDouble() {
    $this->newReader(';em')->withProcessors([
      new AsDouble(),
      null
    ])->read();
  }

  #[Test]
  public function asDate() {
    $in= $this->newReader('2009-09-09 15:45;Order placed')->withProcessors([
      new AsDate(),
      null
    ]);
    $this->assertEquals([new Date('2009-09-09 15:45'), 'Order placed'], $in->read());
  }

  #[Test, Expect(FormatException::class)]
  public function invalidAsDate() {
    $this->newReader('YYYY-MM-DD HH:MM;Order placed')->withProcessors([
      new AsDate(),
      null
    ])->read();
  }

  #[Test, Expect(FormatException::class)]
  public function emptyAsDate() {
    $this->newReader(';Order placed')->withProcessors([
      (new AsDate()),
      null
    ])->read();
  }

  #[Test, Expect(FormatException::class)]
  public function emptyAsDateWithNullDefault() {
    $this->newReader(';Order placed')->withProcessors([
      (new AsDate())->withDefault(null),
      null
    ])->read();
  }

  #[Test]
  public function emptyAsDateWithDefault() {
    $now= Date::now();
    $in= $this->newReader(';Order placed')->withProcessors([
      (new AsDate())->withDefault($now),
      null
    ]);
    $this->assertEquals([$now, 'Order placed'], $in->read());
  }

  #[Test]
  public function formatDate() {
    $writer= $this->newWriter()->withProcessors([
      new FormatDate('Y-m-d H:i'),
      null
    ]);
    $writer->write([new Date('2009-09-09 15:45'), 'Order placed']);
    $this->assertEquals("2009-09-09 15:45;Order placed\n", $this->out->getBytes());
  }

  #[Test, Expect(FormatException::class)]
  public function formatNonDate() {
    $this->newWriter()->withProcessors([
      new FormatDate('Y-m-d H:i'),
      null
    ])->write([$this, 'Order placed']);
  }

  #[Test, Expect(FormatException::class)]
  public function formatNull() {
    $this->newWriter()->withProcessors([
      new FormatDate('Y-m-d H:i'),
      null
    ])->write([null, 'Order placed']);
  }

  #[Test]
  public function formatNullWithDefault() {
    $now= Date::now();
    $writer= $this->newWriter()->withProcessors([
      (new FormatDate('Y-m-d H:i'))->withDefault($now),
      null
    ]);
    $writer->write([null, 'Order placed']);
    $this->assertEquals($now->toString('Y-m-d H:i').";Order placed\n", $this->out->getBytes());
  }

  #[Test]
  public function trueAsBool() {
    $in= $this->newReader('Timm;true')->withProcessors([
      null,
      new AsBool()
    ]);
    $this->assertEquals(['Timm', true], $in->read());
  }

  #[Test]
  public function oneAsBool() {
    $in= $this->newReader('Timm;1')->withProcessors([
      null,
      new AsBool()
    ]);
    $this->assertEquals(['Timm', true], $in->read());
  }

  #[Test]
  public function yAsBool() {
    $in= $this->newReader('Timm;Y')->withProcessors([
      null,
      new AsBool()
    ]);
    $this->assertEquals(['Timm', true], $in->read());
  }

  #[Test]
  public function falseAsBool() {
    $in= $this->newReader('Timm;false')->withProcessors([
      null,
      new AsBool()
    ]);
    $this->assertEquals(['Timm', false], $in->read());
  }

  #[Test]
  public function zeroAsBool() {
    $in= $this->newReader('Timm;0')->withProcessors([
      null,
      new AsBool()
    ]);
    $this->assertEquals(['Timm', false], $in->read());
  }

  #[Test]
  public function nAsBool() {
    $in= $this->newReader('Timm;N')->withProcessors([
      null,
      new AsBool()
    ]);
    $this->assertEquals(['Timm', false], $in->read());
  }

  #[Test, Expect(FormatException::class)]
  public function emptyAsBool() {
    $this->newReader('Timm;')->withProcessors([
      null,
      new AsBool()
    ])->read();
  }

  #[Test]
  public function formatTrue() {
    $writer= $this->newWriter()->withProcessors([
      null,
      new FormatBool()
    ]);
    $writer->write(['A', true]);
    $this->assertEquals("A;true\n", $this->out->getBytes());
  }

  #[Test]
  public function formatTrueAsY() {
    $writer= $this->newWriter()->withProcessors([
      null,
      new FormatBool('Y', 'N')
    ]);
    $writer->write(['A', true]);
    $this->assertEquals("A;Y\n", $this->out->getBytes());
  }

  #[Test]
  public function formatFalse() {
    $writer= $this->newWriter()->withProcessors([
      null,
      new FormatBool()
    ]);
    $writer->write(['A', false]);
    $this->assertEquals("A;false\n", $this->out->getBytes());
  }

  #[Test]
  public function formatFalseAsN() {
    $writer= $this->newWriter()->withProcessors([
      null,
      new FormatBool('Y', 'N')
    ]);
    $writer->write(['A', false]);
    $this->assertEquals("A;N\n", $this->out->getBytes());
  }

  #[Test]
  public function pennyCoin() {
    $in= $this->newReader('200;penny')->withProcessors([
      null,
      new AsEnum(XPClass::forName('text.csv.unittest.Coin'))
    ]);
    $this->assertEquals(['200', Coin::$penny], $in->read());
  }

  #[Test, Expect(FormatException::class)]
  public function invalidCoin() {
    $this->newReader('200;dollar')->withProcessors([
      null,
      new AsEnum(XPClass::forName('text.csv.unittest.Coin'))
    ])->read();
  }

  #[Test, Expect(FormatException::class)]
  public function emptyCoin() {
    $this->newReader('200;')->withProcessors([
      null,
      new AsEnum(XPClass::forName('text.csv.unittest.Coin'))
    ])->read();
  }

  /**
   * Test FormatEnum processor
   *
   */
  #[Test]
  public function formatEnumValue() {
    $writer= $this->newWriter()->withProcessors([
      null,
      new FormatEnum()
    ]);
    $writer->write(['200', Coin::$penny]);
    $this->assertEquals("200;penny\n", $this->out->getBytes());
  }

  #[Test, Expect(FormatException::class)]
  public function formatNonEnum() {
    $this->newWriter()->withProcessors([
      null,
      new FormatEnum()
    ])->write(['200', $this]);
  }

  #[Test]
  public function formatNumber() {
    $writer= $this->newWriter()->withProcessors([
      (new FormatNumber())->withFormat(5, '.'),
      (new FormatNumber())->withFormat(2, ',', "'")
    ]);
    $writer->write([3.75, 10000000.5]);
    $this->assertEquals("3.75000;10'000'000,50\n", $this->out->getBytes());
  }

  #[Test]
  public function formatNumberNull() {
    $writer= $this->newWriter()->withProcessors([
      (new FormatNumber())->withFormat(2, '.')
    ]);
    $writer->write([null]);
    $this->assertEquals("0.00\n", $this->out->getBytes());
  }

  #[Test, Expect(FormatException::class)]
  public function formatNotANumber() {
    $this->newWriter()->withProcessors([
      (new FormatNumber())->withFormat(2, '.')
    ])->write(['Hello']);
  }

  #[Test]
  public function optionalString() {
    $in= $this->newReader('200;OK')->withProcessors([
      null,
      new Optional()
    ]);
    $this->assertEquals(['200', 'OK'], $in->read());
  }
  
  #[Test]
  public function optionalEmpty() {
    $in= $this->newReader('666;')->withProcessors([
      null,
      new Optional()
    ]);
    $this->assertEquals(['666', null], $in->read());
  }

  #[Test]
  public function optionalEmptyWithDefault() {
    $in= $this->newReader('666;')->withProcessors([
      null,
      (new Optional())->withDefault('(unknown)')
    ]);
    $this->assertEquals(['666', '(unknown)'], $in->read());
  }

  #[Test]
  public function writeOptionalString() {
    $this->newWriter()->withProcessors([
      new Optional(),
      null
    ])->write(['A', 'Test']);
    $this->assertEquals("A;Test\n", $this->out->getBytes());
  }

  #[Test]
  public function writeOptionalEmpty() {
    $this->newWriter()->withProcessors([
      new Optional(),
      null
    ])->write(['', 'Test']);
    $this->assertEquals(";Test\n", $this->out->getBytes());
  }

  #[Test]
  public function writeOptionalNull() {
    $this->newWriter()->withProcessors([
      new Optional(),
      null
    ])->write([null, 'Test']);
    $this->assertEquals(";Test\n", $this->out->getBytes());
  }

  #[Test]
  public function writeOptionalWithDefault() {
    $this->newWriter()->withProcessors([
      (new Optional())->withDefault('(unknown)'),
      null
    ])->write(['', 'Test']);
    $this->assertEquals("(unknown);Test\n", $this->out->getBytes());
  }

  #[Test]
  public function writeOptionalNullWithDefault() {
    $this->newWriter()->withProcessors([
      (new Optional())->withDefault('(unknown)'),
      null
    ])->write([null, 'Test']);
    $this->assertEquals("(unknown);Test\n", $this->out->getBytes());
  }

  #[Test]
  public function requiredString() {
    $in= $this->newReader('200;OK')->withProcessors([
      null,
      new Required()
    ]);
    $this->assertEquals(['200', 'OK'], $in->read());
  }
  
  #[Test, Expect(FormatException::class)]
  public function requiredEmpty() {
    $this->newReader('666;')->withProcessors([
      null,
      new Required()
    ])->read();
  }

  #[Test]
  public function writeRequired() {
    $this->newWriter()->withProcessors([
      new Required(),
      null
    ])->write(['A', 'B']);
    $this->assertEquals("A;B\n", $this->out->getBytes());
  }

  #[Test, Expect(FormatException::class)]
  public function writeEmptyRequired() {
    $this->newWriter()->withProcessors([
      new Required(),
      null
    ])->write(['', 'Test']);
  }

  #[Test]
  public function chainingRequired() {
    $in= $this->newReader('200;OK')->withProcessors([
      new Required(new AsInteger()),
      new Required()
    ]);
    $this->assertEquals([200, 'OK'], $in->read());
  }

  #[Test]
  public function chainingOptional() {
    $in= $this->newReader('200;')->withProcessors([
      new Optional(new AsInteger()),
      new Optional(new AsInteger())
    ]);
    $this->assertEquals([200, null], $in->read());
  }

  #[Test]
  public function readUnique() {
    $in= $this->newReader("200;OK\n200;NACK")->withProcessors([
      new Unique(),
      null
    ]);
    $this->assertEquals(['200', 'OK'], $in->read());
    try {
      $in->read();
      $this->fail('Duplicate value not detected', null, 'lang.FormatException');
    } catch (FormatException $expected) { }
  }

  #[Test]
  public function writeUnique() {
    $writer= $this->newWriter()->withProcessors([
      new Unique(),
      null,
    ]);

    $writer->write(['200', 'OK']);
    try {
      $writer->write(['200', 'NACK']);
      $this->fail('Duplicate value not detected', null, 'lang.FormatException');
    } catch (FormatException $expected) { }

    $this->assertEquals("200;OK\n", $this->out->getBytes());
  }
  
  /**
   * Creates a cell processor that checks for an unwanted value and
   * upon encountering it, throws a FormatException
   *
   * @param   var value
   * @return  text.csv.CellProcessor
   */
  protected function newUnwantedValueProcessor($value) {
    return new class($value) extends CellProcessor {
      protected $unwanted= NULL;
      
      public function __construct($value, $next= NULL) {
        parent::__construct($next);
        $this->unwanted= $value;
      }
      
      public function process($in) {
        if ($this->unwanted !== $in) return $this->proceed($in);
        throw new FormatException("Unwanted value ".Objects::stringOf($this->unwanted)." encountered");
      }
    };
  }

  #[Test]
  public function processorExceptionsDoNotBreakReading() {
    $in= $this->newReader("200;OK\n404;Not found")->withProcessors([
      $this->newUnwantedValueProcessor('200'),
      null
    ]);
    try {
      $in->read();
      $this->fail('Unwanted value not detected', null, 'lang.FormatException');
    } catch (FormatException $expected) { }
    $this->assertEquals(['404', 'Not found'], $in->read());
  }

  #[Test]
  public function processorExceptionsDoNotBreakReadingMultiline() {
    $in= $this->newReader("200;'OK\nThank god'\n404;'Not found\nFamous'", (new \text\csv\CsvFormat())->withQuote("'"))->withProcessors([
      $this->newUnwantedValueProcessor('200'),
      null
    ]);
    try {
      $in->read();
      $this->fail('Unwanted value not detected', null, 'lang.FormatException');
    } catch (FormatException $expected) { }
    $this->assertEquals(['404', "Not found\nFamous"], $in->read());
  }

  #[Test]
  public function processorExceptionsDoNotBreakWriting() {
    $writer= $this->newWriter()->withProcessors([
      $this->newUnwantedValueProcessor('200'),
      null
    ]);

    try {
      $writer->write(['200', 'OK']);
      $this->fail('Unwanted value not detected', null, 'lang.FormatException');
    } catch (FormatException $expected) { }

    $writer->write(['404', 'Not found']);
    $this->assertEquals("404;Not found\n", $this->out->getBytes());
  }

  #[Test]
  public function processorExceptionsDoNotCausePartialWriting() {
    $writer= $this->newWriter()->withProcessors([
      null,
      $this->newUnwantedValueProcessor('Internal Server Error')
    ]);

    try {
      $writer->write(['500', 'Internal Server Error']);
      $this->fail('Unwanted value not detected', null, 'lang.FormatException');
    } catch (FormatException $expected) { }

    $writer->write(['404', 'Not found']);
    $this->assertEquals("404;Not found\n", $this->out->getBytes());
  }
}