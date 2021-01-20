# Classmate Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

## [1.0.0] - 2021-01-18

### Added

- Initial release

## [1.0.1] - 2021-01-18

### Fixed

- Fixed conflicting version

## [1.0.2] - 2021-01-19

### Added

- `ClassList::normalize` method

### Changed

- `ClassList::asClasses` now returns an array, so it is compatible with the `attr` filter and function.

## [1.0.3] - 2021-01-19

### Changed

- Log a warning when calling `remove` to remove a non-existent class.
- Throw exception when trying to get a non-existent key.

## [1.0.4] - 2021-01-20

### Fixed

- Fix potential parse order issues, see: https://github.com/timkelty/craftcms-classmate/issues/2
