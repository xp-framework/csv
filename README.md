CSV File handling for the XP Framework
========================================================================

Contains the XP Framework's CSV API

Reading
=======
CSV data can be read off any input stream. Character set decoding
is accomplished by passing a charset to TextReader, or `NULL`
to use auto-detection.

```
use text\csv\CsvListReader;
use io\streams\TextReader;
use io\streams\FileInputStream;

$in= new CsvListReader(new TextReader(new FileInputStream('in.csv')));

$header= $in->getHeaders();
while ($record= $in->read()) {
  Console::writeLine('- ', $record);
}

$in->close();
```

Writing
=======
CSV data can be written to any output stream. Character set encoding
is accomplished by passing a charset to TextWriter.

```
use text\csv\CsvListWriter;
use io\streams\TextWriter;
use io\streams\FileOutputStream;

$out= new CsvListWriter(new TextWriter(new FileOutputStream('out.csv')));

$out->setHeader(array('name', 'city', 'zip'));
$out->write(array('Timm', 'Karlsruhe', 76137));
$out->write(array('Alex', 'Karlsruhe', 76131));

$out->close();
```

Format
======
CSV files usually use the semi-colon to separate values. Depending on the 
file we're parsing, this might be a different character. Both readers and
writers accept an optional second parameter with which the format can be
changed.

```php
$format= create(new CsvFormat())->withDelimiter(',');
$format= CsvFormat::$COMMAS;    // Short-hand for the above

$writer= new CsvListWriter(..., $format);
$reader= new CsvListReader(..., $format);
```