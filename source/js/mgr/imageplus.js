/**
 * Image+ Custom Manager Page Script
 *
 * @package imageplus
 * @subpackage script
 */

var imagePlus = function (config) {
    config = config || {};
    imagePlus.superclass.constructor.call(this, config);
};

Ext.extend(imagePlus, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, form: {}, util: {},

    generateThumbUrl: function (params) {
        return this.generatePhpThumbUrl(params);
    },

    generatePhpThumbUrl: function (params) {
        var query = new URLSearchParams(Ext.apply({
            wctx: 'mgr',
            w: 150,
            source: 1
        }, params));
        return MODx.config.connectors_url + 'system/phpthumb.php?' + query.toString();
    },

    warnAboutUnmetDependencies: function () {
        var warningWindow = MODx.load({
            xtype: 'modx-window',
            title: 'Image+ Warning – Unmet Dependencies',
            modal: true,
            padding: 25,
            allowDrop: false,
            resizable: true,
            collapsible: true,
            maximizable: true,
            buttons: [{
                text: _('ok'),
                handler: function (L) {
                    L.ownerCt.ownerCt.close();
                }
            }],
            html: '<h3>You don\'t have any crop engines!</h3>' +
            '<p>Before you can use Image+, you need at least one Crop Engine installed to handle image manipulation.</p>' +
            '<p>A quick fix is to install either pThumb, phpThumbOf, phpThumbsUp or phpThumbOn from the MODX Package Repository</p>'
        });
        warningWindow.show();
    }
});
Ext.reg('imageplus', imagePlus);

ImagePlus = new imagePlus();
