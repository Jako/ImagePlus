/**
 * Image+ UploadBrowser Component
 *
 * @package imageplus
 * @subpackage script
 */

/**
 * A trigger field with several triggers. Each entry in triggerActions creates a trigger, that calls the method
 * on<Action>Trigger on click and gets the css class from triggerClasses.
 */
ImagePlus.form.MultiTriggerField = Ext.extend(Ext.form.TriggerField, {
    triggerActions: [],
    triggerClasses: {},
    initComponent: function () {
        ImagePlus.form.MultiTriggerField.superclass.initComponent.call(this);
        var count = {2: 'double', 3: 'triple', 4: 'quadruple'}[this.triggerActions.length] || 'multi';
        this.triggerConfig = {
            tag: 'span',
            cls: 'x-form-' + count + '-triggers',
            cn: this.triggerActions.map(function (action) {
                return {tag: 'div', cls: 'x-form-trigger ' + (this.triggerClasses[action] || ''), 'data-action': action};
            }, this)
        };
    },
    initTrigger: function () {
        var triggerSelect = this.trigger.select('.x-form-trigger', true);
        triggerSelect.each(function (trigger) {
            var action = trigger.dom.dataset.action;
            var handler = this['on' + action.charAt(0).toUpperCase() + action.slice(1) + 'Trigger'] || Ext.emptyFn;
            this.mon(trigger, 'click', handler, this, {preventDefault: true});
            trigger.addClassOnOver('x-form-trigger-over');
            trigger.addClassOnClick('x-form-trigger-click');
        }, this);
        this.triggers = triggerSelect.elements;
    },
    getTriggerWidth: function () {
        var triggerWidth = 0;
        Ext.each(this.triggers, function (trigger) {
            triggerWidth += trigger.getWidth() || this.defaultTriggerWidth;
        }, this);
        return triggerWidth;
    },
    onDestroy: function () {
        Ext.destroy(this.triggers);
        ImagePlus.form.MultiTriggerField.superclass.onDestroy.call(this);
    }
});

ImagePlus.form.FileField = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        buttonText: _('imageplus.cropimage.select_file'),
        disabled: true,
        allowedMIMEtypes: '',
        baseParams: {},
        value: '',
        listeners: {},
    });
    Ext.apply(config.listeners, {
        afterrender: {
            fn: this.onAfterRender,
            scope: this,
        }
    });
    ImagePlus.form.FileField.superclass.constructor.call(this, config);
};
Ext.extend(ImagePlus.form.FileField, Ext.form.TextField, {
    onAfterRender: function () {
        this.fileInput = this.el.wrap().createChild({
            id: this.id + '-fileinput',
            name: this.name || 'upload',
            cls: 'x-form-file',
            tag: 'input',
            type: 'file',
            accept: this.allowedMIMEtypes,
        });
        this.fileInput.on('change', this.onFileSelected, this);
        this.fileInput.hide();
    },
    click: function () {
        this.fileInput.dom.click();
    },
    onFileSelected: function () {
        var v = this.fileInput.dom.value;
        this.setValue(v);
        this.fireEvent('change', this, v);
    },
    /**
     * Clear the chosen file, so that the change event is fired again, if the same file is chosen
     */
    clearFile: function () {
        if (this.fileInput) {
            this.fileInput.dom.value = '';
        }
        this.setValue('');
    }
});
Ext.reg('imageplus-form-filefield', ImagePlus.form.FileField);

/**
 * A field to upload a file or to select it in the media browser. The value is the path of the file.
 */
ImagePlus.combo.UploadBrowser = function (config) {
    config = config || {};
    this.ident = 'imageplus-combo-upload-browser-' + Ext.id();
    if (typeof config.preview === 'string') {
        config.preview = config.preview === 'true';
    }
    ImagePlus.util.applyDefaults(config, {
        width: 400,
        triggerActions: ['clear', 'upload', 'browse'],
        triggerClasses: {
            clear: 'x-form-clear-trigger',
            upload: 'x-form-upload-trigger',
            browse: 'x-form-file-trigger',
        },
        source: MODx.config.default_media_source,
        sourceBaseUrl: '',
        hideFiles: false,
        rootVisible: false,
        allowedFileTypes: 'pdf',
        allowedMIMEtypes: 'application/pdf',
        wctx: 'web',
        openTo: '',
        rootId: '/',
        hideSourceCombo: true,
        isTV: false,
        preview: false,
        previewHeight: 250,
        showDownload: false,
        resourceId: '0',
        tvId: '0',
    });
    ImagePlus.combo.UploadBrowser.superclass.constructor.call(this, config);
};
Ext.extend(ImagePlus.combo.UploadBrowser, ImagePlus.form.MultiTriggerField, {
    onRender: function (ct, position) {
        ImagePlus.combo.UploadBrowser.superclass.onRender.call(this, ct, position);
        // The real field holds the (encoded) value, the visible fake field shows the path
        this.el.dom.style.position = 'absolute';
        this.el.dom.style.left = '-100vw';
        this.fakeEl = Ext.DomHelper.insertBefore(this.el, {
            tag: 'input', type: 'text', cls: 'x-form-text x-form-field',
        }, true);
        this.fakeEl.on('change', function (event, target) {
            this.setValue(this.createValue(target.value));
        }, this);
        this.previewEl = Ext.DomHelper.append(this.container, {
            // The images have a maximum height, the preview contains them and their bars
            tag: 'div', cls: 'x-form-imageplus-preview',
        }, true);
        this.createUploadForm();
    },
    onResize: function (aw, ah, w, h) {
        ImagePlus.combo.UploadBrowser.superclass.onResize.call(this, aw, ah, w, h);
        this.fakeEl.dom.style.width = this.el.dom.style.width;
    },
    onDestroy: function () {
        // The upload form is rendered outside of the field, the fake field and the preview next to it
        Ext.destroy(this.uploadForm, this.fakeEl, this.previewEl);
        ImagePlus.combo.UploadBrowser.superclass.onDestroy.call(this);
    },
    onClearTrigger: function () {
        this.setValue('');
        if (this.isTV) {
            MODx.fireResourceFormChange();
        }
    },
    onUploadTrigger: function () {
        this.uploadField.click();
    },
    onBrowseTrigger: function (event) {
        var browserWindow = MODx.load({
            xtype: 'modx-browser',
            closeAction: 'close',
            id: Ext.id(),
            multiple: true,
            source: this.source,
            hideFiles: this.hideFiles,
            allowedFileTypes: this.allowedFileTypes,
            openTo: this.openTo,
            rootId: this.rootId,
            hideSourceCombo: this.hideSourceCombo,
            listeners: {
                select: {
                    fn: this.onFileChosen,
                    scope: this,
                }
            }
        });
        browserWindow.show(event);
    },
    createUploadForm: function () {
        this.uploadForm = MODx.load({
            xtype: 'modx-formpanel',
            id: this.ident + '-upload-form',
            renderTo: 'modx-content',
            isUpload: true,
            hidden: true,
            url: ImagePlus.config.connectorUrl,
            baseParams: {
                wctx: this.wctx,
                action: 'mgr/file/upload',
                source: this.source,
                resource_id: this.resourceId,
                tv_id: this.tvId,
            },
            items: [{
                xtype: 'imageplus-form-filefield',
                id: this.ident + '-upload-filefield',
                name: this.name,
                allowedMIMEtypes: this.allowedMIMEtypes,
                listeners: {
                    change: {
                        fn: this.onUploadFileSelected,
                        scope: this,
                    }
                }
            }],
            listeners: {
                success: {
                    fn: function (o) {
                        this.onFileChosen(o.result.object);
                    },
                    scope: this
                }
            },
        });
        this.uploadField = Ext.getCmp(this.ident + '-upload-filefield');
        this.uploadForm.form.el.dom.setAttribute('enctype', 'multipart/form-data');
        this.relayEvents(this.uploadForm.form, ['failure', 'submit']);
        // The file is cleared after the upload, otherwise the same file can't be uploaded again
        this.uploadForm.form.on({
            actioncomplete: this.uploadField.clearFile,
            actionfailed: this.uploadField.clearFile,
            scope: this.uploadField
        });
    },
    onUploadFileSelected: function () {
        this.uploadForm.form.baseParams.file = this.uploadField.getValue();
        this.uploadForm.submit();
    },
    /**
     * Called with the file data of the media browser or of the upload processor
     */
    onFileChosen: function (data) {
        this.setValue({
            image: data.relativeUrl,
            baseUrl: data.baseUrl || ImagePlus.util.sourceObjectUrl(data.fullRelativeUrl),
        });
    },
    /**
     * Create the value for a path entered in the field
     */
    createValue: function (path) {
        return {
            image: path,
            baseUrl: (path && this.sourceBaseUrl) ? this.sourceBaseUrl + path : '',
        };
    },
    /**
     * Get the value object from a value, that is an object, a JSON encoded object or a path
     */
    parseValue: function (value) {
        return ImagePlus.util.parseFileValue(value);
    },
    /**
     * Get the value of the form field from the value object
     */
    encodeValue: function (value) {
        return value.image || '';
    },
    setValue: function (value) {
        ImagePlus.combo.UploadBrowser.superclass.setValue.call(this, value);
        if (this.el && this.fakeEl) {
            var parsedValue = this.parseValue(value);
            this.el.dom.value = this.encodeValue(parsedValue);
            this.fakeEl.dom.value = parsedValue.image || '';
            // The parent validated the unencoded value
            this.validate();
            this.updatePreview(parsedValue);
            this.fireEvent('change');
        }
    },
    markInvalid: function (msg) {
        ImagePlus.combo.UploadBrowser.superclass.markInvalid.call(this, msg);
        // The real field is hidden, so the visible field shows the invalid state
        if (this.fakeEl) {
            this.fakeEl.addClass(this.invalidClass);
        }
    },
    clearInvalid: function () {
        ImagePlus.combo.UploadBrowser.superclass.clearInvalid.call(this);
        if (this.fakeEl) {
            this.fakeEl.removeClass(this.invalidClass);
        }
    },
    updatePreview: function (value) {
        if (this.preview && this.previewEl) {
            this.previewEl.update('');
            if (!Ext.isEmpty(value.image)) {
                var mainImage = this.renderPreviewMain(value.image, '', 'default');
                this.renderPreviewDownload(mainImage, value.baseUrl);
            }
        }
    },
    renderPreviewMain: function (src, alt, key) {
        // The attributes are not escaped by the DomHelper
        var mainImage = this.previewEl.createChild({
            tag: 'div',
            cls: 'x-form-imageplus-preview-main',
            'data-key': Ext.util.Format.htmlEncode(key)
        });
        mainImage.createChild({
            tag: 'img',
            src: this.getThumbUrl(src, this.previewHeight),
            style: 'max-height: ' + this.previewHeight + 'px',
            alt: Ext.util.Format.htmlEncode(alt)
        });
        return mainImage;
    },
    renderPreviewDownload: function (mainImage, url) {
        if (this.showDownload && url) {
            mainImage.createChild({
                tag: 'a',
                title: _('imageplus.cropimage.original.download'),
                cls: 'download',
                href: url,
                target: '_blank',
                html: '<i class="icon icon-download"></i>',
            });
        }
    },
    getThumbUrl: function (src, height) {
        // Request a sharp thumbnail on high resolution displays
        var pixelRatio = Math.min(2, window.devicePixelRatio || 1);
        return ImagePlus.util.thumbUrl(src, this.source, {h: Math.round(height * pixelRatio)});
    }
});
Ext.reg('imageplus-combo-upload-browser', ImagePlus.combo.UploadBrowser);
