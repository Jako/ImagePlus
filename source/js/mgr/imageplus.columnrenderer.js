/**
 * Image+ Column Renderer
 *
 * @package imageplus
 * @subpackage script
 * @return {string}
 */

/**
 * Render the thumbnail of an Image+ value in a grid column, i.e. in MIGX or Collections
 */
ImagePlus.ColumnRenderer = function (json) {
    if (!json) {
        return '';
    }
    var data = ImagePlus.util.parseFileValue(json);
    var url, cls = 'imageplus';
    if (data.sourceImg) {
        // Image+ value before 3.0
        url = ImagePlus.generateThumbUrl({
            src: data.sourceImg.src,
            source: data.sourceImg.source,
            sw: data.crop.width,
            sh: data.crop.height,
            sx: data.crop.x,
            sy: data.crop.y
        });
    } else if (data.image && json.charAt(0) === '{') {
        // Show the first created crop or the original image
        var crop = Object.values(data.sizes || {}).find(function (size) {
            return size.image;
        });
        url = ImagePlus.util.thumbUrl(crop ? crop.image : data.image, data.source, {w: 150});
    } else {
        url = MODx.config.base_url + json;
        cls = 'plain';
    }
    return '<img class="' + cls + '" src="' + Ext.util.Format.htmlEncode(url) + '" style="max-width:100%; height:auto;" />';
};

// Deprecated, the previous name is kept for existing MIGX and Collections configurations
ImagePlus.MIGX_Renderer = ImagePlus.ColumnRenderer;
