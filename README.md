CSV File handling for the XP Framework
========================================================================

[![Build status on GitHub](https://github.com/xp-framework/csv/workflows/Tests/badge.svg)](https://github.com/xp-framework/csv/actions)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Requires PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.svg)](http://php.net/)
[![Supports PHP 8.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-8_0plus.svg)](http://php.net/)
[![Latest Stable Version](https://poser.pugx.org/xp-framework/csv/version.png)](https://packagist.org/packages/xp-framework/csv)

Contains the XP Framework's CSV API

Reading
-------
CSV data can be read off any input stream, reader or channel:

```php
use util\cmd\Console;
use text\csv\CsvListReader;
use io\streams\FileInputStream;

$csv= new CsvListReader(new FileInputStream('in.csv'));
Console::writeLine($csv->getHeaders());

while ($record= $csv->read()) {
  Console::writeLine('- ', $record);
}

$csv->close();
```

Writing
-------
CSV data can be written to any output stream, writer or channel:

```php
use util\cmd\Console;
use text\csv\CsvListWriter;
use io\streams\FileOutputStream;

$csv= new CsvListWriter(new FileOutputStream('out.csv'));

$csv->setHeader(['name', 'city', 'zip']);
$csv->write(['Timm', 'Karlsruhe', 76137]);
$csv->write(['Alex', 'Karlsruhe', 76131]);

$csv->close();
```

Character set conversion
------------------------
Character set decoding is accomplished by passing a TextReader or TextWriter instance with a given character set:

```php
use text\csv\{CsvListReader, CsvListWriter};
use io\streams\{FileInputStream, FileOutputStream, TextReader, TextWriter};

// Read from in.csv, which is in cp1252
$in= new CsvListReader(new TextReader(new FileInputStream('in.csv'), 'cp1252'));

// Write to out.csv, converting everything to cp1252
$out= new CsvListWriter(new TextWriter(new FileOutputStream('out.csv'), 'cp1252'));
```

Format
------
CSV files usually use the semi-colon to separate values. Depending on the file we're parsing, this might be a different character. Both readers and writers accept an optional second parameter with which the format can be changed.

```php
use text\csv\{CsvFormat, CsvListReader, CsvListWriter};

$format= (new CsvFormat())->withDelimiter(',');
$format= CsvFormat::$COMMAS;    // Short-hand for the above

$writer= new CsvListWriter(..., $format);
$reader= new CsvListReader(..., $format);
```