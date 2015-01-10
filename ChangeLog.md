CSV File handling for the XP Framework ChangeLog
========================================================================

## ?.?.? / ????-??-??

* Rewrite `CsvObjectWriter` to use `Field::setAccessible()` instead of
  using array-cast trick and then accessing mangled names
  (@thekid)
* Heads up: Converted classes to PHP 5.3 namespaces - (@thekid)
