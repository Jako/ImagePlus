/**
 * Image+ UploadCropBrowser Component
 *
 * @package imageplus
 * @subpackage script
 */

/**
 * A field to upload an image or to select it in the media browser and to crop it in several sizes.
 * The value is a JSON encoded object with the image path, its url and the crops of the image.
 */
ImagePlus.combo.UploadCropBrowser = function (config) {
    config = config || {};
    if (typeof config.sizes === 'string') {
        config.sizes = Ext.decode(config.sizes);
    }
    if (typeof config.preview === 'string') {
        config.preview = config.preview === 'true';
    }
    ImagePlus.util.applyDefaults(config, {
        triggerActions: ['clear', 'crop', 'upload', 'browse'],
        triggerClasses: {
            clear: 'x-form-clear-trigger',
            crop: 'x-form-crop-trigger',
            upload: 'x-form-upload-trigger',
            browse: 'x-form-image-trigger',
        },
        sizes: {standard: {name: 'Standard', size: '1000x500'}},
        preview: true,
        autoOpen: false,
        sourceBaseUrl: MODx.config.base_url,
        allowedFileTypes: 'jpg,jpeg,png,gif,webp',
        allowedMIMEtypes: 'image/jpeg,image/png,image/gif,image/webp',
        thumbnailHeight: 100,
        // The minimal size of the selected or uploaded image
        minWidth: 0,
        minHeight: 0,
        // Show the image and its crops in one line, if the field is at least inlinePreviewWidth wide
        allowInlinePreview: true,
        inlinePreviewWidth: 700,
    });
    config.sizes = ImagePlus.util.normalizeCropSizes(config.sizes);
    ImagePlus.combo.UploadCropBrowser.superclass.constructor.call(this, config);
};
Ext.extend(ImagePlus.combo.UploadCropBrowser, ImagePlus.combo.UploadBrowser, {
    onCropTrigger: function (event) {
        this.openCropWindow('default', event);
    },
    openCropWindow: function (size, event) {
        var value = this.parseValue(this.getValue());
        if (Ext.isEmpty(value.image)) {
            return;
        }
        var cropImageWindow = MODx.load({
            xtype: 'imageplus-window-crop-image',
            closeAction: 'close',
            wctx: this.wctx,
            source: this.source,
            cropperSize: size || 'default',
            // Each window gets its own copy, so that the crops are not shared between the windows
            cropperSizes: Ext.decode(Ext.encode(this.sizes)),
            record: value,
            resourceId: this.resourceId,
            tvId: this.tvId,
            listeners: {
                select: {
                    fn: this.setValue,
                    scope: this
                }
            }
        });
        cropImageWindow.show(event);
    },
    createUploadForm: function () {
        ImagePlus.combo.UploadCropBrowser.superclass.createUploadForm.call(this);
        // The upload processor rejects images below the minimal size
        Ext.apply(this.uploadForm.form.baseParams, {
            min_width: this.minWidth,
            min_height: this.minHeight
        });
    },
    onFileChosen: function (data) {
        this.checkMinSize(data, function () {
            ImagePlus.combo.UploadCropBrowser.superclass.onFileChosen.call(this, data);
            if (this.autoOpen) {
                this.openCropWindow('default');
            }
        });
    },
    /**
     * Call the callback, if the chosen image has the minimal size, otherwise show an error message.
     * The size is taken from the file data of the media browser/upload processor or by loading the image.
     */
    checkMinSize: function (data, callback) {
        if (!this.minWidth && !this.minHeight) {
            callback.call(this);
            return;
        }
        var check = function (width, height) {
            var error = '';
            if (this.minWidth && width < this.minWidth) {
                error = _('imageplus.cropimage.error_min_width', {width: width, min_width: this.minWidth});
            } else if (this.minHeight && height < this.minHeight) {
                error = _('imageplus.cropimage.error_min_height', {height: height, min_height: this.minHeight});
            }
            if (error) {
                MODx.msg.alert(_('error'), error);
            } else {
                callback.call(this);
            }
        };
        var width = parseInt(data.image_width || data.width, 10);
        var height = parseInt(data.image_height || data.height, 10);
        if (width && height) {
            check.call(this, width, height);
            return;
        }
        var image = new Image();
        image.onload = function () {
            check.call(this, image.naturalWidth, image.naturalHeight);
        }.bind(this);
        image.onerror = function () {
            MODx.msg.alert(_('error'), _('imageplus.cropimage.error_image_not_valid'));
        };
        image.src = data.baseUrl || MODx.config.base_url + data.fullRelativeUrl;
    },
    encodeValue: function (value) {
        return value.image ? Ext.encode(value) : '';
    },
    onResize: function (aw, ah, w, h) {
        ImagePlus.combo.UploadCropBrowser.superclass.onResize.call(this, aw, ah, w, h);
        var inlinePreview = this.allowInlinePreview !== false && typeof w === 'number' && w >= this.inlinePreviewWidth;
        if (inlinePreview !== !!this.inlinePreview) {
            this.inlinePreview = inlinePreview;
            this.updatePreview(this.parseValue(this.getValue()));
        }
    },
    updatePreview: function (value) {
        if (!this.preview || !this.previewEl) {
            return;
        }
        this.previewEl.update('');
        this.previewEl[this.inlinePreview ? 'addClass' : 'removeClass']('x-form-imageplus-preview-inline');
        if (Ext.isEmpty(value.image)) {
            return;
        }
        // The crops are shown first in the order of the crop sizes, followed by the original image.
        // The first image is the main preview image, the others are shown as thumbnails.
        var images = [];
        Ext.iterate(this.sizes, function (key, size) {
            var crop = (value.sizes || {})[key];
            if (crop && crop.image) {
                images.push({key: key, name: size.name, image: crop.image, dimensions: this.getCropDimensions(crop)});
            }
        }, this);
        images.push({
            key: 'default',
            name: _('imageplus.cropimage.default'),
            image: value.image,
            baseUrl: value.baseUrl,
            dimensions: this.getOriginalDimensions(value)
        });
        var main = images.shift();
        var mainImage = this.renderPreviewMain(main.image, main.name, main.key);
        mainImage.createChild({
            tag: 'a',
            cls: 'crop',
        }).on('click', function (event) {
            event.preventDefault();
            this.openCropWindow(main.key);
        }, this);
        this.renderPreviewBar(mainImage, main.name, main.dimensions, main.baseUrl);
        this.renderPreviewThumbnails(images);
    },
    /**
     * Render a bar below a preview image with the name, the pixel dimensions and the download link of the image
     */
    renderPreviewBar: function (item, name, dimensions, downloadUrl) {
        var bar = item.createChild({
            tag: 'span',
            cls: 'x-form-imageplus-preview-bar',
            cn: [
                {tag: 'span', cls: 'x-form-imageplus-preview-bar-name', html: Ext.util.Format.htmlEncode(name)},
                {tag: 'span', cls: 'x-form-imageplus-preview-bar-dimensions'}
            ]
        });
        this.renderPreviewDownload(bar, downloadUrl);
        var dimensionsEl = bar.child('.x-form-imageplus-preview-bar-dimensions');
        var update = function (dimensions) {
            if (dimensions && dimensions.width && dimensions.height) {
                dimensionsEl.update(dimensions.width + ' × ' + dimensions.height + ' px');
            }
        };
        if (dimensions && typeof dimensions.then === 'function') {
            dimensions.then(update);
        } else {
            update(dimensions);
        }
    },
    /**
     * Get the pixel dimensions of the original image from the crops or by loading the image
     */
    getOriginalDimensions: function (value) {
        var dimensions = null;
        Ext.iterate(value.sizes || {}, function (key, crop) {
            if (crop.originalWidth && crop.originalHeight) {
                dimensions = {width: parseInt(crop.originalWidth, 10), height: parseInt(crop.originalHeight, 10)};
                return false;
            }
        });
        if (dimensions || !value.baseUrl) {
            return dimensions;
        }
        this.originalDimensions = this.originalDimensions || {};
        if (!this.originalDimensions[value.baseUrl]) {
            this.originalDimensions[value.baseUrl] = new Promise(function (resolve) {
                var image = new Image();
                image.onload = function () {
                    resolve({width: image.naturalWidth, height: image.naturalHeight});
                };
                image.onerror = function () {
                    resolve(null);
                };
                image.src = value.baseUrl;
            });
        }
        return this.originalDimensions[value.baseUrl];
    },
    /**
     * Get the pixel dimensions of a crop like the crop processor calculates them
     */
    getCropDimensions: function (crop) {
        var cropWidth = parseInt(crop.cropWidth, 10);
        var cropHeight = parseInt(crop.cropHeight, 10);
        var targetWidth = parseInt(crop.targetWidth, 10);
        var targetHeight = parseInt(crop.targetHeight, 10);
        if (!cropWidth || !cropHeight) {
            return null;
        }
        if (!targetWidth && !targetHeight) {
            return {width: cropWidth, height: cropHeight};
        } else if (!targetWidth) {
            return {width: Math.floor(targetHeight / cropHeight * cropWidth), height: targetHeight};
        } else if (!targetHeight) {
            return {width: targetWidth, height: Math.floor(targetWidth / cropWidth * cropHeight)};
        }
        return {width: targetWidth, height: targetHeight};
    },
    renderPreviewThumbnails: function (images) {
        if (!images.length) {
            return;
        }
        var thumbnails = this.previewEl.createChild({
            tag: 'div',
            cls: 'x-form-imageplus-preview-thumbnails',
        });
        // In one line the thumbnails have the half height of the main image, otherwise they are shown small on hover
        var height = this.inlinePreview ? Math.round(this.previewHeight / 2) : this.thumbnailHeight;
        Ext.each(images, function (image) {
            // The item has the same structure as the main image: the image, a crop link above it and the bar
            var item = thumbnails.createChild({
                tag: 'div',
                cls: 'x-form-imageplus-preview-image x-form-imageplus-preview-image-' + image.key,
                // Hidden below the preview by the image height, the bar height and the shadow, until it is shown on hover
                style: this.inlinePreview ? '' : 'bottom: -' + (height + 100) + 'px',
                'data-key': image.key
            });
            item.createChild({
                tag: 'img',
                src: this.getThumbUrl(image.image, height),
                style: 'height: ' + height + 'px',
                alt: image.name
            });
            item.createChild({
                tag: 'a',
                href: '#',
                cls: 'crop'
            }).on('click', function (event) {
                event.preventDefault();
                this.openCropWindow(image.key);
            }, this);
            this.renderPreviewBar(item, image.name, image.dimensions, image.baseUrl);
        }, this);
    }
});
Ext.reg('imageplus-combo-upload-crop-browser', ImagePlus.combo.UploadCropBrowser);

ImagePlus.window.CropImage = function (config) {
    config = config || {};
    this.ident = 'imageplus-window-cropimage-' + Ext.id();
    Ext.applyIf(config, {
        title: _('imageplus.cropimage'),
        cls: 'x-window-imageplus-cropimage',
        minWidth: 500,
        minHeight: 300,
        width: Ext.getBody().getViewSize().width * 0.9,
        height: Ext.getBody().getViewSize().height * 0.9,
        layout: 'border',
        keys: false,
        items: [{
            id: this.ident + '-cropimage',
            region: 'center',
            border: false,
            listeners: {
                resize: {
                    fn: this.onResizeCropper,
                    scope: this
                }
            }
        }],
        buttons: [{
            text: _('cancel'),
            handler: this.onBeforeClose,
            scope: this,
            action: 'close'
        }, {
            text: _('ok'),
            cls: 'primary-button',
            handler: this.onBeforeClose,
            scope: this,
            action: 'select'
        }]
    });
    ImagePlus.window.CropImage.superclass.constructor.call(this, config);
    this.on('afterrender', this.onAfterRender);
    this.on('destroy', this.onDestroyCropper);
    this.on('beforeclose', this.onBeforeClose);
};
Ext.extend(ImagePlus.window.CropImage, MODx.Window, {
    onAfterRender: function () {
        this.renderCropperContent(Ext.getCmp(this.ident + '-cropimage').body.dom);
        this.cropperImage = Ext.get(this.ident + '-cropimage-cropper');
        this.cropperState = 'ready';
        this.cropperRecord = this.prepareCropperRecord();
        this.cropperSizes = this.prepareCropperSizes();
        this.cropperSize = this.prepareCropperDefaultSize();
        this.cropperSizeData = this.getCropperSizeData();
        this.renderCropperSets();
        if (this.cropperSizeData) {
            this.cropper = new Cropper(document.getElementById(this.ident + '-cropimage-cropper'), {
                data: this.cropperSizeData,
                viewMode: 2,
                dragMode: 'none',
                zoomable: false,
                toggleDragModeOnDblclick: false,
                autoCropArea: 1,
                // Keep the crop area when the browser window is resized
                restore: true,
                crop: (function (event) {
                    this.updateCropperData(event.detail);
                }).bind(this),
                cropstart: (function () {
                    this.setCropperState('start');
                }).bind(this),
                cropend: (function () {
                    this.setCropperState('stop');
                }).bind(this),
                ready: (function () {
                    this.resetCropperSize();
                }).bind(this),
            });
        }
    },
    createDomElement: function (tag, attributes, children) {
        var el = document.createElement(tag);
        Ext.iterate(attributes || {}, function (key, value) {
            if (key === 'text') {
                el.textContent = value;
            } else if (typeof value === 'boolean') {
                el[key] = value;
            } else {
                el.setAttribute(key, value);
            }
        });
        (children || []).forEach(function (child) {
            el.appendChild(child);
        });
        return el;
    },
    createCropperField: function (key, label, attributes) {
        var input = this.createDomElement('input', Ext.apply({
            type: 'number',
            id: this.ident + '-cropimage-property-' + key,
            class: 'x-form-text x-form-field',
        }, attributes));
        // Show the focus border of the manager theme
        input.addEventListener('focus', function () {
            input.classList.add('x-form-focus');
        });
        input.addEventListener('blur', function () {
            input.classList.remove('x-form-focus');
        });
        return this.createDomElement('div', {class: 'imageplus-cropimage-field'}, [
            this.createDomElement('label', {for: input.id, text: label}),
            input
        ]);
    },
    createCropperFieldset: function (cls, legend, fields) {
        return this.createDomElement('fieldset', {class: 'imageplus-cropimage-fieldset ' + cls}, [
            this.createDomElement('legend', {text: legend})
        ].concat(fields));
    },
    renderCropperContent: function (container) {
        var resetButton = this.createDomElement('button', {type: 'button', class: 'imageplus-cropimage-button', text: _('imageplus.cropimage.reset')});
        resetButton.addEventListener('click', this.resetCropperSize.bind(this));
        var saveButton = this.createDomElement('button', {type: 'button', class: 'imageplus-cropimage-button imageplus-cropimage-button-primary', text: _('imageplus.cropimage.save')});
        saveButton.addEventListener('click', this.updateCropperRecord.bind(this));

        var cropFields = [
            this.createCropperField('crop-x', _('imageplus.cropimage.crop.x'), {min: 0, 'data-key': 'x'}),
            this.createCropperField('crop-y', _('imageplus.cropimage.crop.y'), {min: 0, 'data-key': 'y'}),
            this.createCropperField('crop-width', _('imageplus.cropimage.crop.width'), {min: 1, 'data-key': 'cropWidth'}),
            this.createCropperField('crop-height', _('imageplus.cropimage.crop.height'), {min: 1, 'data-key': 'cropHeight'}),
            this.createCropperField('proportion', _('imageplus.cropimage.crop.proportion'), {type: 'text', disabled: true})
        ];
        cropFields.forEach((function (field) {
            var input = field.querySelector('input[data-key]');
            if (input) {
                input.addEventListener('change', (function () {
                    this.setCropperSize(input.dataset.key, parseInt(input.value, 10) || 0);
                }).bind(this));
            }
        }).bind(this));

        container.appendChild(this.createDomElement('div', {class: 'imageplus-cropimage'}, [
            this.createDomElement('div', {class: 'imageplus-cropimage-sidebar'}, [
                this.createDomElement('div', {class: 'imageplus-cropimage-title', text: _('imageplus.cropimage.sets')}),
                this.createDomElement('div', {class: 'imageplus-cropimage-sets', id: this.ident + '-cropimage-sizes'}),
                this.createDomElement('div', {class: 'imageplus-cropimage-toolbar'}, [resetButton, saveButton]),
                this.createDomElement('div', {class: 'imageplus-cropimage-state', id: this.ident + '-cropimage-state', hidden: true})
            ]),
            this.createDomElement('div', {class: 'imageplus-cropimage-main'}, [
                this.createDomElement('div', {class: 'imageplus-cropimage-image'}, [
                    this.createDomElement('img', {src: Ext.BLANK_IMAGE_URL, id: this.ident + '-cropimage-cropper', alt: ''})
                ]),
                this.createDomElement('div', {class: 'imageplus-cropimage-values'}, [
                    this.createCropperFieldset('original', _('imageplus.cropimage.original') + ':', [
                        this.createCropperField('original-width', _('imageplus.cropimage.original.width'), {disabled: true}),
                        this.createCropperField('original-height', _('imageplus.cropimage.original.height'), {disabled: true})
                    ]),
                    this.createCropperFieldset('target', _('imageplus.cropimage.target') + ':', [
                        this.createCropperField('target-width', _('imageplus.cropimage.target.width'), {disabled: true}),
                        this.createCropperField('target-height', _('imageplus.cropimage.target.height'), {disabled: true})
                    ]),
                    this.createCropperFieldset('crop', _('imageplus.cropimage.crop') + ':', cropFields)
                ])
            ])
        ]));
    },
    renderCropperSets: function () {
        var container = document.getElementById(this.ident + '-cropimage-sizes');
        Ext.iterate(this.cropperSizes, function (key, size) {
            var input = this.createDomElement('input', {
                type: 'radio',
                name: this.ident + '-size',
                value: key,
                checked: key === this.cropperSize
            });
            input.addEventListener('change', this.selectCropperSize.bind(this, key));
            container.appendChild(this.createDomElement('label', {class: 'imageplus-cropimage-set'}, [
                input,
                this.createDomElement('div', {class: 'imageplus-cropimage-preview-image'}, [
                    this.createDomElement('img', {src: size.baseUrl || Ext.BLANK_IMAGE_URL, id: this.ident + '-cropimage-preview-' + key, alt: size.name})
                ]),
                this.createDomElement('div', {class: 'imageplus-cropimage-preview-label', text: size.name})
            ]));
        }, this);
    },
    onResizeCropper: function () {
        if (this.cropper && this.cropper.ready) {
            // Resizing re-renders the cropper, so the crop area is restored in image pixels afterwards.
            // The window layout can finish after the cropper is ready and has set the saved crop area.
            var data = this.cropper.getData();
            this.cropper.resize();
            this.cropper.setData(data);
        }
    },
    onDestroyCropper: function () {
        if (this.cropper) {
            this.cropper.destroy();
        }
    },
    onBeforeClose: function (btn) {
        // The confirm callbacks receive the answer, so the action of the clicked button is read here
        var select = !!(btn && btn.action === 'select');
        var warning = null;
        if (this.cropper) {
            if (Object.keys(this.cropperSizes).some(function (key) {
                return !this.cropperSizes[key].cropWidth;
            }, this)) {
                warning = 'unset';
            } else if (this.cropperState !== 'saved' && this.cropperState !== 'ready') {
                warning = 'changes';
            }
        }
        if (warning) {
            Ext.MessageBox.confirm(_('imageplus.cropimage.' + warning + '.title'), _('imageplus.cropimage.' + warning + '.content'), function (answer) {
                if (answer === 'yes') {
                    this.closeCropper(select);
                }
            }, this);
            return false;
        }
        this.closeCropper(select);
    },
    closeCropper: function (select) {
        if (select && this.cropper) {
            this.fireEvent('select', {
                image: this.cropperRecord.image || '',
                baseUrl: this.cropperRecord.baseUrl || '',
                sizes: this.cropperSizes || {},
            });
        }
        this.destroy();
    },
    prepareCropperRecord: function () {
        var record = {
            image: this.record.image || '',
            baseUrl: this.record.baseUrl || '',
            sizes: this.record.sizes || {}
        };
        if (Ext.isEmpty(record.baseUrl)) {
            this.cropperImage.dom.src = Ext.BLANK_IMAGE_URL;
        } else {
            this.cropperImage.dom.src = record.baseUrl;
        }
        return record;
    },
    prepareCropperSizes: function () {
        // Sizes with an invalid configuration are skipped
        var sizes = {};
        Ext.iterate(this.cropperSizes, function (key, data) {
            var defaults = this.parseCropperSize(key, data);
            if (defaults) {
                sizes[key] = Ext.applyIf(Ext.applyIf(Ext.apply({}, data), defaults), this.cropperRecord.sizes[key] || {});
            }
        }, this);
        return sizes;
    },
    /**
     * Get the target size, the aspect ratio and the proportion of a size configuration
     */
    parseCropperSize: function (key, data) {
        var match, width, height;
        if (data.size) {
            match = String(data.size).match(/^([0-9]*)x([0-9]*)$/);
            if (!match) {
                return null;
            }
            width = parseInt(match[1], 10) || NaN;
            height = parseInt(match[2], 10) || NaN;
            return {
                key: key,
                targetWidth: width,
                targetHeight: height,
                ratio: (width && height) ? Ext.util.Format.round(width / height, 10) : NaN,
                proportion: (width && height) ? this.getRatio(width, height) : ''
            };
        } else if (data.proportion) {
            match = String(data.proportion).match(/^([0-9]+)\s*x\s*([0-9]+)$/);
            if (!match) {
                return null;
            }
            width = parseInt(match[1], 10);
            height = parseInt(match[2], 10);
            return {
                key: key,
                targetWidth: NaN,
                targetHeight: NaN,
                ratio: (height) ? Ext.util.Format.round(width / height, 10) : NaN,
                proportion: this.getRatio(width, height)
            };
        } else if (data.width) {
            match = String(data.width).match(/^([0-9]+)$/);
            return (match) ? {key: key, targetWidth: parseInt(match[1], 10), targetHeight: NaN, ratio: NaN} : null;
        } else if (data.height) {
            match = String(data.height).match(/^([0-9]+)$/);
            return (match) ? {key: key, targetWidth: NaN, targetHeight: parseInt(match[1], 10), ratio: NaN} : null;
        }
        return {key: key, targetWidth: NaN, targetHeight: NaN, ratio: NaN};
    },
    prepareCropperDefaultSize: function () {
        if (this.cropperSize === 'default' || !this.cropperSizes[this.cropperSize]) {
            return Object.keys(this.cropperSizes)[0];
        }
        return this.cropperSize;
    },
    setCropperSizeData: function (data) {
        this.cropperSizes[this.cropperSize] = data;
    },
    getCropperSizeData: function () {
        return this.cropperSizes[this.cropperSize] || {};
    },
    getCropperCanvasData: function () {
        var imageData = this.cropper.getImageData();
        var canvasData = this.cropper.getData();
        var sizeData = this.getCropperSizeData();
        return {
            originalWidth: imageData.naturalWidth,
            originalHeight: imageData.naturalHeight,
            targetWidth: sizeData.targetWidth,
            targetHeight: sizeData.targetHeight,
            cropWidth: Math.floor(canvasData.width),
            cropHeight: Math.floor(canvasData.height),
            scaleX: canvasData.scaleX,
            scaleY: canvasData.scaleY,
            x: Math.floor(canvasData.x),
            y: Math.floor(canvasData.y),
            proportion: sizeData.proportion,
        };
    },
    setCropperSize: function (key, value) {
        if (this.cropper) {
            var data = this.getCropperCanvasData();
            var cropperData = {
                rotate: 0,
                scaleX: 1,
                scaleY: 1,
            };
            var ratio, top, left;
            switch (key) {
                case 'x':
                    cropperData.x = value;
                    break;
                case 'y':
                    cropperData.y = value;
                    break;
                case 'cropWidth':
                    ratio = data.cropWidth / data.cropHeight;
                    left = Math.floor((value - data.cropWidth) / 2);
                    top = left / ratio;
                    cropperData.x = data.x - left;
                    cropperData.y = data.y - top;
                    cropperData.width = value;
                    break;
                case 'cropHeight':
                    ratio = data.cropWidth / data.cropHeight;
                    top = Math.floor((value - data.cropHeight) / 2);
                    left = top / ratio;
                    cropperData.x = data.x - left;
                    cropperData.y = data.y - top;
                    cropperData.height = value;
                    break;
            }
            this.cropper.setData(cropperData);
            this.setCropperState('start');
        }
    },
    setCropperState: function (state, content) {
        if (this.cropper) {
            this.cropperState = state;
            var stateEl = document.getElementById(this.ident + '-cropimage-state');
            stateEl.classList.remove('red', 'green', 'loading');
            stateEl.hidden = false;
            if (state === 'start' || state === 'stop') {
                stateEl.classList.add('red');
                stateEl.textContent = _('imageplus.cropimage.changes_unsaved');
            } else if (state === 'saved') {
                stateEl.classList.add('green');
                stateEl.textContent = _('imageplus.cropimage.changes_saved');
            } else if (state === 'saving') {
                stateEl.classList.add('loading');
                stateEl.textContent = _('imageplus.cropimage.saving');
            } else if (state === 'failure') {
                stateEl.classList.add('red');
                stateEl.textContent = content;
            } else {
                stateEl.hidden = true;
            }
            var enableAll = this.cropperState === 'saved' || this.cropperState === 'ready';
            document.querySelectorAll('#' + this.ident + '-cropimage-sizes input').forEach((function (input) {
                input.disabled = !enableAll && input.value !== this.cropperSize;
            }).bind(this));
        }
    },
    setCropperField: function (key, value) {
        var input = document.getElementById(this.ident + '-cropimage-property-' + key);
        if (input) {
            input.value = (typeof value === 'undefined' || value === null || (typeof value === 'number' && isNaN(value))) ? '' : value;
        }
    },
    updateCropperData: function (event) {
        if (this.cropper) {
            var data = this.getCropperCanvasData(event);
            if (data) {
                this.setCropperField('original-width', data.originalWidth);
                this.setCropperField('original-height', data.originalHeight);
                this.setCropperField('target-width', data.targetWidth);
                this.setCropperField('target-height', data.targetHeight);
                this.setCropperField('crop-x', data.x);
                this.setCropperField('crop-y', data.y);
                this.setCropperField('crop-width', data.cropWidth);
                this.setCropperField('crop-height', data.cropHeight);
                this.setCropperField('proportion', ImagePlus.util.formatDimensions(data.proportion));
            }
        }
    },
    updateCropperImage: function () {
        if (this.cropper) {
            var previewImage = document.getElementById(this.ident + '-cropimage-preview-' + this.cropperSize);
            if (previewImage) {
                previewImage.src = this.cropperSizes[this.cropperSize].baseUrl;
            }
        }
    },
    selectCropperSize: function (key) {
        if (this.cropper) {
            if (key) {
                this.cropperSize = key;
                this.resetCropperSize();
            }
        }
    },
    resetCropperSize: function () {
        if (this.cropper) {
            var data = this.getCropperSizeData();
            var cropperData = {
                rotate: 0,
                scaleX: 1,
                scaleY: 1,
            };
            if (data) {
                this.cropper.setAspectRatio((data.ratio) ? data.ratio : NaN);
                if (data.cropWidth && data.cropHeight) {
                    cropperData.x = data.x;
                    cropperData.y = data.y;
                    cropperData.width = data.cropWidth;
                    cropperData.height = data.cropHeight;
                } else {
                    cropperData.width = data.originalWidth;
                    cropperData.height = data.originalHeight;
                }
                this.cropper.setData(cropperData);
                this.setCropperState('ready');
            }
        }
    },
    updateCropperRecord: function () {
        if (this.cropper) {
            var data = {};
            Ext.apply(data, this.getCropperSizeData());
            Ext.apply(data, this.getCropperCanvasData());
            MODx.Ajax.request({
                url: ImagePlus.config.connectorUrl,
                params: {
                    action: 'mgr/image/crop',
                    resource_id: this.resourceId,
                    tv_id: this.tvId,
                    wctx: this.wctx,
                    source: this.source,
                    image: this.cropperRecord.image,
                    originalWidth: data.originalWidth,
                    originalHeight: data.originalHeight,
                    cropWidth: data.cropWidth,
                    cropHeight: data.cropHeight,
                    targetWidth: data.targetWidth,
                    targetHeight: data.targetHeight,
                    scaleX: data.scaleX,
                    scaleY: data.scaleY,
                    x: data.x,
                    y: data.y
                },
                listeners: {
                    success: {
                        fn: function (result) {
                            data.image = result.object.image;
                            data.baseUrl = result.object.baseUrl;
                            this.setCropperSizeData(data);
                            this.updateCropperImage();
                            this.setCropperState('saved');
                        },
                        scope: this
                    },
                    failure: {
                        fn: function (result) {
                            this.setCropperState('failure', result.message);
                        },
                        scope: this
                    }
                }
            });
            this.setCropperState('saving');
        }
    },
    getGCD: function (u, v) {
        var t;
        while (v) {
            t = v;
            v = u % v;
            u = t;
        }
        return u;
    },
    getRatio: function (w, h) {
        var d = this.getGCD(w, h);
        return (w / d) + ' x ' + (h / d);
    }
});
Ext.reg('imageplus-window-crop-image', ImagePlus.window.CropImage);
