<?php
/**
 * Default Lexicon Entries for Image+
 *
 * @package imageplus
 * @subpackage lexicon
 */
$_lang['imageplus'] = 'Image+';
$_lang['imageplus.editor_title'] = 'Image+ Szerkesztő';
$_lang['imageplus.alt_text'] = 'Alt text';
$_lang['imageplus.caption'] = 'Felirat';
$_lang['imageplus.credits'] = 'Credits';
/** Input options render **/
$_lang['imageplus.input_section'] = 'Image+ Options';
$_lang['imageplus.input_section_desc'] = 'A következő beállítások felülírhatók a kontextus/rendszer beállításaival. Kérjük, olvassa el a <a href="https://jako.github.io/ImagePlus/usage/#contextsystem-settings" target="_blank">dokumentációt</a> a megfelelő kulcsokért a kontextus/rendszerbeállításokban.';
$_lang['imageplus.minWidth'] = 'Minimális képszélesség';
$_lang['imageplus.minWidth_desc'] = '(Választható, egész szám) A kiválasztott vagy feltöltött kép minimális szélessége pixelben.';
$_lang['imageplus.minHeight'] = 'Minimális képmagasság';
$_lang['imageplus.minHeight_desc'] = '(Választható, egész szám) A kiválasztott vagy feltöltött kép minimális magassága pixelben.';
$_lang['imageplus.thumbnailHeight'] = 'Miniatűr magassága';
$_lang['imageplus.thumbnailHeight_desc'] = '(Választható, egész szám) A kép miniatűr magassága a sablon változó panelen. Alapértelmezett: 250';
$_lang['imageplus.inlinePreview'] = 'Minden kép megjelenítése egy sorban';
$_lang['imageplus.inlinePreview_desc'] = '(Választható, Igen/Nem) A kivágások és az eredeti kép megjelenítése egy sorban, ha a sablonváltozó panel legalább 700 pixel széles. Alapértelmezett: Igen';
$_lang['imageplus.format'] = 'Képformátum';
$_lang['imageplus.format_desc'] = '(Opcionális) A képkivágás-fájlok képformátuma. Alapértelmezett: WebP';
$_lang['imageplus.format_image'] = 'A kép formátuma';
$_lang['imageplus.uploadPath'] = 'Feltöltési útvonal';
$_lang['imageplus.uploadPath_desc'] = '(Opcionális) A feltöltött képek útvonala a médiaforrásban. Felülírja az imageplus.upload_path rendszerbeállítást. Elérhető helyőrzők: [[+year]], [[+month]], [[+day]], [[+user]], [[+username]], [[+resource_id]], [[+tv_id]].';
$_lang['imageplus.allowAltTag'] = 'Alt tag megengedése';
$_lang['imageplus.allowAltTag_desc'] = 'Lehetővé teszi a felhasználó számára a kép címének/alt-tagjének megadását.';
$_lang['imageplus.allowCaption'] = 'Felirat mező megjelenítése';
$_lang['imageplus.allowCaption_desc'] = 'Lehetővé teszi a felhasználó számára a kép feliratának megadását.';
$_lang['imageplus.allowCredits'] = 'Mutasd a kreditek mezőt';
$_lang['imageplus.allowCredits_desc'] = 'Lehetővé teszi a felhasználó számára, hogy megadja a kép kreditpontját.';
/** Output options render **/
$_lang['imageplus.phpThumbParams'] = 'További phpThumb paraméterek';
$_lang['imageplus.phpThumbParams_desc'] = '(Választható) phpThumb paraméterek a kimeneti chunk options helyőrzőjéhez, pl. egy pthumb kimeneti szűrőhöz. A dokumentáció megtalálható <a href="https://raw.githubusercontent.com/JamesHeinrich/phpThumb/master/docs/phpthumb.readme.txt" target="_blank">itt</a>.';
$_lang['imageplus.outputChunk'] = 'Kimeneti chunk';
$_lang['imageplus.outputChunk_desc'] = 'Válasszon ki egy darabot a tv-kimenethez. Hagyja üresen a nyers url kimenethez.';
/** Placeholder descriptions */
$_lang['imageplus.placeholder.url'] = 'A kivágott fájl URL-je';
$_lang['imageplus.placeholder.alt'] = 'Alt text';
$_lang['imageplus.placeholder.width'] = 'A miniatűr kép szélessége (0 esetén figyelmen kívül hagyva)';
$_lang['imageplus.placeholder.height'] = 'A miniatűr kép magassága (0 esetén figyelmen kívül hagyva)';
$_lang['imageplus.placeholder.source.src'] = 'A forráskép elérési útvonala';
$_lang['imageplus.placeholder.source.width'] = 'A forráskép szélessége';
$_lang['imageplus.placeholder.source.height'] = 'A forráskép magassága';
$_lang['imageplus.placeholder.crop.width'] = 'A forráskép vágási szélessége';
$_lang['imageplus.placeholder.crop.height'] = 'A forráskép vágási magassága';
$_lang['imageplus.placeholder.crop.x'] = 'A forráskép x pozíciójának kivágása';
$_lang['imageplus.placeholder.crop.y'] = 'A forráskép y pozíciójának vágása';
$_lang['imageplus.placeholder.options'] = 'phpThumb opció string a miniatűr kép létrehozásához';
$_lang['imageplus.placeholder.crop.options'] = 'phpThumb crop opció string a miniatűr kép létrehozásához';
$_lang['imageplus.error.image_too_small.title'] = 'Túl kicsi kép';
$_lang['imageplus.error.image_too_small.msg'] = 'A kiválasztott kép túl kicsi ahhoz, hogy itt felhasználható legyen. Kérjük, válasszon másik képet.';
$_lang['imageplus.error.image_not_found.title'] = 'Kép nem található';
$_lang['imageplus.error.image_not_found.msg'] = 'A képet nem találtuk, és nem lehet levágni. Kérjük, válasszon másik képet.';
/** Image crops **/
$_lang['imageplus.sizes'] = 'Képkivágások';
$_lang['imageplus.sizes_desc'] = '(Választható, JSON) JSON-kódolt objektum a képkivágásokhoz, pl. {"desktop": {"name": "Desktop", "size": "1000x500"}, "mobile": {"name": "Mobile", "proportion": "4x3"}}. Minden kivágásnak lehet mérete (size), aránya (proportion), szélessége (width) vagy magassága (height). Ha ez az érték üres, egy korlátozás nélküli képkivágás kerül felhasználásra.';
$_lang['imageplus.crop'] = 'Képkivágás';
$_lang['imageplus.crop_desc'] = '(Választható) A kimenethez használt képkivágás kulcsa. Hagyd üresen az első képkivágás használatához.';
$_lang['imageplus.placeholder.crop.url'] = 'A managerben létrehozott kivágott fájl URL-je';
/** Crop window **/
$_lang['imageplus.cropimage'] = 'Kép kivágása';
$_lang['imageplus.cropimage.changes.content'] = 'Nem mentett változások vannak, folytatod?';
$_lang['imageplus.cropimage.changes.title'] = 'Figyelmeztetés';
$_lang['imageplus.cropimage.changes_saved'] = 'Változások mentve';
$_lang['imageplus.cropimage.changes_unsaved'] = 'Nem mentett változások vannak.';
$_lang['imageplus.cropimage.crop'] = 'Kép kivágása';
$_lang['imageplus.cropimage.crop.height'] = 'Magasság';
$_lang['imageplus.cropimage.crop.proportion'] = 'Arány';
$_lang['imageplus.cropimage.crop.width'] = 'Szélesség';
$_lang['imageplus.cropimage.crop.x'] = 'Balra';
$_lang['imageplus.cropimage.crop.y'] = 'Fent';
$_lang['imageplus.cropimage.default'] = 'Eredeti';
$_lang['imageplus.cropimage.error_crop'] = 'Hiba a kivágott fájl létrehozásakor.';
$_lang['imageplus.cropimage.error_cropheight_cropwidth'] = 'A cropWidth vagy cropHeight értéke üres.';
$_lang['imageplus.cropimage.error_file_not_set'] = 'Nincs megadva fájl.';
$_lang['imageplus.cropimage.error_format'] = 'A(z) [[+format]] képformátumot nem támogatja a szerver GD könyvtára.';
$_lang['imageplus.cropimage.error_image'] = 'A képkivágást nem sikerült menteni.';
$_lang['imageplus.cropimage.error_image_not_exists'] = 'A kép nem létezik.';
$_lang['imageplus.cropimage.error_image_not_valid'] = 'A kép nem érvényes fájl.';
$_lang['imageplus.cropimage.error_min_width'] = 'A kép [[+width]] pixel széles, de legalább [[+min_width]] pixel szélesnek kell lennie.';
$_lang['imageplus.cropimage.error_min_height'] = 'A kép [[+height]] pixel magas, de legalább [[+min_height]] pixel magasnak kell lennie.';
$_lang['imageplus.cropimage.error_memory'] = 'A kép ([[+width]] x [[+height]] pixel) túl nagy a kivágáshoz. A kivágáshoz körülbelül [[+memory]] memória szükséges, de a PHP memory_limit értéke [[+limit]], és nem növelhető. Tölts fel kisebb képet, vagy kérd meg a szerver rendszergazdáját a memory_limit növelésére.';
$_lang['imageplus.cropimage.original'] = 'Eredeti kép';
$_lang['imageplus.cropimage.original.download'] = 'Eredeti kép letöltése';
$_lang['imageplus.cropimage.original.height'] = 'Magasság';
$_lang['imageplus.cropimage.original.width'] = 'Szélesség';
$_lang['imageplus.cropimage.reload'] = 'Kép újratöltése';
$_lang['imageplus.cropimage.reload.content'] = 'A kép újra betöltődik, és minden mentett képkivágás újra létrejön belőle, pl. ha a képet egy azonos fájlnevű, módosított képre cserélték. A nem mentett változások elvesznek. Folytatod?';
$_lang['imageplus.cropimage.reload.title'] = 'Kép újratöltése';
$_lang['imageplus.cropimage.reload_ratio.content'] = 'A kép oldalaránya megváltozott. Ellenőrizd a képkivágásokat, és szükség esetén mentsd őket újra.';
$_lang['imageplus.cropimage.reload_ratio.title'] = 'Figyelmeztetés';
$_lang['imageplus.cropimage.reloading'] = 'A képkivágások újra létrehozása …';
$_lang['imageplus.cropimage.reset'] = 'Változások elvetése';
$_lang['imageplus.cropimage.save'] = 'Kivágás mentése';
$_lang['imageplus.cropimage.saving'] = 'A képkivágás mentése …';
$_lang['imageplus.cropimage.select_file'] = 'Fájl kiválasztása …';
$_lang['imageplus.cropimage.sets'] = 'Képkivágás kiválasztása';
$_lang['imageplus.cropimage.success_image'] = 'Képkivágás mentve';
$_lang['imageplus.cropimage.target'] = 'Célkép';
$_lang['imageplus.cropimage.target.height'] = 'Magasság';
$_lang['imageplus.cropimage.target.width'] = 'Szélesség';
$_lang['imageplus.cropimage.unset.content'] = 'Néhány képkivágás nincs létrehozva, folytatod?';
$_lang['imageplus.cropimage.unset.title'] = 'Figyelmeztetés';
