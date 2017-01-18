# Changelog

All Notable changes to `components` will be documented in this file.

## Next -

## Next - 2016-10-25

### Changed

- Added missing import of the `Schema` facade on migration stubs
- A default plain migration will be used if the name was not matched against a predefined structure (create, add, delete and drop)
- Add tests for all the different migration structures above

## 1.14.0 - 2016-10-19

### Added

- `component:make-notification` command to generate a notification class

### Changed

- Usage of the `lists()` method on the laravel collection has been removed in favor of `pluck()`
- Components can now overwrite the default migration and seed paths in the `component.json`  file

## 0.13.1 - 2016-09-09

### Changed

- Generated emails are now generated in the `Emails` folder by default

## 0.13.0 - 2016-09-08

### Changed

- Ability to set default value on the config() method of a component.
- Mail: Setting default value to config. Using that as the namespace.
- Using PSR2 for generated stubs


## 0.12.0 - 2016-09-08

### Added

- Generation of Mailable classes


## 0.11.2 - 2016-08-29

### Changed

- Using stable version of laravelcollective/html (5.3)

## 0.11.1 - 2016-08-25

### Changed

- Using stable development of laravelcollective/html


## 0.11 - 2016-08-24

### Added

- Adding `component:make-job` command to generate a job class
- Adding support for Laravel 5.3

### Changed

- Added force option to component:seed command

## 0.10 - 2016-08-10

### Added

- Experimental Laravel 5.3 support

### Changed

- Make sure the class name has `Controller` appended to it as well. Previously only the file had it suffixed.

### Removed

- Dependencies: `pingpong/support` and `pingpong/generators`

## 0.9 - 2016-07-30

### Added

- Adding a plain option to the generate controller command

### Changed

- Generate controller command now generates all resource methods

## 0.8 - 2016-07-28

### Fixed

- Component generation namespace now works with `StudlyCase`
- No component namespace fix (#13)

### Changed

- Using new service provider stub for component generation too

## 0.1 - 2016-06-27

Initial release
