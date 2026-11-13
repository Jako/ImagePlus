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
        // The warning is shown once, also if the page contains several Image+ template variables
        if (this.unmetDependenciesWarned) {
            return;
        }
        this.unmetDependenciesWarned = true;
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
            html: '<h3>The crop engine is not available!</h3>' +
            '<p>The PHP GD extension is missing. It is needed to create the image crop files.</p>' +
            '<p>Enable the PHP GD extension on the server.</p>'
        });
        warningWindow.show();
    }
});
Ext.reg('imageplus', imagePlus);

ImagePlus = new imagePlus();
