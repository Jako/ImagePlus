/**
 * Image+ Input Panel
 *
 * @package imageplus
 * @subpackage script
 */

ImagePlus.panel.input = function (config) {
    config = config || {};

    this.options = config.options;
    this.hiddenField = config.hiddenField;
    this.image = this.getValue();

    this.createImageBrowser();
    this.createTextFields();

    // Warn if it has no dependencies
    if (ImagePlus.config.hasUnmetDependencies) {
        ImagePlus.warnAboutUnmetDependencies()
    }

    Ext.apply(config, {
        border: false,
        config: config,
        baseCls: 'modx-panel',
        cls: 'imageplus-panel-input',
        items: [
            {
                // The browser renders the preview into its container, so that the fields are placed below the preview
                cls: 'imageplus-image-browser',
                border: false,
                items: [
                    this.imageBrowser
                ]
            },
            this.fieldsPanel = new Ext.Panel({
                cls: 'imageplus-image-fields',
                border: false,
                items: [
                    this.altTextField,
                    this.captionField,
                    this.creditsField
                ]
            })
        ],
        listeners: {
            afterrender: {
                fn: function () {
                    // The browser fires change events while it is rendered
                    this.initialized = true;
                    this.observeWidth();
                },
                scope: this
            },
            destroy: {
                fn: function () {
                    if (this.resizeObserver) {
                        this.resizeObserver.disconnect();
                    }
                },
                scope: this
            }
        }
    });
    ImagePlus.panel.input.superclass.constructor.call(this, config);

    this.listenForResetEvent();
};
Ext.extend(ImagePlus.panel.input, MODx.Panel, {
    // The minimum panel width, that shows the text fields in one line
    inlineFieldsWidth: 700,
    // The gap between the text fields in one line
    inlineFieldsGap: 10,
    // Size the fields to the full width of the template variable area, also when it is shown later in a tab
    observeWidth: function () {
        var update = function () {
            var width = this.el.getWidth(true);
            if (width && width !== this.fieldWidth) {
                this.fieldWidth = width;
                this.imageBrowser.setWidth(width);
                // The enabled text fields are shown in one line, if the panel is wide enough
                var fields = [this.altTextField, this.captionField, this.creditsField].filter(function (field) {
                    return !field.hidden;
                });
                var inline = fields.length > 1 && width >= this.inlineFieldsWidth;
                var fieldWidth = inline ? Math.floor((width - (fields.length - 1) * this.inlineFieldsGap) / fields.length) : width;
                this.fieldsPanel.body[inline ? 'addClass' : 'removeClass']('imageplus-image-fields-inline');
                Ext.each(fields, function (field) {
                    field.items.get(0).setWidth(fieldWidth);
                });
            }
        }.bind(this);
        if (window.ResizeObserver) {
            this.resizeObserver = new ResizeObserver(update);
            this.resizeObserver.observe(this.el.dom);
        } else {
            Ext.EventManager.onWindowResize(update, this);
        }
        update();
    },
    // Listen for TV 'Reset' button
    listenForResetEvent: function () {
        var resourcePanel = Ext.getCmp('modx-panel-resource');
        if (resourcePanel) {
            resourcePanel.on('tv-reset', function (changed) {
                if (parseInt(changed.id) === this.options.tvId) {
                    this.onReset();
                }
            }, this);
        }
    },
    // Create the image upload/crop/browser field
    createImageBrowser: function () {
        // Generate opento path
        var openToPath = (this.image.image || '').split('/');
        openToPath.pop();
        openToPath = openToPath.join('/');

        this.imageBrowser = MODx.load({
            xtype: 'imageplus-combo-upload-crop-browser',
            value: this.getBrowserValue(),
            submitValue: false,
            hideLabel: true,
            source: this.options.mediaSource,
            sourceBaseUrl: ImagePlus.util.sourceUrl(this.options.mediaSource),
            wctx: MODx.request.wctx || 'mgr',
            openTo: openToPath,
            sizes: this.options.sizes,
            minWidth: this.options.minWidth || 0,
            minHeight: this.options.minHeight || 0,
            previewHeight: this.options.thumbnailHeight || 250,
            // Without the inline preview, the wide view shows the images like the narrow view
            allowInlinePreview: this.options.inlinePreview !== false,
            autoOpen: true,
            showDownload: true,
            isTV: true,
            resourceId: MODx.request.id || 0,
            tvId: this.options.tvId,
            listeners: {
                change: {
                    fn: this.onImageChange,
                    scope: this
                }
            }
        });
    },
    // Create fields for alt-text, caption and credits input
    createTextFields: function () {
        this.altTextField = this.createTextField('altTag', this.options.altTagOn, 'imageplus.alt_text', 'icon-code');
        this.captionField = this.createTextField('caption', this.options.captionOn, 'imageplus.caption', 'icon-header');
        this.creditsField = this.createTextField('credits', this.options.creditsOn, 'imageplus.credits', 'icon-copyright');
    },
    createTextField: function (key, enabled, lexicon, icon) {
        return MODx.load({
            xtype: 'panel',
            cls: 'imageplus-image-field',
            border: false,
            hidden: !enabled,
            items: {
                xtype: enabled ? 'textfield' : 'hidden',
                submitValue: false,
                value: this.image[key] || '',
                listeners: {
                    change: {
                        fn: function (field, value) {
                            this.image[key] = value;
                            this.updateValue();
                        },
                        scope: this
                    },
                    afterrender: function (field) {
                        var el = field.getEl();
                        if (el && enabled) {
                            el.set({'placeholder': _(lexicon)});
                            el.insertSibling({
                                tag: 'span',
                                cls: 'icon ' + icon,
                                style: 'position: absolute; left: 8px; top: 14px; opacity: 0.6',
                                title: _(lexicon)
                            }, 'after', true);
                        }
                    }
                },
                style: {
                    marginTop: '5px',
                    paddingLeft: '25px'
                }
            }
        });
    },
    // Fires when the TV field is reset
    onReset: function () {
        this.image = this.getValue();
        this.imageBrowser.setValue(this.getBrowserValue());
    },
    // Fired when the image or the crops are changed in the browser
    onImageChange: function () {
        if (!this.initialized) {
            return;
        }
        var value = this.imageBrowser.parseValue(this.imageBrowser.getValue());
        this.image.image = value.image || '';
        this.image.baseUrl = value.baseUrl || '';
        this.image.sizes = value.sizes || {};
        this.updateValue();
    },
    // Get the value of the browser field
    getBrowserValue: function () {
        return (this.image.image) ? Ext.encode({
            image: this.image.image,
            baseUrl: this.image.baseUrl || '',
            sizes: this.image.sizes || {}
        }) : '';
    },
    // Get hidden field value
    getValue: function () {
        return ImagePlus.util.parseFileValue(Ext.get(this.hiddenField).getValue());
    },
    // Update hidden field value
    updateValue: function () {
        var hiddenField = document.getElementById(this.hiddenField);
        var newValue = '';
        if (this.image.image) {
            newValue = JSON.stringify({
                image: this.image.image,
                baseUrl: this.image.baseUrl || '',
                sizes: this.image.sizes || {},
                altTag: this.image.altTag || '',
                caption: this.image.caption || '',
                credits: this.image.credits || ''
            }, null, '  ');
        }
        if (hiddenField && hiddenField.value !== newValue) {
            hiddenField.value = newValue;

            // Mark resource as dirty
            MODx.fireResourceFormChange();
        }
    }
});
Ext.reg('imageplus-panel-input', ImagePlus.panel.input);
