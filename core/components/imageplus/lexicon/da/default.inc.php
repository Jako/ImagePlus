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
$_lang['imageplus.minWidth'] = 'Minimal billedbredde';
$_lang['imageplus.minWidth_desc'] = '(Valgfrit, heltal) Den minimale bredde af det valgte eller uploadede billede i pixels.';
$_lang['imageplus.minHeight'] = 'Minimal billedhøjde';
$_lang['imageplus.minHeight_desc'] = '(Valgfrit, heltal) Den minimale højde af det valgte eller uploadede billede i pixels.';
$_lang['imageplus.thumbnailHeight'] = 'Miniature højde';
$_lang['imageplus.thumbnailHeight_desc'] = '(Valgfrit, heltal) Højden på miniaturebilledet i TV-fanen. Standard: 250';
$_lang['imageplus.inlinePreview'] = 'Vis alle billeder på én linje';
$_lang['imageplus.inlinePreview_desc'] = '(Valgfrit, Ja/Nej) Vis beskæringerne og det originale billede på én linje, når template variable-panelet er mindst 700 pixels bredt. Standard: Ja';
$_lang['imageplus.format'] = 'Billedformat';
$_lang['imageplus.format_desc'] = '(Valgfri) Billedformatet for billedudsnitsfilerne. Standard: WebP';
$_lang['imageplus.format_image'] = 'Billedets format';
$_lang['imageplus.uploadPath'] = 'Upload-sti';
$_lang['imageplus.uploadPath_desc'] = '(Valgfri) Stien i mediekilden til uploadede billeder. Tilsidesætter systemindstillingen imageplus.upload_path. Tilgængelige pladsholdere: [[+year]], [[+month]], [[+day]], [[+user]], [[+username]], [[+resource_id]], [[+tv_id]].';
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
/** Placeholder descriptions */
$_lang['imageplus.placeholder.url'] = 'Beskæringsfilens URL';
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
/** Image crops **/
$_lang['imageplus.sizes'] = 'Billedbeskæringer';
$_lang['imageplus.sizes_desc'] = '(Valgfrit, JSON) Et JSON-kodet objekt for billedbeskæringerne, f.eks. {"desktop": {"name": "Desktop", "size": "1000x500"}, "mobile": {"name": "Mobile", "proportion": "4x3"}}. Hver beskæring kan have en størrelse (size), et forhold (proportion), en bredde (width) eller en højde (height). Hvis denne værdi er tom, bruges én billedbeskæring uden begrænsninger.';
$_lang['imageplus.crop'] = 'Billedbeskæring';
$_lang['imageplus.crop_desc'] = '(Valgfrit) Nøglen til den billedbeskæring, der bruges til output. Lad feltet være tomt for at bruge den første billedbeskæring.';
$_lang['imageplus.placeholder.crop.url'] = 'URL til beskæringsfilen oprettet i manageren';
/** Crop window **/
$_lang['imageplus.cropimage'] = 'Beskær billede';
$_lang['imageplus.cropimage.changes.content'] = 'Der er ændringer, der ikke er gemt. Vil du fortsætte?';
$_lang['imageplus.cropimage.changes.title'] = 'Advarsel';
$_lang['imageplus.cropimage.changes_saved'] = 'Ændringer gemt';
$_lang['imageplus.cropimage.changes_unsaved'] = 'Der er ændringer, der ikke er gemt.';
$_lang['imageplus.cropimage.crop'] = 'Beskær billede';
$_lang['imageplus.cropimage.crop.height'] = 'Højde';
$_lang['imageplus.cropimage.crop.proportion'] = 'Forhold';
$_lang['imageplus.cropimage.crop.width'] = 'Bredde';
$_lang['imageplus.cropimage.crop.x'] = 'Venstre';
$_lang['imageplus.cropimage.crop.y'] = 'Top';
$_lang['imageplus.cropimage.default'] = 'Original';
$_lang['imageplus.cropimage.error_crop'] = 'Fejl ved oprettelse af beskæringsfilen.';
$_lang['imageplus.cropimage.error_cropheight_cropwidth'] = 'Værdien i cropWidth eller cropHeight er tom.';
$_lang['imageplus.cropimage.error_file_not_set'] = 'Ingen fil angivet.';
$_lang['imageplus.cropimage.error_format'] = 'Billedformatet [[+format]] understøttes ikke af serverens GD-bibliotek.';
$_lang['imageplus.cropimage.error_image'] = 'Billedbeskæringen kunne ikke gemmes.';
$_lang['imageplus.cropimage.error_image_not_exists'] = 'Billedet findes ikke.';
$_lang['imageplus.cropimage.error_image_not_valid'] = 'Billedet er ikke en gyldig fil.';
$_lang['imageplus.cropimage.error_min_width'] = 'Billedet er [[+width]] pixels bredt, men det skal være mindst [[+min_width]] pixels bredt.';
$_lang['imageplus.cropimage.error_min_height'] = 'Billedet er [[+height]] pixels højt, men det skal være mindst [[+min_height]] pixels højt.';
$_lang['imageplus.cropimage.error_memory'] = 'Billedet ([[+width]] x [[+height]] pixels) er for stort til at blive beskåret. Beskæringen kræver ca. [[+memory]] hukommelse, men PHP memory_limit er [[+limit]] og kan ikke hæves. Upload et mindre billede, eller bed serveradministratoren om at hæve memory_limit.';
$_lang['imageplus.cropimage.original'] = 'Originalt billede';
$_lang['imageplus.cropimage.original.download'] = 'Download originalt billede';
$_lang['imageplus.cropimage.original.height'] = 'Højde';
$_lang['imageplus.cropimage.original.width'] = 'Bredde';
$_lang['imageplus.cropimage.reload'] = 'Genindlæs billede';
$_lang['imageplus.cropimage.reload.content'] = 'Billedet indlæses igen, og alle gemte billedbeskæringer oprettes igen ud fra det, f.eks. hvis billedet er erstattet af et ændret billede med samme filnavn. Ændringer, der ikke er gemt, forkastes. Vil du fortsætte?';
$_lang['imageplus.cropimage.reload.title'] = 'Genindlæs billede';
$_lang['imageplus.cropimage.reload_ratio.content'] = 'Billedets størrelsesforhold er ændret. Kontroller billedbeskæringerne, og gem dem igen om nødvendigt.';
$_lang['imageplus.cropimage.reload_ratio.title'] = 'Advarsel';
$_lang['imageplus.cropimage.reloading'] = 'Billedbeskæringerne oprettes igen …';
$_lang['imageplus.cropimage.reset'] = 'Fortryd ændringer';
$_lang['imageplus.cropimage.save'] = 'Gem beskæring';
$_lang['imageplus.cropimage.saving'] = 'Gemmer billedbeskæringen …';
$_lang['imageplus.cropimage.select_file'] = 'Vælg fil …';
$_lang['imageplus.cropimage.sets'] = 'Vælg billedbeskæring';
$_lang['imageplus.cropimage.success_image'] = 'Billedbeskæring gemt';
$_lang['imageplus.cropimage.target'] = 'Målbillede';
$_lang['imageplus.cropimage.target.height'] = 'Højde';
$_lang['imageplus.cropimage.target.width'] = 'Bredde';
$_lang['imageplus.cropimage.unset.content'] = 'Nogle billedbeskæringer er ikke oprettet. Vil du fortsætte?';
$_lang['imageplus.cropimage.unset.title'] = 'Advarsel';
