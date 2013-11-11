<?php namespace text\csv;

use io\streams\TextWriter;

/**
 * Abstract base class
 *
 * @see   xp://text.csv.CsvListWriter
 * @see   xp://text.csv.CsvObjectWriter
 * @see   xp://text.csv.CsvBeanWriter
 */
abstract class CsvWriter extends \text\csv\AbstractCsvProcessor {
  protected $writer= null;
  protected $format= null;
  protected $line= 0;

  /**
   * Creates a new CSV writer writing data to a given TextWriter
   *
   * @param   io.streams.TextWriter writer
   * @param   text.csv.CsvFormat format
   */
  public function  __construct(TextWriter $writer, CsvFormat $format= null) {
    $this->writer= $writer;
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
      throw new \lang\IllegalStateException('Cannot writer headers - already started writing data');
    }
    return $this->writeValues($headers, true);
  }

  /**
   * Raise an exception
   *
   * @param   string message
   */
  protected function raise($message) {
    throw new \lang\FormatException(sprintf('Line %d: %s', $this->line, $message));
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
        } catch (\lang\Throwable $e) {
          $this->raise($e->getMessage());
        }
      }
      
      $i++;
      $line.= $this->format->format((string)$value);
    }
    $this->line++;
    $this->writer->writeLine(substr($line, 0, -1));
  }
}
