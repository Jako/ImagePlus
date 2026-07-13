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
            xtype: 'sizes-ratio-combo',
            fieldLabel: {/literal}{if $forceconfig}_('imageplus.selectConfigForce'){else}_('imageplus.selectConfig'){/if}{literal},
            description: MODx.expandHelp ? '' : {/literal}{if $forceconfig}_('imageplus.selectConfigForce_desc'){else}_('imageplus.selectConfig_desc'){/if}{literal},
            name: 'inopt_selectConfig',
            id: 'inopt_selectConfig{/literal}{$tv}{literal}',
            tvId: '{/literal}{$tv}{literal}',
            labelAlign: 'left',
            data: {/literal}{$selectconfig}{literal},
            hidden: {/literal}{$hide}{literal},
            anchor: '100%',
            listeners: oc
        }, {
            xtype: MODx.expandHelp ? 'label' : 'hidden',
            forId: 'inopt_selectConfig{/literal}{$tv}{literal}',
            html: {/literal}{if $forceconfig}_('imageplus.selectConfigForce_desc'){else}_('imageplus.selectConfig_desc'){/if}{literal},
            cls: 'desc-under'
        }, {
            layout: 'column',
            items: [{
                columnWidth: 0.5,
                layout: 'form',
                labelAlign: 'top',
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('imageplus.targetwidth'),
                    description: MODx.expandHelp ? '' : _('imageplus.targetwidth_desc'),
                    name: 'inopt_targetWidth',
                    id: 'inopt_targetWidth{/literal}{$tv}{literal}',
                    value: params['targetWidth'] || '',
                    readOnly: {/literal}{if $forceconfig}true{else}false{/if}{literal},
                    anchor: '100%',
                    listeners: oc
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden',
                    forId: 'inopt_targetWidth{/literal}{$tv}{literal}',
                    html: _('imageplus.targetwidth_desc'),
                    cls: 'desc-under'
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('imageplus.targetheight'),
                    description: MODx.expandHelp ? '' : _('imageplus.targetheight_desc'),
                    name: 'inopt_targetHeight',
                    id: 'inopt_targetHeight{/literal}{$tv}{literal}',
                    value: params['targetHeight'] || '',
                    readOnly: {/literal}{if $forceconfig}true{else}false{/if}{literal},
                    anchor: '100%',
                    listeners: oc
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden',
                    forId: 'inopt_targetHeight{/literal}{$tv}{literal}',
                    html: _('imageplus.targetheight_desc'),
                    cls: 'desc-under'
                }]
            }, {
                columnWidth: 0.5,
                layout: 'form',
                labelAlign: 'top',
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('imageplus.targetRatio'),
                    description: MODx.expandHelp ? '' : _('imageplus.targetRatio_desc'),
                    name: 'inopt_targetRatio',
                    id: 'inopt_targetRatio{/literal}{$tv}{literal}',
                    value: params['targetRatio'] || '',
                    readOnly: {/literal}{if $forceconfig}true{else}false{/if}{literal},
                    anchor: '100%',
                    listeners: oc
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden',
                    forId: 'inopt_targetRatio{/literal}{$tv}{literal}',
                    html: _('imageplus.targetRatio_desc'),
                    cls: 'desc-under'
                }, {
                    xtype: 'textfield',
                    fieldLabel: _('imageplus.thumbnailWidth'),
                    description: MODx.expandHelp ? '' : _('imageplus.thumbnailWidth_desc'),
                    name: 'inopt_thumbnailWidth',
                    id: 'inopt_thumbnailWidth{/literal}{$tv}{literal}',
                    value: params['thumbnailWidth'] || '',
                    anchor: '100%',
                    listeners: oc
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden',
                    forId: 'inopt_thumbnailWidth{/literal}{$tv}{literal}',
                    html: _('imageplus.thumbnailWidth_desc'),
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
                            '&copy; 2015-2023 by <a href="https://treehillstudio.com" target="_blank">treehillstudio.com</a></span>';
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
