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
$_lang['imageplus.thumbnailHeight'] = 'Miniatűr magassága';
$_lang['imageplus.thumbnailHeight_desc'] = '(Választható, egész szám) A kép miniatűr magassága a sablon változó panelen. Alapértelmezett: 250';
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
$_lang['imageplus.generateUrl'] = 'A kivágott fájl URL-jének használata';
$_lang['imageplus.generateUrl_desc'] = '(Választható) A kivágott fájl URL-jére talán nincs szükség, ha a kimeneti chunkban, azaz egy pthumb kimeneti szűrővel generálod a miniatűr képet.';
$_lang['imageplus.generateUrl_desc_warning'] = 'Ezt az opciót akkor kell aktiválnod, ha nem adsz meg kimeneti egységet a kimeneti beállítások között, vagy ha a [[+url]] helyőrzőt használod a megadott kimeneti egységben. Ellenkező esetben a képet nem vágja le, és az eredeti kép URL-jét adja vissza.';
/** Placeholder descriptions */
$_lang['imageplus.placeholder.url'] = 'A kivágott fájl URL címe';
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
