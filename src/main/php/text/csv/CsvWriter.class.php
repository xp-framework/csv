<?php namespace text\csv;

use io\streams\{Writer, StringWriter};
use lang\{FormatException, IllegalStateException, Throwable, Closeable};

/**
 * Abstract base class
 *
 * @see  text.csv.CsvListWriter
 * @see  text.csv.CsvObjectWriter
 * @see  text.csv.CsvBeanWriter
 */
abstract class CsvWriter extends AbstractCsvProcessor implements Closeable {
  protected $writer= null;
  protected $format= null;
  protected $line= 0;

  /**
   * Creates a new CSV writer writing data
   *
   * @param  io.streams.Writer|io.streams.OutputStream|io.Channel|string $out
   * @param  text.csv.CsvFormat $format
   */
  public function  __construct($out, CsvFormat $format= null) {
    $this->writer= $out instanceof Writer ? $out : new StringWriter($out);
    $this->format= $format ? $format : CsvFormat::$DEFAULT;
  }

  /**
   * Set header line
   *
   * @return  string[]
   * @throws  lang.IllegalStateException if writing has already started
   */
  public function setHeaders($headers) {
    if ($this->line > 0) {
      throw new IllegalStateException('Cannot writer headers - already started writing data');
    }
    return $this->writeValues($headers, true);
  }

  /**
   * Raise an exception
   *
   * @param   string message
   * @throws  lang.Throwable
   */
  protected function raise($message) {
    throw new FormatException(sprintf('Line %d: %s', $this->line, $message));
  }
  
  /**
   * Writes values
   *
   * @param   var[] values
   * @param   bool raw
   * @throws  lang.FormatException if a formatting error is detected
   */
  protected function writeValues($values, $raw= false) {
    $line= '';
    $i= 0;
    foreach ($values as $value) {
      if (!$raw && isset($this->processors[$i])) {
        try {
          $value= $this->processors[$i]->process($value);
        } catch (Throwable $e) {
          $this->raise($e->getMessage());
        }
      }
      
      $i++;
      $line.= $this->format->format((string)$value);
    }
    $this->line++;
    $this->writer->writeLine(substr($line, 0, -1));
  }

  /** @return void */
  public function close() {
    $this->writer->close();
  }
}