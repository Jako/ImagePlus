<?php
/**
 * Default Lexicon Entries for Image+
 *
 * @package imageplus
 * @subpackage lexicon
 */
$_lang['imageplus'] = 'Image+';
$_lang['imageplus.editor_title'] = 'Image+ Editor';
$_lang['imageplus.alt_text'] = 'Alternatieve tekst';
$_lang['imageplus.caption'] = 'Titel';
$_lang['imageplus.credits'] = 'Credits';
/** Input options render **/
$_lang['imageplus.input_section'] = 'Image+ Opties';
$_lang['imageplus.input_section_desc'] = 'De volgende opties kunnen met een context of system setting worden overschreven. Bekijk de <a href="https://jako.github.io/ImagePlus/usage/#contextsystem-settings" target="_blank">documentatie</a> voor de juiste keys.';
$_lang['imageplus.minWidth'] = 'Minimale afbeeldingsbreedte';
$_lang['imageplus.minWidth_desc'] = '(Optioneel, geheel getal) De minimale breedte van de geselecteerde of geüploade afbeelding in pixels.';
$_lang['imageplus.minHeight'] = 'Minimale afbeeldingshoogte';
$_lang['imageplus.minHeight_desc'] = '(Optioneel, geheel getal) De minimale hoogte van de geselecteerde of geüploade afbeelding in pixels.';
$_lang['imageplus.thumbnailHeight'] = 'Thumbnail hoogte';
$_lang['imageplus.thumbnailHeight_desc'] = '(Optioneel, geheel getal) De hoogte van de thumbnail in het Template Variabele paneel. Standaard: 250';
$_lang['imageplus.inlinePreview'] = 'Alle afbeeldingen op één regel tonen';
$_lang['imageplus.inlinePreview_desc'] = '(Optioneel, Ja/Nee) Toont de uitsneden en de originele afbeelding ook op één regel, als het Template Variabele paneel minstens 700 pixels breed is. Standaard: Ja';
$_lang['imageplus.format'] = 'Afbeeldingsformaat';
$_lang['imageplus.format_desc'] = '(Optioneel) Het afbeeldingsformaat van de uitsnedebestanden. Standaard: WebP';
$_lang['imageplus.format_image'] = 'Formaat van de afbeelding';
$_lang['imageplus.allowAltTag'] = 'Alt-Tag veld weergeven';
$_lang['imageplus.allowAltTag_desc'] = 'Laat de gebruiker een titel/alt-tag voor de afbeelding invoeren.';
$_lang['imageplus.allowCaption'] = 'Toon bijschriftveld';
$_lang['imageplus.allowCaption_desc'] = 'Gebruiker toestaan aan een bijschrift voor de afbeelding in te voeren.';
$_lang['imageplus.allowCredits'] = 'Credits-veld weergeven';
$_lang['imageplus.allowCredits_desc'] = 'Mogelijk maken dat de gebruiker een naamsvermelding (credits) voor de afbeelding kan invoeren.';
/** Output options render **/
$_lang['imageplus.phpThumbParams'] = 'Extra phpThumb opties';
$_lang['imageplus.phpThumbParams_desc'] = '(Optioneel) phpThumb opties voor de options placeholder van de output chunk, bijvoorbeeld voor een pthumb output filter. Documentatie is <a href="https://raw.githubusercontent.com/JamesHeinrich/phpThumb/master/docs/phpthumb.readme.txt" target="_blank">hier</a> te vinden.';
$_lang['imageplus.outputChunk'] = 'Output Chunk';
$_lang['imageplus.outputChunk_desc'] = 'Selecteer een chunk voor de TV output. Laat leeg om alleen een link naar de afbeelding terug te krijgen.';
/** Placeholder descriptions */
$_lang['imageplus.placeholder.url'] = 'URL van het uitsnedebestand';
$_lang['imageplus.placeholder.alt'] = 'Alternatieve tekst';
$_lang['imageplus.placeholder.width'] = 'Breedte van de thumbnail (genegeerd indien 0)';
$_lang['imageplus.placeholder.height'] = 'Hoogte van de thumbnail (genegeerd indien 0)';
$_lang['imageplus.placeholder.source.src'] = 'Pad naar de bronafbeelding';
$_lang['imageplus.placeholder.source.width'] = 'Breedte van de bronafbeelding';
$_lang['imageplus.placeholder.source.height'] = 'Hoogte van de bronafbeelding';
$_lang['imageplus.placeholder.crop.width'] = 'Cropbreedte van de bronafbeelding';
$_lang['imageplus.placeholder.crop.height'] = 'Crophoogte van de bronafbeelding';
$_lang['imageplus.placeholder.crop.x'] = 'Crop X positie van de bronafbeelding';
$_lang['imageplus.placeholder.crop.y'] = 'Crop Y positie van de bronafbeelding';
$_lang['imageplus.placeholder.options'] = 'phpThumb waarde om de thumbnail te genereren';
$_lang['imageplus.placeholder.crop.options'] = 'phpThumb crop waarde om de thumbnail te genereren';
$_lang['imageplus.error.image_too_small.title'] = 'Afbeelding is te klein';
$_lang['imageplus.error.image_too_small.msg'] = 'De geselecteerde afbeelding is te klein om hier gebruikt te worden. Selecteer een andere afbeelding.';
$_lang['imageplus.error.image_not_found.title'] = 'Afbeelding niet gevonden';
$_lang['imageplus.error.image_not_found.msg'] = 'De afbeelding kan niet worden bijgesneden omdat hij niet gevonden kon worden. Selecteer een andere afbeelding.';
/** Image crops **/
$_lang['imageplus.sizes'] = 'Afbeeldingsuitsneden';
$_lang['imageplus.sizes_desc'] = '(Optioneel, JSON) Een JSON-gecodeerd object voor de afbeeldingsuitsneden, bijv. {"desktop": {"name": "Desktop", "size": "1000x500"}, "mobile": {"name": "Mobiel", "proportion": "4x3"}}. Elke uitsnede kan een grootte (size), een verhouding (proportion), een breedte (width) of een hoogte (height) hebben. Als deze waarde leeg is, wordt één uitsnede zonder beperkingen gebruikt.';
$_lang['imageplus.crop'] = 'Afbeeldingsuitsnede';
$_lang['imageplus.crop_desc'] = '(Optioneel) De sleutel van de afbeeldingsuitsnede voor de uitvoer. Laat leeg om de eerste uitsnede te gebruiken.';
$_lang['imageplus.placeholder.crop.url'] = 'URL van het in de manager aangemaakte uitsnedebestand';
/** Crop window **/
$_lang['imageplus.cropimage'] = 'Afbeelding bijsnijden';
$_lang['imageplus.cropimage.changes.content'] = 'Er zijn niet-opgeslagen wijzigingen, wil je doorgaan?';
$_lang['imageplus.cropimage.changes.title'] = 'Waarschuwing';
$_lang['imageplus.cropimage.changes_saved'] = 'Wijzigingen opgeslagen';
$_lang['imageplus.cropimage.changes_unsaved'] = 'Er zijn niet geredde veranderingen.';
$_lang['imageplus.cropimage.crop'] = 'Afbeelding bijsnijden';
$_lang['imageplus.cropimage.crop.height'] = 'Hoogte';
$_lang['imageplus.cropimage.crop.proportion'] = 'Verhouding';
$_lang['imageplus.cropimage.crop.width'] = 'Breedte';
$_lang['imageplus.cropimage.crop.x'] = 'Links';
$_lang['imageplus.cropimage.crop.y'] = 'Top';
$_lang['imageplus.cropimage.default'] = 'Origineel';
$_lang['imageplus.cropimage.error_crop'] = 'Fout bij het maken van bijsnijdbestand.';
$_lang['imageplus.cropimage.error_cropheight_cropwidth'] = 'De waarde in cropWidth of cropHeight is leeg.';
$_lang['imageplus.cropimage.error_file_not_set'] = 'Geen bestand opgegeven.';
$_lang['imageplus.cropimage.error_format'] = 'Het afbeeldingsformaat [[+format]] wordt niet ondersteund door de GD-bibliotheek van de server.';
$_lang['imageplus.cropimage.error_image'] = 'De bijsnede van de afbeelding kon niet worden opgeslagen.';
$_lang['imageplus.cropimage.error_image_not_exists'] = 'De afbeelding bestaat niet.';
$_lang['imageplus.cropimage.error_image_not_valid'] = 'De afbeelding is geen geldig bestand.';
$_lang['imageplus.cropimage.error_min_width'] = 'De afbeelding is [[+width]] pixels breed, maar moet minstens [[+min_width]] pixels breed zijn.';
$_lang['imageplus.cropimage.error_min_height'] = 'De afbeelding is [[+height]] pixels hoog, maar moet minstens [[+min_height]] pixels hoog zijn.';
$_lang['imageplus.cropimage.error_memory'] = 'De afbeelding ([[+width]] x [[+height]] pixels) is te groot om bij te snijden. Het bijsnijden vereist ongeveer [[+memory]] geheugen, maar de PHP memory_limit is [[+limit]] en kan niet worden verhoogd. Upload een kleinere afbeelding of vraag de serverbeheerder om de memory_limit te verhogen.';
$_lang['imageplus.cropimage.original'] = 'Oorspronkelijke afbeelding';
$_lang['imageplus.cropimage.original.download'] = 'Oorspronkelijke afbeelding downloaden';
$_lang['imageplus.cropimage.original.height'] = 'Hoogte';
$_lang['imageplus.cropimage.original.width'] = 'Breedte';
$_lang['imageplus.cropimage.reload'] = 'Afbeelding opnieuw laden';
$_lang['imageplus.cropimage.reload.content'] = 'De afbeelding wordt opnieuw geladen en alle opgeslagen uitsneden worden daaruit opnieuw aangemaakt, bijvoorbeeld als de afbeelding is vervangen door een gewijzigde afbeelding met dezelfde bestandsnaam. Niet opgeslagen wijzigingen gaan verloren. Wil je doorgaan?';
$_lang['imageplus.cropimage.reload.title'] = 'Afbeelding opnieuw laden';
$_lang['imageplus.cropimage.reload_ratio.content'] = 'De beeldverhouding van de afbeelding is gewijzigd. Controleer de uitsneden en sla ze indien nodig opnieuw op.';
$_lang['imageplus.cropimage.reload_ratio.title'] = 'Waarschuwing';
$_lang['imageplus.cropimage.reloading'] = 'De uitsneden worden opnieuw aangemaakt …';
$_lang['imageplus.cropimage.reset'] = 'Wijzigingen terugzetten';
$_lang['imageplus.cropimage.save'] = 'Uitsnede opslaan';
$_lang['imageplus.cropimage.saving'] = 'De uitsnede van de afbeelding opslaan …';
$_lang['imageplus.cropimage.sets'] = 'Selecteer bijsnijden afbeelding';
$_lang['imageplus.cropimage.success_image'] = 'Afbeelding bijsnijden opgeslagen';
$_lang['imageplus.cropimage.target'] = 'Afbeelding doelwit';
$_lang['imageplus.cropimage.target.height'] = 'Hoogte';
$_lang['imageplus.cropimage.target.width'] = 'Breedte';
$_lang['imageplus.cropimage.unset.content'] = 'Sommige beeldgewassen worden niet gemaakt, wil je doorgaan?';
$_lang['imageplus.cropimage.unset.title'] = 'Waarschuwing';
$_lang['imageplus.cropimage.select_file'] = 'Selecteer bestand …';
