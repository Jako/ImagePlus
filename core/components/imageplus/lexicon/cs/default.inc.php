<?php
/**
 * Default Lexicon Entries for Image+
 *
 * @package imageplus
 * @subpackage lexicon
 */
$_lang['imageplus'] = 'Image+';
$_lang['imageplus.editor_title'] = 'Image+ Editor';
$_lang['imageplus.alt_text'] = 'Alt text';
$_lang['imageplus.caption'] = 'Titulek';
$_lang['imageplus.credits'] = 'Kredity';
/** Input options render **/
$_lang['imageplus.input_section'] = 'Image+ Options';
$_lang['imageplus.input_section_desc'] = 'Následující možnosti mohou být přepsány kontextovým/systémovým nastavením. Příslušné klíče v kontextovém/systémovém nastavení naleznete v <a href="https://jako.github.io/ImagePlus/usage/#contextsystem-settings" target="_blank">dokumentaci</a>.';
$_lang['imageplus.thumbnailHeight'] = 'Výška miniatury';
$_lang['imageplus.thumbnailHeight_desc'] = '(Volitelné, celé číslo) Výška miniatury v panelu Template variable. Výchozí: 250';
$_lang['imageplus.allowAltTag'] = 'Zobrazit pole "alt" tag';
$_lang['imageplus.allowAltTag_desc'] = 'Povolit uživatelům zadat title/alt.';
$_lang['imageplus.allowCaption'] = 'Zobrazit pole titulků';
$_lang['imageplus.allowCaption_desc'] = 'Umožňuje uživateli zadat popisek k obrázku.';
$_lang['imageplus.allowCredits'] = 'Zobrazit pole Kredity';
$_lang['imageplus.allowCredits_desc'] = 'Umožnit uživateli zadat kredit obrázku.';
/** Output options render **/
$_lang['imageplus.phpThumbParams'] = 'Další phpThumb parametry';
$_lang['imageplus.phpThumbParams_desc'] = '(Volitelné) Parametry phpThumb pro zástupný symbol options ve výstupním chunku, například pro výstupní filtr pthumb. Dokumentaci najdete <a href="https://raw.githubusercontent.com/JamesHeinrich/phpThumb/master/docs/phpthumb.readme.txt" target="_blank">zde</a>.';
$_lang['imageplus.outputChunk'] = 'Výstupní chunk';
$_lang['imageplus.outputChunk_desc'] = 'Vyberte chunk pro výstup TV. Nechte prázdé pro výstup čisté URL.';
$_lang['imageplus.generateUrl'] = 'Použít URL souboru výřezu';
$_lang['imageplus.generateUrl_desc'] = '(Volitelné) URL souboru výřezu možná není třeba, pokud miniaturu generujete ve výstupním chunku (například pomocí výstupního filtru pthumb).';
$_lang['imageplus.generateUrl_desc_warning'] = 'Tuto možnost musíte aktivovat, pokud ve výstupních možnostech nezadáte výstupní oddíl nebo pokud v zadaném výstupním oddílu použijete zástupný znak [[+url]]. Jinak se obrázek neořízne a vrátí se URL původního obrázku.';
/** Placeholder descriptions */
$_lang['imageplus.placeholder.url'] = 'URL souboru výřezu';
$_lang['imageplus.placeholder.alt'] = 'Alt text';
$_lang['imageplus.placeholder.width'] = 'Šířka miniatury (ignorováno pokud je 0)';
$_lang['imageplus.placeholder.height'] = 'Výška miniatury (ignorováno pokud je 0)';
$_lang['imageplus.placeholder.source.src'] = 'Cesta ke zdrojovému obrázku';
$_lang['imageplus.placeholder.source.width'] = 'Šířka zdrojového obrázku';
$_lang['imageplus.placeholder.source.height'] = 'Výška zdrojového obrázku';
$_lang['imageplus.placeholder.crop.width'] = 'Oříznout šířku zdrojového obrázku';
$_lang['imageplus.placeholder.crop.height'] = 'Oříznout výšku zdrojového obrázku';
$_lang['imageplus.placeholder.crop.x'] = 'Oříznout podle X osy zdrojového obrázku';
$_lang['imageplus.placeholder.crop.y'] = 'Oříznout podle Y osy zdrojového obrázku';
$_lang['imageplus.placeholder.options'] = 'phpThumb řetězec pro generování miniatur';
$_lang['imageplus.placeholder.crop.options'] = 'phpThumb řetězec ořezu pro generování miniatur';
$_lang['imageplus.error.image_too_small.title'] = 'Obrázek je příliš malý';
$_lang['imageplus.error.image_too_small.msg'] = 'Vybraný obrázek je moc malý pro použití. Prosím vyberte jiný obrázek.';
$_lang['imageplus.error.image_not_found.title'] = 'Obrázek nebyl nalezen';
$_lang['imageplus.error.image_not_found.msg'] = 'Obrázek nebyl nalezen a nelze ho oříznout. Prosím vyberte jiný obrázek.';
