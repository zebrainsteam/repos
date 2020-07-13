# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2020-07-13
### Added
- Transactions are described in Repository contract
- Added support for transactions in ArrayRepository
- DuckTyper helper is added

### Changed
- Result and Query objects are removed from Repository contract because I decided to make this contract more general here

### Removed
- Result object is removed becouse I decided that is has nothing to do with repository responsibility
- HasMeta trait is removed because it has no meaning without Result object