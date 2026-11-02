<?php
/**
 * Default Lexicon Entries for Image+
 *
 * @package imageplus
 * @subpackage lexicon
 */
$_lang['imageplus'] = 'Image+';
$_lang['imageplus.editor_title'] = 'Image+ Editor';
$_lang['imageplus.alt_text'] = 'Alt text';
$_lang['imageplus.caption'] = 'Caption';
$_lang['imageplus.credits'] = 'Credits';
/** Input options render **/
$_lang['imageplus.input_section'] = 'Image+ Options';
$_lang['imageplus.input_section_desc'] = 'The following options could be overridden by context/system settings. Please read the <a href="https://jako.github.io/ImagePlus/usage/#contextsystem-settings" target="_blank">documentation</a> for the appropriate keys in the context/system settings.';
$_lang['imageplus.minWidth'] = 'Minimal Image Width';
$_lang['imageplus.minWidth_desc'] = '(Optional, Integer) The minimal width of the selected or uploaded image in pixels.';
$_lang['imageplus.minHeight'] = 'Minimal Image Height';
$_lang['imageplus.minHeight_desc'] = '(Optional, Integer) The minimal height of the selected or uploaded image in pixels.';
$_lang['imageplus.thumbnailHeight'] = 'Thumbnail Height';
$_lang['imageplus.thumbnailHeight_desc'] = '(Optional, Integer) The thumbnail height of the image in the template variable panel. Default: 250';
$_lang['imageplus.inlinePreview'] = 'Show All Images in One Line';
$_lang['imageplus.inlinePreview_desc'] = '(Optional, Yes/No) Show the image crops and the original image also in one line, when the template variable panel is at least 700 pixels wide. Default: Yes';
$_lang['imageplus.allowAltTag'] = 'Show Alt Tag Field';
$_lang['imageplus.allowAltTag_desc'] = 'Allow user to enter a title/alt-tag for the image.';
$_lang['imageplus.allowCaption'] = 'Show Caption Field';
$_lang['imageplus.allowCaption_desc'] = 'Allow user to enter a caption for the image.';
$_lang['imageplus.allowCredits'] = 'Show Credits Field';
$_lang['imageplus.allowCredits_desc'] = 'Allow user to enter a credit for the image.';
/** Output options render **/
$_lang['imageplus.phpThumbParams'] = 'Additional phpThumb Parameters';
$_lang['imageplus.phpThumbParams_desc'] = '(Optional) phpThumb parameters for the options placeholder of the output chunk, i.e. for a pthumb output filter. Documentation can be found <a href="https://raw.githubusercontent.com/JamesHeinrich/phpThumb/master/docs/phpthumb.readme.txt" target="_blank">here</a>.';
$_lang['imageplus.outputChunk'] = 'Output Chunk';
$_lang['imageplus.outputChunk_desc'] = 'Select a chunk for tv output. Leave blank for raw url output.';
$_lang['imageplus.generateUrl'] = 'Use the Crop File URL';
$_lang['imageplus.generateUrl_desc'] = '(Optional) The url of the image crop file is maybe not necessary, if you generate the thumbnail in output chunk i.e. by a pthumb output filter.';
$_lang['imageplus.generateUrl_desc_warning'] = 'You have to activate this option, when you don’t specify an output chunk in the output options or when you use the [[+url]] placeholder in the specified output chunk. Otherwise the image is not cropped and the url of the original image is returned.';
/** Placeholder descriptions */
$_lang['imageplus.placeholder.url'] = 'URL of the image crop file';
$_lang['imageplus.placeholder.alt'] = 'Alt text';
$_lang['imageplus.placeholder.width'] = 'Width of the thumbnail image (ignored when 0)';
$_lang['imageplus.placeholder.height'] = 'Height of the thumbnail image (ignored when 0)';
$_lang['imageplus.placeholder.source.src'] = 'Path to the source image';
$_lang['imageplus.placeholder.source.width'] = 'Width of the source image';
$_lang['imageplus.placeholder.source.height'] = 'Height of the source image';
$_lang['imageplus.placeholder.crop.width'] = 'Crop width of the source image';
$_lang['imageplus.placeholder.crop.height'] = 'Crop height of the source image';
$_lang['imageplus.placeholder.crop.x'] = 'Crop x position of the source image';
$_lang['imageplus.placeholder.crop.y'] = 'Crop y position of the source image';
$_lang['imageplus.placeholder.options'] = 'phpThumb option string to generate the thumbnail image';
$_lang['imageplus.placeholder.crop.options'] = 'phpThumb crop option string to generate the thumbnail image';
$_lang['imageplus.error.image_too_small.title'] = 'Image too small';
$_lang['imageplus.error.image_too_small.msg'] = 'The selected image is too small to be used here. Please select a different image.';
$_lang['imageplus.error.image_not_found.title'] = 'Image not found';
$_lang['imageplus.error.image_not_found.msg'] = 'The image was not found and can’t be cropped. Please select a different image.';
/** Image crops **/
$_lang['imageplus.sizes'] = 'Image Crops';
$_lang['imageplus.sizes_desc'] = '(Optional, JSON) A JSON-encoded object for the image crops, i.e. {"desktop": {"name": "Desktop", "size": "1000x500"}, "mobile": {"name": "Mobile", "proportion": "4x3"}}. Each crop could have a size, a proportion, a width or a height. If this value is empty, one image crop without constraints is used.';
$_lang['imageplus.crop'] = 'Image Crop';
$_lang['imageplus.crop_desc'] = '(Optional) The key of the image crop used for the output. Leave blank to use the first image crop.';
$_lang['imageplus.placeholder.crop.url'] = 'URL of the image crop file created in the manager';
/** Crop window **/
$_lang['imageplus.cropimage'] = 'Crop Image';
$_lang['imageplus.cropimage.changes.content'] = 'There are unsaved changes, do you want to continue?';
$_lang['imageplus.cropimage.changes.title'] = 'Warning';
$_lang['imageplus.cropimage.changes_saved'] = 'Changes saved';
$_lang['imageplus.cropimage.changes_unsaved'] = 'There are unsaved changes.';
$_lang['imageplus.cropimage.crop'] = 'Crop Image';
$_lang['imageplus.cropimage.crop.height'] = 'Height';
$_lang['imageplus.cropimage.crop.proportion'] = 'Ratio';
$_lang['imageplus.cropimage.crop.width'] = 'Width';
$_lang['imageplus.cropimage.crop.x'] = 'Left';
$_lang['imageplus.cropimage.crop.y'] = 'Top';
$_lang['imageplus.cropimage.default'] = 'Original';
$_lang['imageplus.cropimage.error_crop'] = 'Error creating image crop file.';
$_lang['imageplus.cropimage.error_cropheight_cropwidth'] = 'The value in cropWidth or cropHeight is empty.';
$_lang['imageplus.cropimage.error_file_not_set'] = 'No file specified.';
$_lang['imageplus.cropimage.error_image'] = 'The image crop could not be saved.';
$_lang['imageplus.cropimage.error_image_not_exists'] = 'The image does not exist.';
$_lang['imageplus.cropimage.error_image_not_valid'] = 'The image is not a valid file.';
$_lang['imageplus.cropimage.error_min_width'] = 'The image is [[+width]] pixels wide, but it has to be at least [[+min_width]] pixels wide.';
$_lang['imageplus.cropimage.error_min_height'] = 'The image is [[+height]] pixels high, but it has to be at least [[+min_height]] pixels high.';
$_lang['imageplus.cropimage.error_memory'] = 'The image ([[+width]] x [[+height]] pixels) is too large to be cropped. Cropping it needs about [[+memory]] of memory, but the PHP memory_limit is [[+limit]] and can\'t be raised. Please upload a smaller image or ask the server administrator to raise the memory_limit.';
$_lang['imageplus.cropimage.original'] = 'Original image';
$_lang['imageplus.cropimage.original.download'] = 'Download original image';
$_lang['imageplus.cropimage.original.height'] = 'Height';
$_lang['imageplus.cropimage.original.width'] = 'Width';
$_lang['imageplus.cropimage.reload'] = 'Reload image';
$_lang['imageplus.cropimage.reload.content'] = 'The image is loaded again and all saved image crops are created again from it, i.e. if the image was replaced by a changed image with the same filename. Unsaved changes are discarded. Do you want to continue?';
$_lang['imageplus.cropimage.reload.title'] = 'Reload image';
$_lang['imageplus.cropimage.reload_ratio.content'] = 'The aspect ratio of the image has changed. Please check the image crops and save them again, if needed.';
$_lang['imageplus.cropimage.reload_ratio.title'] = 'Warning';
$_lang['imageplus.cropimage.reloading'] = 'Creating the image crops again …';
$_lang['imageplus.cropimage.reset'] = 'Reset changes';
$_lang['imageplus.cropimage.save'] = 'Save crop';
$_lang['imageplus.cropimage.saving'] = 'Saving the image crop …';
$_lang['imageplus.cropimage.select_file'] = 'Select file …';
$_lang['imageplus.cropimage.sets'] = 'Select image crop';
$_lang['imageplus.cropimage.success_image'] = 'Image crop saved';
$_lang['imageplus.cropimage.target'] = 'Target image';
$_lang['imageplus.cropimage.target.height'] = 'Height';
$_lang['imageplus.cropimage.target.width'] = 'Width';
$_lang['imageplus.cropimage.unset.content'] = 'Some image crops are not created, do you want to continue?';
$_lang['imageplus.cropimage.unset.title'] = 'Warning';
