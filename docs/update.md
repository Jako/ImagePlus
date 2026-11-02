# Update to Image+ 3.x

The template variable values of Image+ 2.x are converted to the format of
Image+ 3.0 by the setup or on their first output.

The setup option **Migrate the Image+ 2.x template variables and settings** is
enabled by default. The migration converts the template variable values to the
new value format. It also converts the target width/height input options and
settings to the minimal image size input options and to the size of the image
crop. At least it converts the target ratio to the ratio of the image crop and
removes the legacy settings. The crop files of the image crops are created.
Values and crop files, that could not be converted or created, are logged in
`core/cache/logs/imageplus_migration.log`.

## ImagePlusMigration Snippet

If you want to check the migration first, disable the setup option and call the
ImagePlusMigration snippet uncached on a page, that is only visible for
administrators. The snippet output is only shown to manager users, that are sudo
users or members of the Administrator user group.

| Property | Description                                               | Default |
|----------|-----------------------------------------------------------|---------|
| dryRun   | Report the changes of the migration without saving them. | 1       |

First check the changes:

```
[[!ImagePlusMigration]]
```

Migrate afterwards:

```
[[!ImagePlusMigration? &dryRun=`0`]]
```

An empty `dryRun` property keeps the dry run.

## Photos with an EXIF Orientation

Browsers before 2020 showed photos without their EXIF orientation, so crops of
these photos, that were set in such a browser, refer to the photo lying on its
side. These crops have to be cropped again.
