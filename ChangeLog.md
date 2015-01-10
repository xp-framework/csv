CSV File handling for the XP Framework ChangeLog
========================================================================

## ?.?.? / ????-??-??

## 6.0.0 / 2015-01-10

* Rewrite `CsvObjectWriter` and `CsvObjectReader` to use reflection and
  instead of using array-cast trick and then accessing mangled names.
  (@thekid)
* Heads up: Converted classes to PHP 5.3 namespaces - (@thekid)
