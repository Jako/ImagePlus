# Legacy Image+ 2.x

!!! info "Legacy version"
    This page contains the documentation of Image+ 2.9.5, the last version
    before Image+ 3.0. It is only needed for installations, that still use
    Image+ 2.x. See [Update to Image+ 3.x](update.md)
    for the migration to the current version.

### Requirements

* MODX Revolution 2.6+
* PHP 7.1+
* MODX crop engine i.e. [pThumb](https://modx.com/extras/package/pthumb)

The crop engine creates the cropped images. Image+ 2.x supports pThumb,
phpThumbOf, phpThumbsUp and phpThumbOn.

## Create a new template variable

Once Image+ is installed, you can create template variables [TV] in the usual
way, but instead of setting the **Input Type** in the Input Options to *Image*,
choose *Image+* instead.

For most use cases, you should also set the **Output Type** in the Output
Options to *Image+*. This allows you to control the image output on a resource.

## The cropping tool

When using an *Image+* TV, you can select an image in the same way as you would
with a regular *Image* TV. Once an image is selected, a cropping window will
appear where you can select the area of the image to use. The original image is
not changed. So you can upload a single image and reuse it in multiple places on
the website at different sizes (and crops with multiple Image+ TVs).

If you want to change the cropping area, you need to click the crop trigger of
the Image+ TV.

In the cropping window you need to drag the handles of the dotted box to change
the cropping area. If the TV has been configured with size constraints or an
aspect ratio, the cropping area will be limited to that aspect ratio.

## Input Options

In *Image+* you can control the final size and aspect ratio of selected
images. For an *Image+* TV, several advanced input options are available:

#### Target Height/Width

Images can be constrained to a minimum height and/or width with **Target
Height** and **Target Width**. If both values are set, the target aspect ratio
of the output image will be calculated with these values. Both fields must be
filled with an integer value.

#### Target Aspect Ratio

The aspect ratio for the output image can be restricted with **Target aspect
ratio**. If target height and target width are specified, this value is ignored
and the calculated aspect ratio of target height and target width is used. The
field must be filled with a float value.

If you specify only a value for **Target Height** or **Target Width** and leave
**Target Aspect Ratio** blank, the size of the crop is not constrained to any
aspect ratio.

!!! note "How to calculate the aspect ratio"
    The aspect ratio contains a floating point value: this value can be easily
    determined by dividing any width with the desired aspect ratio by the
    corresponding height. For example, suppose you want all your crops to have the
    same aspect ratio as an image of size "1600x1000", then simply divide "1600" by
    "1000", which gives the value "1.6". This is the value for your aspect ratio.

#### Thumbnail Width

The width of the thumbnail image in the template variable panel.

#### Show Alt Tag Field

*Image+* TVs can also contain an **Alt Tag field** which is an additional text
input that is stored with each image. It can be output next to the image i.e. as
an alt tag or title tag.

To output the alt text in an *Image+* TV placeholder, you need to select a chunk
in the **Output Options**. You can also use the ImagePlus snippet and specify a
template chunk in the snippet call options. The alt text is entered in the
`[[+alt]]` placeholder in this chunk.

#### Show Caption Field

*Image+* TVs can also include a **Caption field**, which is an additional text
input that is stored with each image. This can be output below the image, i.e.
in an additional caption div.

To output the caption in an *Image+* TV placeholder, you must select a chunk in
the **Output Options**. You can also use the ImagePlus snippet and specify a
template chunk in the call options of the snippet. The caption will be inserted
into the `[[+caption]]` placeholder in that chunk.

#### Show Credits Field

*Image+* TVs can also include a **Credits field**, which is an additional text
input stored with each image. It can be output below the image, for example in
an additional credits field.

To output the credits in an *Image+* TV placeholder, you must select a chunk in
the **Output Options**. You can also use the ImagePlus snippet and specify a
template chunk in the snippet's call options. The credits are inserted into the
`[[+credits]]` placeholder in that chunk.

### Context/System Settings

Each *Image+* TV **Input Option** can be replaced by a context or system
setting. And context/system settings can be defined for a single TV.

There are predefined system settings in the `imageplus` namespace which are
empty or zero. If you activate one of them or fill it with a value, this setting
will override the input options of all Image+ TVs. Context settings must be
created before they can be used.

| Key                       | Name                                      | Description                                                                                     | Default |
|---------------------------|-------------------------------------------|-------------------------------------------------------------------------------------------------|---------|
| imageplus.allow_alt_tag   | Show Alt Tag Field                        | Allow user to enter a title/alt-tag for the image.                                              | No      |
| imageplus.allow_caption   | Show Caption Field                        | Allow user to enter a caption for the image.                                                    | No      |
| imageplus.allow_credits   | Show Credits Field                        | Allow user to enter a credit for the image.                                                     | No      |
| imageplus.debug           | Debug                                     | Log debug information in the MODX error log.                                                    | No      |
| imageplus.force_config    | Force predefined crop sizes/aspect ratios | Force the usage of predefined crop size/aspect ratios.                                          | No      |
| imageplus.select_config   | Predefined crop sizes/aspect ratios       | Create predefined crop size/aspect ratios that are selectable in the template variable options. | []      |
| imageplus.target_height   | Target Height                             | Constrain the target images to a minimal height.                                                | -       |
| imageplus.target_ratio    | Target Aspect Ratio                       | Restrict the target images to a aspect ratio.                                                   | -       |
| imageplus.target_width    | Target Width                              | Constrain the target images to a minimal width.                                                 | -       |
| imageplus.thumbnail_width | Thumbnail Width                           | The thumbnail width of the image in the template variable panel.                                | -       |

The global context/system settings have the prefix `imageplus.` and the
individual TV context/system settings have the prefix `imageplus.{tvname}.`.
`{tvname}` must be replaced with the name of the template variable.

The order for these settings is [^1]:

- TV name based context setting
- TV name based system setting
- context setting
- system setting

In the `imageplus.select_config` system setting, you can create predefined crop
sizes/aspect ratios with an auxiliary grid. To force the use of the predefined
sizes/ratios, you can activate the system setting `imageplus.force_config`.

## Output Options

Several advanced output options are available for an *Image+* TV:

#### Additional phpThumb Parameters

By default, an *Image+* TV returns a relative URL to a cropped (and possibly
constrained) image scaled by phpThumb. This output option allows you to assign
several additional phpThumb parameters that will be generated to create the
thumbnail image.

#### Output Chunk

When you select an **Output Chunk**, the TV output is rendered using that chunk.
Select the name of the chunk from the drop-down menu. Multiple placeholders are
possible in this chunk to customize the output:

| Placeholder   | Description                                                    |
|---------------|----------------------------------------------------------------|
| alt           | Alt text                                                       |
| caption       | Caption                                                        |
| credits       | Credits                                                        |
| crop.height   | Crop height of the thumbnail image                             |
| crop.options  | Crop engine crop option string to generate the thumbnail image |
| crop.width    | Crop width of the thumbnail image                              |
| crop.x        | Crop x position of the thumbnail image                         |
| crop.y        | Crop y position of the thumbnail image                         |
| height        | Minimal height of the thumbnail image                          |
| options       | Crop engine full option string to generate the thumbnail image |
| source.height | Height of the source image                                     |
| source.src    | Full path of the source image on the server (not a URL)        |
| source.width  | Width of the source image                                      |
| url           | URL of the thumbnail image                                     |
| width         | Minimal width of the thumbnail image                           |

All these placeholders can be used in the Snippet too.

#### Generate Thumb URL

If you create the thumbnail in the output chunk, i.e. through a pthumb output
filter, you can disable the generation of the internal thumb URL.

!!! warning "Caution"
    You must enable this option if you do not specify an output chunk in the
    output options or if you use the `[[+url]]` placeholder in the specified
    output chunk. Otherwise, the image will not be cropped and the original
    image path will be returned.

#### Thumb URL Issues

If you use Image+ in a multiple language site with different base URL context
settings, you have to set the system setting `phpthumbof.cache_url` to `/`.
Otherwise, the generated thumbnail path will contain the `{base_url}` prefix.

## Snippet

The snippet gives you a second way to display the TV value. With template
variable output you are limited to one output chunk per template variable, with
the snippet you can be more flexible and use different output chunks. The
following properties can be set in the snippet call:

| Property | Description                                                         | Default         |
|----------|---------------------------------------------------------------------|-----------------|
| docid    | Resource where the Image+ TV value is received from.                | -               |
| options  | Extended phpThumb options for the image [^5].                       | -               |
| tpl      | Template chunk for the snippet output [^6].                         | ImagePlus.image |
| tvname   | Name of the Image+ TV.                                              | -               |
| type     | Type of the snippet output. Could be set to `check` [^2], `tpl` [^3] and `thumb` [^4]. | -               |
| value    | Use this value instead of the TV value, i.e. for a MIGX field.      | -               |

#### Example

```
[[ImagePlus?
&tvname=`imageplus`
&docid=`1`
&options=`w=100`
&type=`tpl`
&tpl=`ImagePlus.demo`
]]
```

This snippet call returns the contents of the template variable named
`imageplus` of resource `1` and the extended phpThumb option `&w=100` (width:
100px) in the parsed `ImagePlus.demo` chunk.

## MIGX

If you want to use *Image+* in MIGX, you can configure all *Image+* TV
properties with a JSON string in the *Configs* textarea in the MIGX formtabs
field. The following properties are possible:

```json
{
    "targetWidth": "",
    "targetHeight": "",
    "targetRatio": "",
    "thumbnailWidth": "",
    "allowAltTag": "",
    "allowCaption": "",
    "allowCredits": ""
}
```

You also need to change the *Input TV Type* to `imageplus`. You can display the
thumbnail in the grid column by setting the renderer to
*ImagePlus.MIGX_Renderer*.

To use this *Image+* MIGX field in the frontend, call the *ImagePlus Snippet*
where the value parameter is the name of the MIGX field:

```
[[ImagePlus?
&value=`[[+migxImagePlusField]]`
]]
```

!!! note
    The MIGX configs are not migrated by the Image+ 3.0 setup, they have to be
    changed manually.

[^1]: The first entry supersedes the second entry in the list etc.
[^2]: Outputs *image* if the Image+ TV contains an image, otherwise *noimage*.
[^3]: Outputs the parsed template set by the **tpl** property for the retrieved Image+ TV value (with additional options set by the **options** property).
[^4]: Outputs the thumbnail URL for the retrieved Image+ TV value (with additional options set by the **options** property).
[^5]: See [phpThumb readme](http://phpthumb.sourceforge.net/demo/docs/phpthumb.readme.txt) for possible options. Maybe not all options are valid in your installation (because of crop engine limitations or server restrictions).
[^6]: The template chunk placeholders are the same as in the output chunk of the template variable.
