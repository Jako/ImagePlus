## Input Options

In *Image+* you can control the final size and aspect ratio of selected
images. The image can be selected in the media browser, uploaded or entered as
path. After selecting an image, the crop window opens and the image crops can
be created. For an *Image+* TV, several advanced input options are available:

#### Image Crops

A JSON encoded object with the image crops of the image. Each crop has a key, a
name and one of the following constraints:

| Constraint | Description                                     | Example     |
|------------|-------------------------------------------------|-------------|
| size       | The target size of the crop (width x height)    | `1000x500`  |
| proportion | The aspect ratio of the crop (width x height)   | `16x9`      |
| width      | The target width of the crop                    | `800`       |
| height     | The target height of the crop                   | `600`       |

```json
{
    "desktop": {"name": "Desktop", "size": "1600x800"},
    "mobile": {"name": "Mobile", "proportion": "4x3"}
}
```

If this option is empty, one image crop without constraints is created.

#### Minimal Image Width/Height

The selected or uploaded image must have at least this width and/or height in
pixels. Smaller images are rejected with an error message.

#### Thumbnail Height

The height of the preview image in the template variable panel. The default is
250 pixels.

#### Show All Images in One Line

Show the image crops and the original image also in one line, when the
template variable panel is at least 700 pixels wide. The default is enabled.

#### Show Alt Tag Field

*Image+* TVs can also contain an **Alt Tag field** which is an additional text
input that is stored with each image. It can be output next to the image i.e. as
an alt tag or title tag.

To output the alt text in an *Image+* TV placeholder, you need to select a chunk
in the **Output Options**. You can also use the ImagePlus snippet and specify a
template chunk in the snippet call options. The alt text is entered in the
`[[+alt]]` placeholder in this chunk.

#### Show Caption Field

*Image+* TVs can also include a **Caption Div**, which is an additional text
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
will override the input options of all image+ TVs. Context settings must be
created before they can be used.

| Key                       | Name                                      | Description                                                                                     | Default |
|---------------------------|-------------------------------------------|-------------------------------------------------------------------------------------------------|---------|
| imageplus.allow_alt_tag   | Show Alt Tag Field                        | Allow user to enter a title/alt-tag for the image.                                              | No      |
| imageplus.allow_caption   | Show Caption Field                        | Allow user to enter a caption for the image.                                                    | No      |
| imageplus.allow_credits   | Show Credits Field                        | Allow user to enter a credit for the image.                                                     | No      |
| imageplus.crop_path       | Crop Path                                 | The path in the media source for the image crop files.                                          | crop/   |
| imageplus.debug           | Debug                                     | Log debug information in the MODX error log.                                                    | No      |
| imageplus.thumbnail_height | Thumbnail Height                         | The thumbnail height of the image in the template variable panel.                               | 250     |
| imageplus.upload_path     | Upload Path                               | The path in the media source for uploaded images.                                               | -       |

The upload path and the crop path can contain the placeholders `[[+year]]`,
`[[+month]]`, `[[+day]]`, `[[+user]]`, `[[+username]]`, `[[+resource_id]]` and
`[[+tv_id]]`.

The global context/system settings have the prefix `imageplus.` and the
individual TV context/system settings have the prefix `imageplus.{tvname}.`.
`{tvname}` must be replaced with the name of the template variable.

The order for these settings is [^1].

- TV name based context setting
- TV name based system setting
- context setting
- system setting

## Output Options

Several advanced output options are available for an *Image+* TV:

#### Additional phpThumb Parameters

By default, an *Image+* TV returns a relative URL to a cropped (and possibly
constrained) image scaled by phpThumb. This output option allows you to assign
several additional phpThumb parameters that will be generated to create the
thumbnail image.

#### Image Crop

The key of the image crop that is used for the output. If it is empty, the first
image crop is used.

#### Output Chunk

When you select an **Output Chunk**, the TV output is rendered using that chunk.
Select the name of the chunk from the drop-down menu. Multiple placeholders are
possible in this chunk to customize the output:

| Placeholder   | Description                                                    |
|---------------|----------------------------------------------------------------|
| alt           | Alt text                                                       |
| crop.height   | Crop height of the thumbnail image                             |
| crop.options  | Crop engine crop option string to generate the thumbnail image |
| crop.url      | URL of the image crop file created in the manager              |
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

    You must enable this option if you do not specify an output chunk in the output
    options or if you use the `[[+url]]` placeholder in the specified output chunk.
    Otherwise, the image will not be cropped/truncated and the original image path
    will be returned.

#### Thumb URL Issues

If you use Image+ in a multiple language site with different base URL context
settings, you have to set the system setting `phpthumbof.cache_url` to `/`.
Otherwise, the generated thumbnail path will contain the `{base_url}` prefix.

## Snippet

The snippet gives you a second way to display the TV value. With template
variable output you are limited to one output chunk per template variable, with
the snippet you can be more flexible and use different output chunks. The
following properties can be set in the snippet call:

| Property | Description                                                                           | Default         |
|----------|---------------------------------------------------------------------------------------|-----------------|
| crop     | Key of the image crop used for the output. The first image crop is used by default.   | -               |
| docid    | Resource where the Image+ TV value is received from.                                  | -               |
| options  | Extended phpThumb options for the image.                                              | -               |
| tpl      | Template chunk for the snippet output.                                                | ImagePlus.image |
| tvname   | Name of the Image+ TV.                                                                | -               |
| type     | Type of the snippet output. Could be set to <i>check</i> <i>tpl</i> and <i>thumb</i>. | -               |

#### Example

```html
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

[^1]: The first entry supercedes the second entry in the list etc.
[^2]: Outputs *image* if the Image+ TV contains an image, otherwise *noimage*.
[^3]: Outputs the parsed template set by **tpl** property for retreived Image+ TV value (with additional options set by the **options** property)
[^4]: Outputs the thumbnail URL for retreived Image+ TV value (with additional options set by the **options** property)
[^5]: See [phpThumb readme](http://phpthumb.sourceforge.net/demo/docs/phpthumb.readme.txt) for possible options. Maybe not all options are valid in your installation (because of crop engine limitations or server restricions).
[^6]: The template chunk placeholder are the same as in the output chunk of the template variable.
