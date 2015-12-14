CSV File handling for the XP Framework ChangeLog
========================================================================

## ?.?.? / ????-??-??

## 6.2.0 / 2015-12-14

* **Heads up**: Changed minimum XP version to run webtests to XP
  6.5.0, and with it the minimum PHP version to PHP 5.5
  (@thekid)

## 6.1.1 / 2015-06-30

* Added handling to skip empty lines or lines consisting completely of
  whitespace (space, newlines and tabs).
  (@thekid)

## 6.1.0 / 2015-05-24

* Merged xp-framework/csv#1: Iterable lines - @thekid

## 6.0.1 / 2015-02-12

* Changed dependency to use XP ~6.0 (instead of dev-master) - @thekid

## 6.0.0 / 2015-01-10

* Rewrite `CsvObjectWriter` and `CsvObjectReader` to use reflection and
  instead of using array-cast trick and then accessing mangled names.
  (@thekid)
* Heads up: Converted classes to PHP 5.3 namespaces - (@thekid)
