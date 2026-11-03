<script type="text/javascript">
    // <![CDATA[{literal}
    var params = {
        {/literal}{foreach from=$params key=k item=v name='p'}
        '{$k}': '{$v|escape:"javascript"}'{if NOT $smarty.foreach.p.last}, {/if}
        {/foreach}{literal}
    };
    var oc = {
        change: {
            fn: function () {
                Ext.getCmp('modx-panel-tv').markDirty();
            }, scope: this
        }
    };
    MODx.load({
        xtype: 'panel',
        layout: 'form',
        applyTo: 'modx-input-props',
        cls: 'imageplus-props',
        border: false,
        labelAlign: 'top',
        listeners: {
            afterrender: function (component) {
                // The input option values are not used by Image+
                var elements = Ext.getCmp('modx-tv-elements');
                if (elements) {
                    elements.hide().nextSibling().hide();
                    if (ImagePlus.config.modxversion === '2') {
                        // MODX 2 shows only the field again, when another type is selected
                        Ext.getCmp('modx-tv-type').on('select', function () {
                            elements.nextSibling().show();
                        }, null, {single: true});
                    }
                }
                var tvTabId = (ImagePlus.config.modxversion === '2') ? 'modx-tv-tabs' : 'modx-tv-editor-tabs';
                Ext.getCmp('modx-panel-tv-input-properties').addListener('resize', function () {
                    component.setWidth(Ext.getCmp('modx-input-props').getWidth()).doLayout();
                });
                Ext.getCmp(tvTabId).addListener('tabchange', function () {
                    component.setWidth(Ext.getCmp('modx-input-props').getWidth()).doLayout();
                });
            },
        },
        items: [{
            cls: 'x-form-item imageplus-section',
            html: '<label class="x-form-item-label" style="padding-bottom: 0">' +
                _('imageplus.input_section') + '</label><label class="desc-under" style="">' +
                _('imageplus.input_section_desc') + '</label>'
        }, {
            layout: 'column',
            items: [{
                columnWidth: 0.33,
                layout: 'form',
                labelAlign: 'top',
                items: [{
                    xtype: 'combo',
                    fieldLabel: _('imageplus.format'),
                    description: MODx.expandHelp ? '' : _('imageplus.format_desc'),
                    name: 'inopt_format',
                    hiddenName: 'inopt_format',
                    id: 'inopt_format{/literal}{$tv}{literal}',
                    store: [
                        ['image', _('imageplus.format_image')],
                        ['jpg', 'JPEG'],
                        ['png', 'PNG'],
                        ['gif', 'GIF'],
                        ['webp', 'WebP'],
                        ['avif', 'AVIF']
                    ],
                    mode: 'local',
                    triggerAction: 'all',
                    editable: false,
                    forceSelection: true,
                    value: params['format'] || 'webp',
                    anchor: '100%',
                    listeners: oc
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden',
                    forId: 'inopt_format{/literal}{$tv}{literal}',
                    html: _('imageplus.format_desc'),
                    cls: 'desc-under'
                }]
            }, {
                columnWidth: 0.33,
                layout: 'form',
                labelAlign: 'top',
                items: [{
                    xtype: 'numberfield',
                    fieldLabel: _('imageplus.minWidth'),
                    description: MODx.expandHelp ? '' : _('imageplus.minWidth_desc'),
                    name: 'inopt_minWidth',
                    id: 'inopt_minWidth{/literal}{$tv}{literal}',
                    value: params['minWidth'] || '',
                    allowDecimals: false,
                    allowNegative: false,
                    anchor: '100%',
                    listeners: oc
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden',
                    forId: 'inopt_minWidth{/literal}{$tv}{literal}',
                    html: _('imageplus.minWidth_desc'),
                    cls: 'desc-under'
                }]
            }, {
                columnWidth: 0.34,
                layout: 'form',
                labelAlign: 'top',
                items: [{
                    xtype: 'numberfield',
                    fieldLabel: _('imageplus.minHeight'),
                    description: MODx.expandHelp ? '' : _('imageplus.minHeight_desc'),
                    name: 'inopt_minHeight',
                    id: 'inopt_minHeight{/literal}{$tv}{literal}',
                    value: params['minHeight'] || '',
                    allowDecimals: false,
                    allowNegative: false,
                    anchor: '100%',
                    listeners: oc
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden',
                    forId: 'inopt_minHeight{/literal}{$tv}{literal}',
                    html: _('imageplus.minHeight_desc'),
                    cls: 'desc-under'
                }]
            }]
        }, {
            layout: 'column',
            items: [{
                columnWidth: 0.33,
                layout: 'form',
                labelAlign: 'top',
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('imageplus.uploadPath'),
                    description: MODx.expandHelp ? '' : _('imageplus.uploadPath_desc'),
                    name: 'inopt_uploadPath',
                    id: 'inopt_uploadPath{/literal}{$tv}{literal}',
                    value: params['uploadPath'] || '',
                    anchor: '100%',
                    listeners: oc
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden',
                    forId: 'inopt_uploadPath{/literal}{$tv}{literal}',
                    html: _('imageplus.uploadPath_desc'),
                    cls: 'desc-under'
                }]
            }, {
                columnWidth: 0.33,
                layout: 'form',
                labelAlign: 'top',
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('imageplus.thumbnailHeight'),
                    description: MODx.expandHelp ? '' : _('imageplus.thumbnailHeight_desc'),
                    name: 'inopt_thumbnailHeight',
                    id: 'inopt_thumbnailHeight{/literal}{$tv}{literal}',
                    value: params['thumbnailHeight'] || '',
                    anchor: '100%',
                    listeners: oc
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden',
                    forId: 'inopt_thumbnailHeight{/literal}{$tv}{literal}',
                    html: _('imageplus.thumbnailHeight_desc'),
                    cls: 'desc-under'
                }]
            }, {
                columnWidth: 0.34,
                layout: 'form',
                labelAlign: 'top',
                items: [{
                    xtype: 'combo-boolean',
                    fieldLabel: _('imageplus.inlinePreview'),
                    description: MODx.expandHelp ? '' : _('imageplus.inlinePreview_desc'),
                    name: 'inopt_inlinePreview',
                    hiddenName: 'inopt_inlinePreview',
                    id: 'inopt_inlinePreview{/literal}{$tv}{literal}',
                    value: !(params['inlinePreview'] === 0 || params['inlinePreview'] === 'false'),
                    anchor: '100%',
                    listeners: oc
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden',
                    forId: 'inopt_inlinePreview{/literal}{$tv}{literal}',
                    html: _('imageplus.inlinePreview_desc'),
                    cls: 'desc-under'
                }]
            }]
        }, {
            xtype: 'textarea',
            fieldLabel: _('imageplus.sizes'),
            description: MODx.expandHelp ? '' : _('imageplus.sizes_desc'),
            name: 'inopt_sizes',
            id: 'inopt_sizes{/literal}{$tv}{literal}',
            value: params['sizes'] || '',
            anchor: '100%',
            grow: true,
            listeners: oc
        }, {
            xtype: MODx.expandHelp ? 'label' : 'hidden',
            forId: 'inopt_sizes{/literal}{$tv}{literal}',
            html: _('imageplus.sizes_desc'),
            cls: 'desc-under'
        }, {
            layout: 'column',
            items: [{
                columnWidth: 0.33,
                layout: 'form',
                labelAlign: 'top',
                items: [{
                    xtype: 'combo-boolean',
                    fieldLabel: _('imageplus.allowAltTag'),
                    description: MODx.expandHelp ? '' : _('imageplus.allowAltTag_desc'),
                    name: 'inopt_allowAltTag',
                    hiddenName: 'inopt_allowAltTag',
                    id: 'inopt_allowAltTag{/literal}{$tv}{literal}',
                    value: !(params['allowAltTag'] === 0 || params['allowAltTag'] === 'false'),
                    labelAlign: 'left',
                    anchor: '100%',
                    listeners: oc
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden',
                    forId: 'inopt_allowAltTag{/literal}{$tv}{literal}',
                    html: _('imageplus.allowAltTag_desc'),
                    cls: 'desc-under'
                }]
            }, {
                columnWidth: 0.33,
                layout: 'form',
                labelAlign: 'top',
                items: [{
                    xtype: 'combo-boolean',
                    fieldLabel: _('imageplus.allowCaption'),
                    description: MODx.expandHelp ? '' : _('imageplus.allowCaption_desc'),
                    name: 'inopt_allowCaption',
                    hiddenName: 'inopt_allowCaption',
                    id: 'inopt_allowCaption{/literal}{$tv}{literal}',
                    value: (params['allowCaption'] === 1 || params['allowCaption'] === 'true'),
                    labelAlign: 'left',
                    anchor: '100%',
                    listeners: oc
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden',
                    forId: 'inopt_allowCaption{/literal}{$tv}{literal}',
                    html: _('imageplus.allowCaption_desc'),
                    cls: 'desc-under'
                }]
            }, {
                columnWidth: 0.34,
                layout: 'form',
                labelAlign: 'top',
                items: [{
                    xtype: 'combo-boolean',
                    fieldLabel: _('imageplus.allowCredits'),
                    description: MODx.expandHelp ? '' : _('imageplus.allowCredits_desc'),
                    name: 'inopt_allowCredits',
                    hiddenName: 'inopt_allowCredits',
                    id: 'inopt_allowCredits{/literal}{$tv}{literal}',
                    value: (params['allowCredits'] === 1 || params['allowCredits'] === 'true'),
                    labelAlign: 'left',
                    anchor: '100%',
                    listeners: oc
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden',
                    forId: 'inopt_allowCredits{/literal}{$tv}{literal}',
                    html: _('imageplus.allowCredits_desc'),
                    cls: 'desc-under'
                }]
            }]
        }, {
            cls: "treehillstudio_about",
            html: '<img width="146" height="40" src="' + ImagePlus.config.assetsUrl + 'img/mgr/treehill-studio-small.png"' + ' srcset="' + ImagePlus.config.assetsUrl + 'img/mgr/treehill-studio-small@2x.png 2x" alt="Treehill Studio">',
            listeners: {
                afterrender: function (component) {
                    component.getEl().select('img').on('click', function () {
                        var msg = '<span style="display: inline-block; text-align: center;">&copy; 2013-2015 by Alan Pich <a href="https://github.com/alanpich" target="_blank">github.com/alanpich</a><br>' +
                            '<img src="' + ImagePlus.config.assetsUrl + 'img/mgr/treehill-studio.png" srcset="' + ImagePlus.config.assetsUrl + 'img/mgr/treehill-studio@2x.png 2x" alt="Treehill Studio" style="margin-top: 10px"><br>' +
                            '&copy; 2015-2026 by <a href="https://treehillstudio.com" target="_blank">treehillstudio.com</a></span>';
                        Ext.Msg.show({
                            title: _('imageplus') + ' ' + ImagePlus.config.version,
                            msg: msg,
                            buttons: Ext.Msg.OK,
                            cls: 'treehillstudio_window',
                            width: 358
                        });
                    });
                }
            }
        }]
    });
    MODx.helpUrl = 'https://jako.github.io/ImagePlus/usage/';
    // ]]>
</script>
{/literal}
