**Image+* can be used for various purposes. On this page you will find some
*examples of how it can be used:

### Collections

You can display the *Image+* thumbnail in a column of the Collections grid by
setting the column's renderer to *ImagePlus.MIGX_Renderer*.

### MIGX

If you want to use *Image+* in MIGX, you can configure all *Image+* TV
properties with a JSON string in the *Configs* textarea in the MIGX formtabs
field. The following properties are possible:

```json
{
    "sizes":"",
    "minWidth":"",
    "minHeight":"",
    "thumbnailHeight":"",
    "inlinePreview":"",
    "allowAltTag":"",
    "allowCaption":"",
    "allowCredits":""
}
```

You also need to change the *Input TV Type* to `imageplus`.

You can display the thumbnail in the grid column by setting the renderer to
*ImagePlus.MIGX_Renderer*. In earlier versions of MIGX, you need to edit a MIGX
configuration to do this.

To use this *Image+* MIGX field in the frontend, call the *ImagePlus Snippet*
where the value parameter is the name of the MIGX field:

```html
[[ImagePlus? 
&value=`[[+migxImagePlusField]]`
]]
```

This will give you the prepared URL for the cropped image instead of the raw
JSON object.

### getResources/pdoResources

In order for the TV to be parsed using the *getResources/pdoResources snippet*,
you need to add the following lines to your *getResources/pdoResources* call
Snippet* call:

```html
&includeTVs=`name_of_your_tv`
&processTVs=`name_of_your_tv`
```

In the template chunk of the *getResources/pdoResources snippet* call, you can
use the placeholder `[[+tv.name_your_TV]]` if the TV's Output Type is set to
`Image+`. Without any further changes, the placeholder will contain the URL of
the cropped image.

### Using the ImagePlus snippet in the getResources/pdoResources template chunk

In your template chunk for calling *getResources/pdoResources Snippet*, you need
to add a parameter so that the *ImagePlus Snippet* call knows the origin ID from
which to retrieve data:

```html
&docid=`[[+id]]`
```

Here is an example of a call and configuration, where *image* is your Image+ TV:

**Snippet Call**
```
<div class="blog-articles">
[[!pdoPage?
    &element=`pdoResources`
    &tpl=`tplBlogPost`
    &limit=`11`
    &includeContent=`1`
    &showHidden=`0`
    &hideContainers=`1`
]]
</div>
<div class="blog-paging">
  [[!+page.nav]]
</div>
```

**Chunk tplBlogPost**

```html
<article class="post">
    <header class="post-header">
        <h3 class="post-title mt0 mb1"><a href="[[~[[+id]]]]">[[+longtitle:default=`[[+pagetitle]]`]]</a></h3>

    [[ImagePlus? 
        &tvname=`image` 
        &type=`tpl` 
        &docid=`[[+id]]`
        &tpl=`tplBlogIntroImg`
    ]] 

    </header>
    <section class="post-excerpt">
         <p>[[+content:striptags:ellipsis=`255`:typography]]
         <a class="read-more" href="[[~[[+id]]]]">read more »</a></p>
    </section>
    <footer class="post-meta">
        <span class="post-author">[[+createdby:userinfo=`fullname`]]</span>
        <time class="post-date" datetime="[[+publishedon:date=`%B %e, %Y`]]">[[+publishedon:date=`%B %e, %Y`]]</time>
    </footer>
</article>
```

**Chunk tplBlogIntroImg**

```html
<div class="feature" style="margin-bottom:1rem">
    <a href="[[+caption]]"><img src="[[+source.src:pthumb=`w=320`]]" alt="[[+alt]]" /></a>
</div>
```

### Responsive images

If you want to display responsive images with and without the crop, you can use
the *ImagePlus Snippet*.

**Snippet Call**
```
[[ImagePlus? 
&tvname=`yourtvname` 
&type=`tpl` 
&options=`&w=320`
&tpl=`tplResponsiveImage`
&pagetitle=`[[*pagetitle]]`
]]
```
    
**Chunk tplResponsiveImage**
```
<picture>
    <source media="(min-width: 36em)"
            srcset="[[+source.src:pthumb=`w=1024`]] 1024w,
                [[+source.src:pthumb=`w=640`]] 640w,
                [[+source.src:pthumb=`w=320`]] 320w"
            sizes="33.3vw"/>
    <source srcset="[[+source.src:pthumb=`[[+crop.options]]&w=640`]] 2x,
                [[+source.src:pthumb=`[[+crop.options]]&w=320`]] 1x"/>
    <img src="[[+url]]" alt="[[+alt:default=`[[+pagetitle]]`]]"/>
</picture>
```
