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
        var check = function (dimensions) {
            var error = '';
            if (!dimensions) {
                error = _('imageplus.cropimage.error_image_not_valid');
            } else if (this.minWidth && dimensions.width < this.minWidth) {
                error = _('imageplus.cropimage.error_min_width', {width: dimensions.width, min_width: this.minWidth});
            } else if (this.minHeight && dimensions.height < this.minHeight) {
                error = _('imageplus.cropimage.error_min_height', {height: dimensions.height, min_height: this.minHeight});
            }
            if (error) {
                MODx.msg.alert(_('error'), error);
            } else {
                callback.call(this);
            }
        }.bind(this);
        // The upload processor sends the size of the upright image. The media browser sends the stored size of the image,
        // that ignores the EXIF orientation, so the size of an image selected in the media browser is taken in the browser.
        var width = parseInt(data.width, 10);
        var height = parseInt(data.height, 10);
        if (width && height && !('original_width' in data || 'image_width' in data)) {
            check({width: width, height: height});
        } else {
            ImagePlus.util.loadImageDimensions(data.baseUrl || ImagePlus.util.sourceObjectUrl(data.fullRelativeUrl)).then(check);
        }
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
        // The first image is the main preview image, the others are shown as thumbnails
        var images = this.getPreviewImages(value);
        var main = images.shift();
        var mainImage = this.renderPreviewMain(main.image, main.name, main.key);
        this.renderPreviewCropLink(mainImage, main.key);
        this.renderPreviewBar(mainImage, main.name, main.dimensions, main.baseUrl);
        this.renderPreviewThumbnails(images);
    },
    /**
     * Get the crops in the order of the crop sizes, followed by the original image
     */
    getPreviewImages: function (value) {
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
        return images;
    },
    /**
     * Render a link above a preview image, that opens the crop window for the image
     */
    renderPreviewCropLink: function (item, key) {
        item.createChild({
            tag: 'a',
            href: '#',
            cls: 'crop'
        }).on('click', function (event) {
            event.preventDefault();
            this.openCropWindow(key);
        }, this);
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
        // The image is loaded once for every preview update
        this.originalDimensions = this.originalDimensions || {};
        if (!this.originalDimensions[value.baseUrl]) {
            this.originalDimensions[value.baseUrl] = ImagePlus.util.loadImageDimensions(value.baseUrl);
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
            this.renderPreviewCropLink(item, image.key);
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
        cls: 'x-window-imageplus-cropimage modx' + ImagePlus.config.modxversion,
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
        this.cropperState = 'ready';
        this.cropperRecord = this.prepareCropperRecord();
        this.cropperSizes = this.prepareCropperSizes();
        this.cropperSize = this.prepareCropperDefaultSize();
        this.cropperSizeData = this.getCropperSizeData();
        this.renderCropperSets();
        if (this.cropperSizeData) {
            this.cropper = new Cropper(this.getCropperEl('cropper'), {
                data: this.cropperSizeData,
                viewMode: 2,
                dragMode: 'none',
                zoomable: false,
                toggleDragModeOnDblclick: false,
                autoCropArea: 1,
                // Keep the crop area when the browser window is resized
                restore: true,
                // The browser shows the image upright by its EXIF orientation, so that the crop area is set on the
                // upright image like it is cropped by the crop processor
                checkOrientation: false,
                crop: this.updateCropperData.bind(this),
                cropstart: this.setCropperState.bind(this, 'start'),
                cropend: this.setCropperState.bind(this, 'stop'),
                ready: this.onCropperReady.bind(this)
            });
        }
    },
    /**
     * Get an element of the crop window by the suffix of its id
     */
    getCropperEl: function (suffix) {
        return document.getElementById(this.ident + '-cropimage-' + suffix);
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
        // The editable crop fields change the crop area
        if (input.dataset.key) {
            input.addEventListener('change', function () {
                this.setCropperSize(input.dataset.key, parseInt(input.value, 10) || 0);
            }.bind(this));
        }
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
    createCropperButton: function (cls, text, handler) {
        var button = this.createDomElement('button', {type: 'button', class: 'imageplus-cropimage-button ' + cls, text: text});
        button.addEventListener('click', handler.bind(this));
        return button;
    },
    renderCropperContent: function (container) {
        container.appendChild(this.createDomElement('div', {class: 'imageplus-cropimage'}, [
            this.createDomElement('div', {class: 'imageplus-cropimage-sidebar'}, [
                this.createDomElement('div', {class: 'imageplus-cropimage-title', text: _('imageplus.cropimage.sets')}),
                this.createDomElement('div', {class: 'imageplus-cropimage-sets', id: this.ident + '-cropimage-sizes'}),
                this.createDomElement('div', {class: 'imageplus-cropimage-toolbar'}, [
                    this.createCropperButton('imageplus-cropimage-button-primary', _('imageplus.cropimage.save'), this.updateCropperRecord),
                    this.createCropperButton('', _('imageplus.cropimage.reset'), this.resetCropperSize),
                    this.createCropperButton('', _('imageplus.cropimage.reload'), this.reloadCropperImage)
                ]),
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
                    this.createCropperFieldset('crop', _('imageplus.cropimage.crop') + ':', [
                        this.createCropperField('crop-x', _('imageplus.cropimage.crop.x'), {min: 0, 'data-key': 'x'}),
                        this.createCropperField('crop-y', _('imageplus.cropimage.crop.y'), {min: 0, 'data-key': 'y'}),
                        this.createCropperField('crop-width', _('imageplus.cropimage.crop.width'), {min: 1, 'data-key': 'cropWidth'}),
                        this.createCropperField('crop-height', _('imageplus.cropimage.crop.height'), {min: 1, 'data-key': 'cropHeight'}),
                        this.createCropperField('proportion', _('imageplus.cropimage.crop.proportion'), {type: 'text', disabled: true})
                    ])
                ])
            ])
        ]));
    },
    renderCropperSets: function () {
        var container = this.getCropperEl('sizes');
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
    onCropperReady: function () {
        if (this.cropperReload) {
            this.cropperReload = false;
            this.recropCropperSizes();
        } else {
            this.resetCropperSize();
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
        this.getCropperEl('cropper').src = record.baseUrl || Ext.BLANK_IMAGE_URL;
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
            // A size without both values has no proportion
            return Ext.apply({key: key, targetWidth: width, targetHeight: height, proportion: ''}, this.getCropperRatio(width, height));
        } else if (data.proportion) {
            match = String(data.proportion).match(/^([0-9]+)\s*x\s*([0-9]+)$/);
            if (!match) {
                return null;
            }
            // A proportion has an aspect ratio, but no target size
            return Ext.apply(this.getCropperRatio(parseInt(match[1], 10), parseInt(match[2], 10)), {
                key: key,
                targetWidth: NaN,
                targetHeight: NaN
            });
        } else if (data.width || data.height) {
            match = String(data.width || data.height).match(/^([0-9]+)$/);
            if (!match) {
                return null;
            }
            width = data.width ? parseInt(match[1], 10) : NaN;
            height = data.width ? NaN : parseInt(match[1], 10);
        }
        return Ext.apply(this.getCropperRatio(width, height), {
            key: key,
            targetWidth: width || NaN,
            targetHeight: height || NaN
        });
    },
    /**
     * Get the aspect ratio and the proportion of a width and a height, if both are set
     */
    getCropperRatio: function (width, height) {
        if (!width || !height) {
            return {ratio: NaN};
        }
        return {
            ratio: Ext.util.Format.round(width / height, 10),
            proportion: ImagePlus.util.formatProportion(width, height)
        };
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
    /**
     * Set a value of the crop fields, a changed crop size keeps the center of the crop area
     */
    setCropperSize: function (key, value) {
        if (!this.cropper) {
            return;
        }
        var data = this.getCropperCanvasData();
        var cropperData = {rotate: 0, scaleX: 1, scaleY: 1};
        if (key === 'x' || key === 'y') {
            cropperData[key] = value;
        } else if (key === 'cropWidth' || key === 'cropHeight') {
            // With an aspect ratio, the other side of the crop area changes too
            var ratio = this.getCropperSizeData().ratio;
            var width = (key === 'cropWidth') ? value : (ratio ? value * ratio : data.cropWidth);
            var height = (key === 'cropHeight') ? value : (ratio ? value / ratio : data.cropHeight);
            cropperData.x = data.x - Math.floor((width - data.cropWidth) / 2);
            cropperData.y = data.y - Math.floor((height - data.cropHeight) / 2);
            cropperData.width = width;
            cropperData.height = height;
        }
        this.cropper.setData(cropperData);
        this.setCropperState('start');
    },
    setCropperState: function (state, content) {
        if (!this.cropper) {
            return;
        }
        this.cropperState = state;
        // The css class and the text of the state message
        var messages = {
            start: ['red', _('imageplus.cropimage.changes_unsaved')],
            stop: ['red', _('imageplus.cropimage.changes_unsaved')],
            saved: ['green', _('imageplus.cropimage.changes_saved')],
            saving: ['loading', _('imageplus.cropimage.saving')],
            reloading: ['loading', _('imageplus.cropimage.reloading')],
            failure: ['red', content]
        };
        var stateEl = this.getCropperEl('state');
        stateEl.classList.remove('red', 'green', 'loading');
        stateEl.hidden = !messages[state];
        if (messages[state]) {
            stateEl.classList.add(messages[state][0]);
            stateEl.textContent = messages[state][1];
        }
        // Another size can only be selected, if the changes are saved
        var enableAll = state === 'saved' || state === 'ready';
        this.getCropperEl('sizes').querySelectorAll('input').forEach(function (input) {
            input.disabled = !enableAll && input.value !== this.cropperSize;
        }, this);
    },
    setCropperField: function (key, value) {
        var input = this.getCropperEl('property-' + key);
        if (input) {
            input.value = (typeof value === 'undefined' || value === null || (typeof value === 'number' && isNaN(value))) ? '' : value;
        }
    },
    updateCropperData: function () {
        if (!this.cropper) {
            return;
        }
        var data = this.getCropperCanvasData();
        Ext.iterate({
            'original-width': data.originalWidth,
            'original-height': data.originalHeight,
            'target-width': data.targetWidth,
            'target-height': data.targetHeight,
            'crop-x': data.x,
            'crop-y': data.y,
            'crop-width': data.cropWidth,
            'crop-height': data.cropHeight,
            'proportion': ImagePlus.util.formatDimensions(data.proportion)
        }, this.setCropperField, this);
    },
    updateCropperImage: function (key) {
        key = key || this.cropperSize;
        var previewImage = this.getCropperEl('preview-' + key);
        if (previewImage) {
            previewImage.src = this.cropperSizes[key].baseUrl;
        }
    },
    isCropperBusy: function () {
        return this.cropperState === 'saving' || this.cropperState === 'reloading';
    },
    /**
     * Crops of photos with an EXIF orientation, that were created before the orientation was regarded, were set on the
     * image lying on its side. Their crop area doesn't fit the upright image, so the whole image is selected.
     */
    isCropperOrientationChanged: function (data) {
        var imageData = this.cropper.getImageData();
        var width = parseInt(data.originalWidth, 10);
        var height = parseInt(data.originalHeight, 10);
        return !!(width && height && width !== height && width === imageData.naturalHeight && height === imageData.naturalWidth);
    },
    selectCropperSize: function (key) {
        if (this.cropper && key) {
            this.cropperSize = key;
            this.resetCropperSize();
        }
    },
    resetCropperSize: function () {
        if (!this.cropper || this.isCropperBusy()) {
            return;
        }
        var data = this.getCropperSizeData();
        var imageData = this.cropper.getImageData();
        var cropperData = {rotate: 0, scaleX: 1, scaleY: 1};
        this.cropper.setAspectRatio((data.ratio) ? data.ratio : NaN);
        if (data.cropWidth && data.cropHeight && !this.isCropperOrientationChanged(data)) {
            Ext.apply(cropperData, {x: data.x, y: data.y, width: data.cropWidth, height: data.cropHeight});
        } else {
            Ext.apply(cropperData, {width: imageData.naturalWidth, height: imageData.naturalHeight});
        }
        this.cropper.setData(cropperData);
        this.setCropperState('ready');
    },
    updateCropperRecord: function () {
        if (!this.cropper || this.isCropperBusy()) {
            return;
        }
        var data = Ext.apply(Ext.apply({}, this.getCropperSizeData()), this.getCropperCanvasData());
        this.requestCropperCrop(data, function () {
            this.setCropperSizeData(data);
            this.updateCropperImage();
            this.setCropperState('saved');
        });
        this.setCropperState('saving');
    },
    /**
     * Create the crop file of an image crop. The path and the url of the crop file are set in the crop data.
     */
    requestCropperCrop: function (data, callback) {
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
                        callback.call(this);
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
    },
    /**
     * Load the image again, i.e. if it was replaced by a changed image with the same filename, and create the saved
     * image crops again
     */
    reloadCropperImage: function () {
        if (!this.cropper || this.isCropperBusy() || !this.cropperRecord.baseUrl) {
            return;
        }
        Ext.MessageBox.confirm(_('imageplus.cropimage.reload.title'), _('imageplus.cropimage.reload.content'), function (answer) {
            if (answer !== 'yes') {
                return;
            }
            var baseUrl = this.cropperRecord.baseUrl;
            // The timestamp loads the image from the server instead of the browser cache
            var url = baseUrl + ((baseUrl.indexOf('?') === -1) ? '?' : '&') + '_dc=' + Date.now();
            var image = new Image();
            image.onload = function () {
                if (this.cropper) {
                    this.cropperReload = true;
                    this.cropper.replace(url);
                }
            }.bind(this);
            image.onerror = function () {
                this.setCropperState('failure', _('imageplus.cropimage.error_image_not_exists'));
            }.bind(this);
            this.setCropperState('reloading');
            image.src = url;
        }, this);
    },
    /**
     * Create the saved image crops again with the reloaded image
     */
    recropCropperSizes: function () {
        var imageData = this.cropper.getImageData();
        var keys = Object.keys(this.cropperSizes).filter(function (key) {
            var size = this.cropperSizes[key];
            return size.image && size.cropWidth && size.cropHeight;
        }, this);
        var ratioChanged = false;
        var next = function () {
            var key = keys.shift();
            if (!key) {
                this.cropperState = 'ready';
                this.resetCropperSize();
                this.setCropperState('saved');
                if (ratioChanged) {
                    Ext.MessageBox.alert(_('imageplus.cropimage.reload_ratio.title'), _('imageplus.cropimage.reload_ratio.content'));
                }
                return;
            }
            var size = this.cropperSizes[key];
            var data = this.getReloadedCropperSize(size, imageData.naturalWidth, imageData.naturalHeight);
            ratioChanged = ratioChanged || this.isCropperRatioChanged(size, imageData.naturalWidth, imageData.naturalHeight);
            this.requestCropperCrop(data, function () {
                this.cropperSizes[key] = data;
                this.updateCropperImage(key);
                next();
            });
        }.bind(this);
        next();
    },
    isCropperRatioChanged: function (size, width, height) {
        var originalWidth = parseInt(size.originalWidth, 10);
        var originalHeight = parseInt(size.originalHeight, 10);
        return !!(originalWidth && originalHeight && Math.abs(originalWidth / originalHeight - width / height) > 0.01);
    },
    /**
     * Get the crop data of an image crop for a reloaded image. With a changed image size, the position of the crop area
     * is scaled with the image and the crop area keeps its aspect ratio. The crop area has to be inside the image.
     */
    getReloadedCropperSize: function (size, width, height) {
        var originalWidth = parseInt(size.originalWidth, 10) || width;
        var originalHeight = parseInt(size.originalHeight, 10) || height;
        var x = parseInt(size.x, 10) || 0;
        var y = parseInt(size.y, 10) || 0;
        var cropWidth = parseInt(size.cropWidth, 10);
        var cropHeight = parseInt(size.cropHeight, 10);
        var ratio = size.ratio || cropWidth / cropHeight;
        if (originalWidth !== width || originalHeight !== height) {
            var scale = Math.min(width / originalWidth, height / originalHeight);
            x = Math.round(x * width / originalWidth);
            y = Math.round(y * height / originalHeight);
            cropWidth = Math.round(cropWidth * scale);
            cropHeight = Math.round(cropHeight * scale);
        }
        if (cropWidth > width) {
            cropWidth = width;
            cropHeight = Math.round(width / ratio);
        }
        if (cropHeight > height) {
            cropHeight = height;
            cropWidth = Math.round(height * ratio);
        }
        cropWidth = Math.max(1, cropWidth);
        cropHeight = Math.max(1, cropHeight);
        return Ext.apply(Ext.apply({}, size), {
            originalWidth: width,
            originalHeight: height,
            x: Math.max(0, Math.min(x, width - cropWidth)),
            y: Math.max(0, Math.min(y, height - cropHeight)),
            cropWidth: cropWidth,
            cropHeight: cropHeight
        });
    }
});
Ext.reg('imageplus-window-crop-image', ImagePlus.window.CropImage);
