# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.9.5] - 2023-09-07

### Fixed

- Don't check the mediasource policy in the frontend

## [2.9.4] - 2023-03-13

### Fixed

- Fix PHP warning: Undefined array key targetHeight & targetWidth [#115]
- Fix reset the TV value to a default value [#116]

## [2.9.3] - 2022-10-10

### Fixed

- Fix a swapped ratio for the displayed image, when no crop is created after selecting an image
- Fix chunk getlist processor not found in MODX 3.x [#113]

## [2.9.2] - 2022-08-01

### Changed

- Add an initial crop, when the editor image is closed without selecting a crop

### Fixed

- Fix the random use of the wrong crop for images larger than the editor window

## [2.9.1] - 2022-06-16

### Fixed

- Fix showing small initial crops for large images

### Changed

- Update jCrop to v0.9.15

## [2.9.0] - 2022-06-08

### Fixed

- Fix showing invalid size message for large images
- Fix some scrollbar glitches
- Fix editing template variable properties in MODX 3.0.1

## [2.8.9] - 2022-03-23

### Fixed

- Fix removing plugin events

## [2.8.8] - 2022-02-16

### Fixed

- Fix OnDocFormRender was not found!

## [2.8.7] - 2022-02-12

### Fixed

- Fix options property of ImagePlus snippet [#107]

## [2.8.6] - 2022-02-05

### Changed

- Code refactoring

### Fixed

- Fix OnDocFormRender was not found!

## [2.8.5] - 2021-12-30

### Fixed

- Fix styling issues in MODX 3 backend

## [2.8.4] - 2021-12-20

### Fixed

- Fix for PHP 8 warnings

## [2.8.3] - 2021-03-06

### Changed

- Compatibility with MODX 3

## [2.8.2] - 2021-01-19

### Fixed

- Fix the ImagePlus.MIGX_Renderer to show normal images too

## [2.8.1] - 2020-01-04

### Fixed

- Prevent an error, when $modx->resource is not set

## [2.8.0] - 2018-11-15

### Changed

- Updated jQuery

### Fixed

- Fixing a jQuery issue with ContentBlocks and other extras that use jQuery inside [#60]
- Fixing cannot read property 'destroy' of undefined issue
- Fixing Undefined property issue [#71]
- Fixing a window issue with tiny images [#53]

### Removed

- Some IE < 9 CSS rules

## [2.7.0] - 2017-11-13

### Changed

- Improved check for the allowBlank TV option

### Fixed

- Bugfix for using a wrong plugin event name in special circumstances

### Added

- phpThumbOn crop engine
- Warning in the TV output options for the right usage of the Generate Thumb URL property

## [2.6.3] - 2017-06-01

### Fixed

- Bugfix a full server path in the url placeholder

## [2.6.2] - 2017-02-21

### Fixed

- Bugfix for disabled option 'Generate Thumbnail URL' and an empty url placeholder

## [2.6.1] - 2017-02-03

### Fixed

- Bugfix for an empty select_config system setting

## [2.6.0] - 2017-02-01

### Fixed

- Fixed a missing realpath issue

### Added

- System-wide predefined crop sizes/aspect ratios, selectable in the TV options
- Context/system settings that supercede the TV options

### Removed

- MODX 2.2 compatibility

## [2.5.0] - 2016-12-17

### Fixed

- Fix showing a full server path, when the image is not found [#41]

### Added

- Optional caption and credits fields below of the image

## [2.4.5] - 2016-07-29

### Fixed

- Solved an installation issue on Windows machines

## [2.4.4] - 2016-06-15

### Changed

- Don't try to get an Image+ url with an empty template variable value
- Change the image by typing the filename
- Correcting typos, refactored code

### Added

- Add assets files on manager pages (i.e. for MIGX)
- Log invalid JSON only if imageplus.debug system setting is active
- Use only uglified/minified scripts in package code

## [2.4.3] - 2016-03-03

### Fixed

- Fixed not found image in combined/minified css file

### Added

- The alt text field could contain special chars
- Debug system setting for enabling not combined and minified/uglified css/js files

## [2.4.2] - 2016-01-18

### Added

- The snippet could use inherited values

## [2.4.1] - 2016-01-16

### Changed

- Change the image by typing the filename

### Fixed

- Fixing recoverable errors

### Added

Translated error messages

## [2.4.0] - 2016-01-15

### Changed

- Improved error logging
- Resolved issues with apostrophes in language strings

### Added

- Retain the value of a MODX TV as source image for the Image+ TV
- Fill output chunk placeholders with script properties

## [2.3.4] - 2015-08-06

### Changed

- Open crop window automatic after selecting a new image
- Bugfix for media source issue in MIGX

## [2.3.3] - 2015-07-05

### Changed

- Run jQuery in noConflict mode

## [2.3.2] - 2015-06-06

### Added

- 'value' parameter in the ImagePlus Snippet

## [2.3.1] - 2015-06-05

### Changed

- Use default_media_source if the media source of the TV is not set (i.e. if it used in a MIGX configuration)
- Don't show the crop window if the image size is invalid

### Added

- Enable property sets for the ImagePlus Snippet

## [2.3.0] - 2015-05-24

### Changed

- Updated Jcrop plugin and jQuery
- Updated Czech translation (@bartholomej)
- Updated Dutch translation (@Mark-H)
- Updated French translation (@AmaZili)
- Updated Russian translation (@Alroniks, serimarda)

### Fixed

- Bugfix for fireResourceFormChange issue
- Bugfix for a Firefox display issue

### Added

- MODX 2.3 compatibility
- Inline Trigger fields
- Some better backend styling
- Set thumbnail width in template variable panel
- Set crop aspect ratio in template variable input options
- Respect context settings for media sources
- Center the crop window in the viewport

## [2.2.x] - 2013-11-02

### Changed

- Refactored to allow changing of crop engines
- Confirmed to work with Articles [#21]
- Now works with MIGX [#15]
- TV Default Value is now output if TV is empty
- TV Reset button now works [#22]

### Added

- GUI warning of missing dependencies
- phpThumbsUp crop engine
- grid renderer for MIGX backend
- image preloader to accurately get image size on upload. This is because MODX File Manager will not report accurate image size for images above 800x600 [#8]
- option to put an icon on TV input 'crop' button [#16]
- Create a system setting called imageplus.crop_icon and populate it with the url to the desired icon png
- system setting override for core_path and assets_url [#25]
- Czech translation (@TheBoxer)
- Danish translation (@Flygenring)
- German translation (@KristianP)
- Spanish translation (Nico Telfer)
- French translation (@rtripault)
- Hungarian translation (Kristof Kotai)
- Italian translation (@tillilab)
- Dutch translation (@Mark-H)
- Russian translation (@Alroniks)
- French translation (@Alroniks)

## [2.1.x] - 2012-12-09

### Changed

- Fixed bug with non-default media sources

### Added

- Field for additional phpThumb parameters to output renderer
- Option to specify a chunk for output formatting (fields: url,alt,width,height)

## [2.0.x] - 2012-12-01

### Added

- Complete rewrite
