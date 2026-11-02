<?php
/**
 * Default Lexicon Entries for Image+
 *
 * @package imageplus
 * @subpackage lexicon
 */
$_lang['imageplus'] = 'Image+';
$_lang['imageplus.editor_title'] = 'Image+ editor';
$_lang['imageplus.alt_text'] = 'Alt-tekst';
$_lang['imageplus.caption'] = 'Billedtekst';
$_lang['imageplus.credits'] = 'Kreditter';
/** Input options render **/
$_lang['imageplus.input_section'] = 'Image+ Options';
$_lang['imageplus.input_section_desc'] = 'Følgende indstillinger kan tilsidesættes af kontekst-/systemindstillinger. Læs venligst <a href="https://jako.github.io/ImagePlus/usage/#contextsystem-settings" target="_blank">dokumentationen</a> for at finde de relevante taster i kontekst-/systemindstillingerne.';
$_lang['imageplus.thumbnailHeight'] = 'Miniature højde';
$_lang['imageplus.thumbnailHeight_desc'] = '(Valgfrit, heltal) Højden på miniaturebilledet i TV-fanen. Standard: 250';
$_lang['imageplus.allowAltTag'] = 'Alternativ tekst';
$_lang['imageplus.allowAltTag_desc'] = 'Tillader brugeren at indtaste en titel/alt-tag til billedet.';
$_lang['imageplus.allowCaption'] = 'Vis feltet med billedtekst';
$_lang['imageplus.allowCaption_desc'] = 'Tillader brugeren at indtaste en billedtekst til billedet.';
$_lang['imageplus.allowCredits'] = 'Vis feltet Credits';
$_lang['imageplus.allowCredits_desc'] = 'Tillader brugeren at angive en kredit for billedet.';
/** Output options render **/
$_lang['imageplus.phpThumbParams'] = 'Ekstra phpThumb parametre';
$_lang['imageplus.phpThumbParams_desc'] = '(Valgfrit) phpThumb parametre til options-pladsholderen i output chunk\'en, f.eks. til et pthumb output filter. Dokumentationen kan findes <a href="https://raw.githubusercontent.com/JamesHeinrich/phpThumb/master/docs/phpthumb.readme.txt" target="_blank">her</a>.';
$_lang['imageplus.outputChunk'] = 'Output chunk';
$_lang['imageplus.outputChunk_desc'] = 'Vælg en chunk til tv-udgang. Lad det være tomt for rå url-udgang.';
$_lang['imageplus.generateUrl'] = 'Brug URL til beskæringsfilen';
$_lang['imageplus.generateUrl_desc'] = '(Valgfrit) URL til beskæringsfilen er måske ikke nødvendig, hvis du genererer miniaturen i output chunk\'en f.eks. ved brug af pthumb output filter.';
$_lang['imageplus.generateUrl_desc_warning'] = 'Du skal aktivere denne indstilling, når du ikke angiver en output chunk i outputindstillingerne, eller når du bruger [[+url]]-pladsholderen [[+url]] i den angivne output chunk. Ellers beskæres billedet ikke, og URL\'en til det originale billede returneres.';
/** Placeholder descriptions */
$_lang['imageplus.placeholder.url'] = 'URL til beskæringsfilen';
$_lang['imageplus.placeholder.alt'] = 'Alt-tekst';
$_lang['imageplus.placeholder.width'] = 'Miniaturebredde (ignoreret ved 0)';
$_lang['imageplus.placeholder.height'] = 'Miniaturehøjde (ignoreret ved 0)';
$_lang['imageplus.placeholder.source.src'] = 'Stien til kildebilledet';
$_lang['imageplus.placeholder.source.width'] = 'Bredden på kildebilledet';
$_lang['imageplus.placeholder.source.height'] = 'Højden på kildebilledet';
$_lang['imageplus.placeholder.crop.width'] = 'Beskær kildebilledets bredde';
$_lang['imageplus.placeholder.crop.height'] = 'Beskær kildebilledets højde';
$_lang['imageplus.placeholder.crop.x'] = 'Beskær x placering af kildebilledet';
$_lang['imageplus.placeholder.crop.y'] = 'Beskær y placering af kildebilledet';
$_lang['imageplus.placeholder.options'] = 'phpThumb indstillingsstreng til at generere miniaturebilledet';
$_lang['imageplus.placeholder.crop.options'] = 'phpThumb beskæringsindstillingsstreng til at generere miniaturebilledet';
$_lang['imageplus.error.image_too_small.title'] = 'Billedet er for lille';
$_lang['imageplus.error.image_too_small.msg'] = 'Det valgte billede er for småt til at blive brugt her. Vælg venligst et andet billede.';
$_lang['imageplus.error.image_not_found.title'] = 'Billedet blev ikke fundet';
$_lang['imageplus.error.image_not_found.msg'] = 'Billedet blev ikke fundet og kan dermed ikke blive beskåret. Vælg venligst et andet billede.';
