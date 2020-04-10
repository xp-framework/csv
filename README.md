CSV File handling for the XP Framework
========================================================================

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-framework/csv.svg)](http://travis-ci.org/xp-framework/csv)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Requirews PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.png)](http://php.net/)
[![Latest Stable Version](https://poser.pugx.org/xp-framework/csv/version.png)](https://packagist.org/packages/xp-framework/csv)

Contains the XP Framework's CSV API

Reading
------
CSV data can be read off any input stream. Character set decoding is accomplished by passing a charset to TextReader, or `NULL` to use auto-detection.

```php
use text\csv\CsvListReader;
use io\streams\{TextReader, FileInputStream};

$in= new CsvListReader(new TextReader(new FileInputStream('in.csv')));

Console::writeLine($in->getHeaders());
while ($record= $in->read()) {
  Console::writeLine('- ', $record);
}

$in->close();
```

Writing
-------
CSV data can be written to any output stream. Character set encoding is accomplished by passing a charset to TextWriter.

```php
use text\csv\CsvListWriter;
use io\streams\{TextWriter, FileOutputStream};

$out= new CsvListWriter(new TextWriter(new FileOutputStream('out.csv')));

$out->setHeader(['name', 'city', 'zip']);
$out->write(['Timm', 'Karlsruhe', 76137]);
$out->write(['Alex', 'Karlsruhe', 76131]);

$out->close();
```

Format
------
CSV files usually use the semi-colon to separate values. Depending on the file we're parsing, this might be a different character. Both readers and writers accept an optional second parameter with which the format can be changed.

```php
$format= (new CsvFormat())->withDelimiter(',');
$format= CsvFormat::$COMMAS;    // Short-hand for the above

$writer= new CsvListWriter(..., $format);
$reader= new CsvListReader(..., $format);
```