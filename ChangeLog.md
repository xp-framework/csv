CSV File handling for the XP Framework ChangeLog
========================================================================

## ?.?.? / ????-??-??

* Merged PR #2: Make CsvReader and CsvWriter closeable - @thekid

## 10.0.0 / 2020-10-21

* Implemented xp-framework/rfc#341, dropping compatibility with XP 9
  (@thekid)
* Made compatible with PHP 8.1 - add `ReturnTypeWillChange` attributes to
  iterator, see https://wiki.php.net/rfc/internal_method_return_types

## 9.0.0 / 2020-04-10

* Implemented xp-framework/rfc#334: Drop PHP 5.6:
  . **Heads up:** Minimum required PHP version now is PHP 7.0.0
  . Rewrote code base, grouping use statements
  . Converted `newinstance` to anonymous classes
  . Rewrote `isset(X) ? X : default` to `X ?? default`
  (@thekid)

## 8.0.2 / 2019-12-02

* Made compatible with XP 10 - @thekid
* Replaced xp::stringOf() with util.Objects::stringOf() - @thekid

## 8.0.1 / 2018-04-02

* Fixed compatiblity with PHP 7.2 - @thekid

## 8.0.0 / 2017-06-15

* **Heads up:** Dropped PHP 5.5 support - @thekid
* Added forward compatibility with XP 9.0.0 - @thekid

## 7.1.0 / 2016-08-29

* Added forward compatibility with XP 8.0.0 - @thekid

## 7.0.0 / 2016-02-21

* **Adopted semantic versioning. See xp-framework/rfc#300** - @thekid 
* Added version compatibility with XP 7 - @thekid

## 6.2.0 / 2015-12-14

* **Heads up**: Changed minimum XP version to XP 6.5.0, and with it the
  minimum PHP version to PHP 5.5.
  (@thekid)

## 6.1.1 / 2015-06-30

* Added handling to skip empty lines or lines consisting completely of
  whitespace (space, newlines and tabs).
  (@thekid)

## 6.1.0 / 2015-05-24

* Merged xp-framework/csv#1: Iterable lines - @thekid

## 6.0.1 / 2015-02-12

* Changed dependency to use XP 6.0 (instead of dev-master) - @thekid

## 6.0.0 / 2015-01-10

* Rewrite `CsvObjectWriter` and `CsvObjectReader` to use reflection and
  instead of using array-cast trick and then accessing mangled names.
  (@thekid)
* Heads up: Converted classes to PHP 5.3 namespaces - (@thekid)
