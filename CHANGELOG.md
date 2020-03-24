# Change Log for OXID econdaanalytics module powered by Econda

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Undecided] - Unreleased

### Fixed
- Test compatibility with removal of oxconfig encoding feature.

## [2.0.0] - Unreleased

### Changed
- Increase `oxid-esales/econda-tracking-component` version.
- Increase `php` version.

### Fixed
- Test compatibility with removal of oxconfig encoding feature.
- Fixed compatibility issues regarding setUp and tearDown phpunit methods.

## [1.2.0] - 2019-07-05

### Changed
- Ensure module works with php 7.2
- Increase required econda tracking component version to 1.0.4

### Fixed
- Replaced deprecated getConfig usages

### Removed
- Dropped support of php 7.0

## [1.1.1] - Unreleased

### Fixed
- Fixed integration tests

## [1.1.0] - 2019-04-29

### Added
- New block `oeecondaanalytics_cookienote` in `Application/views/widget/header/cookienote.tpl`

## [1.0.4] - 2018-12-06

### Fixed
- Mall URL to javascript file [PR-1](https://github.com/OXID-eSales/econda-analytics-module/pull/1)

## [1.0.3] - 2018-11-26

### Changed
- Update module version in `metadata.php`

## [1.0.2] - 2018-11-26

### Fixed
- Fix issue when sometimes empty SiteId and PageId is being sent.

## [1.0.1] - 2018-11-20

### Fixed
- Wrong shop data loaded in admin when clicking on "Analytics" menu element.

## [1.0.0] - 2018-11-19

[2.0.0]: https://github.com/OXID-eSales/econda-analytics-module/compare/v1.2.0...master
[1.2.0]: https://github.com/OXID-eSales/econda-analytics-module/compare/b-1.1.x...v1.2.0
[1.1.1]: https://github.com/OXID-eSales/econda-analytics-module/compare/v1.1.0...b-1.1.x
[1.1.0]: https://github.com/OXID-eSales/econda-analytics-module/compare/v1.0.4...v1.1.0
[1.0.4]: https://github.com/OXID-eSales/econda-analytics-module/compare/v1.0.3...v1.0.4
[1.0.3]: https://github.com/OXID-eSales/econda-analytics-module/compare/v1.0.2...v1.0.3
[1.0.2]: https://github.com/OXID-eSales/econda-analytics-module/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/OXID-eSales/econda-analytics-module/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/OXID-eSales/econda-analytics-module/compare/e1600e81b37fe0128c79b936015f54b21e74034c...v1.0.0