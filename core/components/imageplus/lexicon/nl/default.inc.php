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
$_lang['imageplus.selectConfig'] = 'Voorgedefinieerde doel maten/aspect ratios';
$_lang['imageplus.selectConfig_desc'] = 'Selecteer een vooraf gedefinieerde doel grootte/hoogte-breedteverhouding. Deze kunnen aangemaakt worden in de systeeminstellingen.';
$_lang['imageplus.selectConfigForce'] = 'Forceer vooraf gedefinieerde doel maten/hoogte-breedteverhoudingen';
$_lang['imageplus.selectConfigForce_desc'] = 'Forceer een vooraf gedefinieerde uitsnede grootte/hoogte-breedteverhouding. Deze kunnen aangemaakt worden in de systeeminstellingen.';
$_lang['imageplus.targetwidth'] = 'Doel (minimale) breedte';
$_lang['imageplus.targetwidth_desc'] = '(Optioneel, geheel getal) De breedte van de uiteindelijke afbeelding.';
$_lang['imageplus.targetheight'] = 'Doel (minimale) hoogte';
$_lang['imageplus.targetheight_desc'] = '(Optioneel, geheel getal) De hoogte van de uiteindelijke afbeelding.';
$_lang['imageplus.targetRatio'] = 'Hoogte/breedteverhouding voor het doelbestand';
$_lang['imageplus.targetRatio_desc'] = '(Optioneel, drijvend) De hoogte/breedteverhouding van de afbeelding. Als de hoogte en breedte voor de output zijn ingesteld wordt deze waarde genegeerd.';
$_lang['imageplus.thumbnailWidth'] = 'Thumbnail breedte';
$_lang['imageplus.thumbnailWidth_desc'] = '(Optioneel, geheel getal) De breedte van de thumbnail in het Template Variabele paneel.';
$_lang['imageplus.allowAltTag'] = 'Alt-Tag veld weergeven';
$_lang['imageplus.allowAltTag_desc'] = 'Laat de gebruiker een titel/alt-tag voor de afbeelding invoeren.';
$_lang['imageplus.allowCaption'] = 'Toon bijschriftveld';
$_lang['imageplus.allowCaption_desc'] = 'Gebruiker toestaan aan een bijschrift voor de afbeelding in te voeren.';
$_lang['imageplus.allowCredits'] = 'Credits-veld weergeven';
$_lang['imageplus.allowCredits_desc'] = 'Mogelijk maken dat de gebruiker een naamsvermelding (credits) voor de afbeelding kan invoeren.';
/** Output options render **/
$_lang['imageplus.phpThumbParams'] = 'Extra phpThumb opties';
$_lang['imageplus.phpThumbParams_desc'] = 'Voeg extra phpThumb opties zoals filters toe. Documentatie is <a href="https://raw.githubusercontent.com/JamesHeinrich/phpThumb/master/docs/phpthumb.readme.txt" target="_blank">hier</a> te vinden.';
$_lang['imageplus.outputChunk'] = 'Output Chunk';
$_lang['imageplus.outputChunk_desc'] = 'Selecteer een chunk voor de TV output. Laat leeg om alleen een link naar de afbeelding terug te krijgen.';
$_lang['imageplus.generateUrl'] = 'Genereer Thumbnail URL';
$_lang['imageplus.generateUrl_desc'] = '(Optioneel) De thumbnail URL is mogelijk niet nodig, als de thumbnail gegenereerd wordt in de output chunk met bijvoorbeeld een pthumb output filter.';
$_lang['imageplus.generateUrl_desc_warning'] = 'Deze optie moet ingeschakeld zijn wanneer je geen "output chunk" hebt gedefineerd; of wanneer je de "output chunk" wel hebt gedefineerd en de [[+url]] placeholder hierin gebruikt. Als deze optie in voorgaande situaties niet is ingeschakeld dan worden afbeeldingen niet bijgesneden en/of vergroot en verkleind en wordt het pad naar de originele afbeelding weergegeven.';
/** Placeholder descriptions */
$_lang['imageplus.placeholder.url'] = 'URL van de thumbnail';
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
