# Changelog

## 3.0.0

* [**Added**] [**BC BREAK**] Custom Doctrine types have been reworked and now they delegate the string to enum array conversion to specific services. While technically it is a BC break because some procted methods were changed, unless you were doing weird things you should be alright.

## 2.2.2

* [**Fixed**] `null` is now allowed in validators, and it must be explicitly filter using the `NotBlank` validator.

## 2.2.1

* [**Fixed**] `null` is now maintained when converting a list to database value, instead of creating an empty array.

## 2.2

* [**Added**] New `EnumListContraint` and `EnumListValidator` to work with Symfony's validator.

## 2.1

* [**Added**] New `SmartEnumExceptionInterface` allows you to override the default `fromValue` and `fromValues` methods and throw a custom exception, as long as it implements this interface.

## 2.0

* [**Added**] New `AbstractEnumJsonListType` and `AbstractEnumSimpleListType` Doctrine types to store data as JSON or comma-separated strings.
* [**Removed**] Dropped support for PHP 7.3, minimum requirement is now 7.4.

## 1.1

* [**Added**] New `EnumConstraint` and `EnumValidator` to work with Symfony's validator.
