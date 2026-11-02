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
$_lang['imageplus.minWidth'] = 'Minimální šířka obrázku';
$_lang['imageplus.minWidth_desc'] = '(Volitelné, celé číslo) Minimální šířka vybraného nebo nahraného obrázku v pixelech.';
$_lang['imageplus.minHeight'] = 'Minimální výška obrázku';
$_lang['imageplus.minHeight_desc'] = '(Volitelné, celé číslo) Minimální výška vybraného nebo nahraného obrázku v pixelech.';
$_lang['imageplus.thumbnailHeight'] = 'Výška miniatury';
$_lang['imageplus.thumbnailHeight_desc'] = '(Volitelné, celé číslo) Výška miniatury v panelu Template variable. Výchozí: 250';
$_lang['imageplus.inlinePreview'] = 'Zobrazit všechny obrázky na jednom řádku';
$_lang['imageplus.inlinePreview_desc'] = '(Volitelné, Ano/Ne) Zobrazit výřezy a původní obrázek na jednom řádku, pokud je panel proměnné šablony široký alespoň 700 pixelů. Výchozí: Ano';
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
/** Image crops **/
$_lang['imageplus.sizes'] = 'Výřezy obrázku';
$_lang['imageplus.sizes_desc'] = '(Volitelné, JSON) Objekt kódovaný v JSON pro výřezy obrázku, např. {"desktop": {"name": "Desktop", "size": "1000x500"}, "mobile": {"name": "Mobile", "proportion": "4x3"}}. Každý výřez může mít velikost (size), poměr (proportion), šířku (width) nebo výšku (height). Pokud je tato hodnota prázdná, použije se jeden výřez bez omezení.';
$_lang['imageplus.crop'] = 'Výřez obrázku';
$_lang['imageplus.crop_desc'] = '(Volitelné) Klíč výřezu obrázku použitého pro výstup. Ponechte prázdné pro použití prvního výřezu.';
$_lang['imageplus.placeholder.crop.url'] = 'URL souboru výřezu vytvořeného v manageru';
/** Crop window **/
$_lang['imageplus.cropimage'] = 'Oříznout obrázek';
$_lang['imageplus.cropimage.changes.content'] = 'Existují neuložené změny, chcete pokračovat?';
$_lang['imageplus.cropimage.changes.title'] = 'Upozornění';
$_lang['imageplus.cropimage.changes_saved'] = 'Změny uloženy';
$_lang['imageplus.cropimage.changes_unsaved'] = 'Existují neuložené změny.';
$_lang['imageplus.cropimage.crop'] = 'Oříznout obrázek';
$_lang['imageplus.cropimage.crop.height'] = 'Výška';
$_lang['imageplus.cropimage.crop.proportion'] = 'Poměr';
$_lang['imageplus.cropimage.crop.width'] = 'Šířka';
$_lang['imageplus.cropimage.crop.x'] = 'Vlevo';
$_lang['imageplus.cropimage.crop.y'] = 'Nahoře';
$_lang['imageplus.cropimage.default'] = 'Originál';
$_lang['imageplus.cropimage.error_crop'] = 'Chyba při vytváření souboru výřezu.';
$_lang['imageplus.cropimage.error_cropheight_cropwidth'] = 'Hodnota cropWidth nebo cropHeight je prázdná.';
$_lang['imageplus.cropimage.error_file_not_set'] = 'Nebyl zadán žádný soubor.';
$_lang['imageplus.cropimage.error_image'] = 'Výřez obrázku nelze uložit.';
$_lang['imageplus.cropimage.error_image_not_exists'] = 'Obrázek neexistuje.';
$_lang['imageplus.cropimage.error_image_not_valid'] = 'Obrázek není platný soubor.';
$_lang['imageplus.cropimage.error_min_width'] = 'Obrázek je široký [[+width]] pixelů, ale musí být široký alespoň [[+min_width]] pixelů.';
$_lang['imageplus.cropimage.error_min_height'] = 'Obrázek je vysoký [[+height]] pixelů, ale musí být vysoký alespoň [[+min_height]] pixelů.';
$_lang['imageplus.cropimage.error_memory'] = 'Obrázek ([[+width]] x [[+height]] pixelů) je příliš velký na oříznutí. Oříznutí potřebuje asi [[+memory]] paměti, ale PHP memory_limit je [[+limit]] a nelze ho zvýšit. Nahrajte prosím menší obrázek nebo požádejte správce serveru o zvýšení memory_limit.';
$_lang['imageplus.cropimage.original'] = 'Původní obrázek';
$_lang['imageplus.cropimage.original.download'] = 'Stáhnout původní obrázek';
$_lang['imageplus.cropimage.original.height'] = 'Výška';
$_lang['imageplus.cropimage.original.width'] = 'Šířka';
$_lang['imageplus.cropimage.reload'] = 'Znovu načíst obrázek';
$_lang['imageplus.cropimage.reload.content'] = 'Obrázek se znovu načte a všechny uložené výřezy se z něj vytvoří znovu, např. pokud byl obrázek nahrazen změněným obrázkem se stejným názvem souboru. Neuložené změny budou zahozeny. Chcete pokračovat?';
$_lang['imageplus.cropimage.reload.title'] = 'Znovu načíst obrázek';
$_lang['imageplus.cropimage.reload_ratio.content'] = 'Poměr stran obrázku se změnil. Zkontrolujte prosím výřezy obrázku a v případě potřeby je znovu uložte.';
$_lang['imageplus.cropimage.reload_ratio.title'] = 'Upozornění';
$_lang['imageplus.cropimage.reloading'] = 'Výřezy obrázku se vytvářejí znovu …';
$_lang['imageplus.cropimage.reset'] = 'Zrušit změny';
$_lang['imageplus.cropimage.save'] = 'Uložit výřez';
$_lang['imageplus.cropimage.saving'] = 'Ukládání výřezu obrázku …';
$_lang['imageplus.cropimage.select_file'] = 'Vybrat soubor …';
$_lang['imageplus.cropimage.sets'] = 'Vyberte výřez obrázku';
$_lang['imageplus.cropimage.success_image'] = 'Výřez obrázku uložen';
$_lang['imageplus.cropimage.target'] = 'Cílový obrázek';
$_lang['imageplus.cropimage.target.height'] = 'Výška';
$_lang['imageplus.cropimage.target.width'] = 'Šířka';
$_lang['imageplus.cropimage.unset.content'] = 'Některé výřezy obrázku nejsou vytvořeny, chcete pokračovat?';
$_lang['imageplus.cropimage.unset.title'] = 'Upozornění';
