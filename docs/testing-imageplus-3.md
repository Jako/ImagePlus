---
title: Testing Image+ 3.0
search:
  exclude: true
---

# Testing Image+ 3.0

*Image+ 3.0.0-b3 · beta*

Thank you for helping to test the next major version of Image+. This page
explains what has changed, how to set up a test site and which parts need
testing the most.

## What is new in 3.0

Image+ 3.0 is a rewrite of the template variable input and of the image output.

- The crop window uses Cropper.js instead of Jcrop and jQuery. The crop area can
  also be set with the Left, Top, Width and Height inputs below the image.
- An image can have several **image crops** (i.e. desktop and mobile). Each crop
  is saved as its own crop file in the media source.
- Images can be uploaded directly in the template variable.
- The crop files get an **image format**: WebP by default, JPEG, PNG, GIF, AVIF
  or the format of the image.
- The output uses these crop files. phpThumb, phpThumbOf, phpThumbsUp or
  phpThumbOn are no longer needed. pThumb can still be used in an output chunk.
- The template variable value has a new format without the media source. Image+
  2.x values are converted by the setup or on their first output.
- New snippets: `ImagePlusMigration` (with a dry run) and `ImagePlusCleanup`
  (removes unused crop files).

The full list of changes is in the changelog of the beta package
(`core/components/imageplus/docs/changelog.md`).

## Before you start

!!! warning "Use a copy of your site, never a live site"

    The update converts template variable values, changes template variable
    options and removes legacy system settings. Back up the database and the
    files of the media sources first.

| Requirement   | Version                |
|---------------|------------------------|
| PHP           | 8.2 or later           |
| MODX          | 2.8+ or 3.x            |
| Image library | GD, with WebP support  |

Install the beta package as usual in the package manager, either on a fresh MODX
installation or as an update of Image+ 2.x. Both ways are important to test.
AVIF crop files need a GD library with AVIF support, which is often missing.

## What to test

The areas are sorted by priority. You don't have to test everything: pick the
areas that match your sites.

### 1. Update from Image+ 2.x (highest priority)

Most problems are expected here, because every site uses Image+ a bit
differently.

- Update a copy of a real site with the setup option *Migrate the Image+ 2.x
  template variables and settings* enabled. Check that all images on the
  frontend still look the same.
- On a second copy, disable the setup option and run `[[!ImagePlusMigration]]`
  on a page only administrators can see. Check the dry run report, then run it
  with ``&dryRun=`0` ``.
- Check `core/cache/logs/imageplus_migration.log` for values or crop files, that
  could not be converted or created.
- Check the converted template variable options: target width/height should
  become the minimal image size and the size of the image crop, the target ratio
  should become the ratio of the image crop.
- Open resources in the manager and check that the crops are the same as before.
- Values in MIGX are converted too. MIGX configs (`targetWidth`, `targetRatio` …)
  have to be changed manually.

### 2. Template variable input (high priority)

- Create an Image+ TV with several image crops in the **Image Crops** input
  option, i.e.
  `{"desktop": {"name": "Desktop", "size": "1600x800"}, "mobile": {"name": "Mobile", "proportion": "4x3"}}`.
  Also try `width`, `height` and a crop without constraints.
- Select an image with the browse trigger, upload one with the upload trigger
  and type or paste an image path into the field.
- Clear the image, use the TV reset button of the resource and save the
  resource.
- Set a minimal image width/height and try a smaller image.
- Check the previews in a wide and in a narrow panel (below 700 pixels, the
  other images are shown on hover), the thumbnail height option and the *Show
  All Images in One Line* option.
- Enable the alt text, caption and credits fields and check that they are saved.
- Check the `imageplus.upload_path` and `imageplus.crop_path` system settings
  with placeholders like `[[+year]]` or `[[+resource_id]]`.

### 3. Crop window (high priority)

- Change the crop area with the handles and with the Left, Top, Width and Height
  inputs. With an aspect ratio, a changed width should change the height too.
- Switch between the image crops, save, reset and close the window with unsaved
  changes.
- Replace an image with a changed image with the same filename and click
  **Reload image**.
- Crop portrait photos of a phone (photos with an EXIF orientation). The crop
  window and the crop files should show them upright.
- Crop transparent PNG and GIF images and very large images. A too large image
  should show a clear error message instead of a blank result.

### 4. Image format (new)

- Without a value in the **Image Format** input option, new crop files should be
  WebP files.
- Try every format and *Format of the image*. A transparent PNG saved as JPEG
  should get a white background.
- Select AVIF on a server without AVIF support: the crop window should show an
  error message.
- Change the format of a TV with existing crops. Old crop files keep their
  format until the crop is saved again.

### 5. Frontend output

- Set the output type of the TV to Image+ and check the plain output (the URL of
  the crop file) and an output chunk.
- Check the placeholders in the output chunk: `url`, `width`, `height`, `alt`,
  `caption`, `credits`, `crop.url`, `source.src` and `crops.{key}.url/width/height`
  for every image crop.
- Select another image crop with the **Image Crop** output option.
- Use the ImagePlus snippet with `tvname`, `docid`, `crop`, `format` and an
  empty `tpl` (returns the URL).
- Use the snippet in a getResources/pdoResources template chunk with
  ``&docid=`[[+id]]` ``.
- If you use pThumb, check ``[[+source.src:pthumb=`[[+crop.options]]&w=640`]]``
  in an output chunk.

```
[[ImagePlus?
&tvname=`image`
&crop=`mobile`
&format=`jpg`
&tpl=``
]]
```

### 6. MIGX, Collections and media sources

- Use Image+ in a MIGX formtab with a JSON config (`sizes`, `minWidth`,
  `format` …) and output it with ``[[ImagePlus? &value=`[[+field]]` &source=`2`]]``.
- Show the thumbnail in a MIGX or Collections grid column with the
  `ImagePlus.ColumnRenderer` renderer.
- Use a media source other than the default one, context specific media sources
  and, if available, a remote media source (i.e. Amazon S3).

### 7. ImagePlusCleanup snippet

- Run `[[!ImagePlusCleanup]]` on a copy and check the list of unused crop files
  in the dry run. No crop file, that is still used on the site, should be
  listed.
- Remove the files with ``&dryRun=`0` `` and check the frontend afterwards.
  Check `core/cache/logs/imageplus_cleanup.log`.

### 8. Manager environments

- Test in MODX 2.8 and in MODX 3, in different browsers and with different
  manager languages (the lexicons exist in 11 languages).
- Check the TV input options panel and the crop window with the manager themes
  you use.

## How to report a problem

Please send your report directly by email to
[office@treehillstudio.com](mailto:office@treehillstudio.com) and add:

1. The versions of Image+, MODX and PHP, and the browser.
2. The steps to reproduce the problem, what you expected and what happened.
3. The input options of the TV (i.e. the Image Crops JSON) and the raw value of
   the TV, if the problem is about a single resource.
4. Entries from `core/cache/logs/error.log`, `imageplus_migration.log` or
   `imageplus_cleanup.log`.
5. A screenshot, if the problem is visible in the manager.

Feedback that something works on your site is welcome too, especially for
updates of larger sites.
