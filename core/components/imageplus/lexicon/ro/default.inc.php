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
$_lang['imageplus.caption'] = 'Legenda';
$_lang['imageplus.credits'] = 'Credite';
/** Input options render **/
$_lang['imageplus.input_section'] = 'Image+ Options';
$_lang['imageplus.input_section_desc'] = 'Următoarele opțiuni pot fi înlocuite de setările de context/sistem. Vă rugăm să citiți documentația <a href="https://jako.github.io/ImagePlus/usage/#contextsystem-settings" target="_blank">documentația</a> pentru tastele corespunzătoare din setările de context/sistem.';
$_lang['imageplus.thumbnailHeight'] = 'Înălțimea miniaturii';
$_lang['imageplus.thumbnailHeight_desc'] = '(Opțional, număr întreg) Înălțimea miniaturii imaginii din panoul de variabile al șablonului. Implicit: 250';
$_lang['imageplus.allowAltTag'] = 'Afișați câmpul Alt Tag';
$_lang['imageplus.allowAltTag_desc'] = 'Permiteți utilizatorului să introducă un titlu/un etichet pentru imagine.';
$_lang['imageplus.allowCaption'] = 'Afișați câmpul de legendă';
$_lang['imageplus.allowCaption_desc'] = 'Permiteți utilizatorului să introducă o legendă pentru imagine.';
$_lang['imageplus.allowCredits'] = 'Afișați câmpul Credite';
$_lang['imageplus.allowCredits_desc'] = 'Permite utilizatorului să introducă un credit pentru imagine.';
/** Output options render **/
$_lang['imageplus.phpThumbParams'] = 'Parametri suplimentari phpThumb';
$_lang['imageplus.phpThumbParams_desc'] = '(Opțional) Parametri phpThumb pentru substituentul options din chunk-ul de ieșire, de exemplu pentru un filtru de ieșire pthumb. Documentația poate fi găsită <a href="https://raw.githubusercontent.com/JamesHeinrich/phpThumb/master/docs/phpthumb.readme.txt" target="_blank">aici</a>.';
$_lang['imageplus.outputChunk'] = 'Output Chunk';
$_lang['imageplus.outputChunk_desc'] = 'Selectați o bucată pentru ieșirea TV. Lăsați gol pentru ieșirea în format raw url.';
$_lang['imageplus.generateUrl'] = 'Folosește URL-ul fișierului decupat';
$_lang['imageplus.generateUrl_desc'] = '(Opțional) URL-ul fișierului decupat poate să nu fie necesar dacă generați miniatura în chunk-ul de ieșire, de exemplu, printr-un filtru de ieșire pthumb.';
$_lang['imageplus.generateUrl_desc_warning'] = 'Trebuie să activați această opțiune atunci când nu specificați un chunk de ieșire în opțiunile de ieșire sau atunci când folosiți substituentul [[+url]] în chunk-ul de ieșire specificat. În caz contrar, imaginea nu este decupată și se returnează URL-ul imaginii originale.';
/** Placeholder descriptions */
$_lang['imageplus.placeholder.url'] = 'URL-ul fișierului decupat';
$_lang['imageplus.placeholder.alt'] = 'Alt text';
$_lang['imageplus.placeholder.width'] = 'Lățimea imaginii miniaturale (ignorată când este 0)';
$_lang['imageplus.placeholder.height'] = 'Înălțimea imaginii miniaturale (ignorată dacă este 0)';
$_lang['imageplus.placeholder.source.src'] = 'Cale de acces la imaginea sursă';
$_lang['imageplus.placeholder.source.width'] = 'Lățimea imaginii sursă';
$_lang['imageplus.placeholder.source.height'] = 'Înălțimea imaginii sursă';
$_lang['imageplus.placeholder.crop.width'] = 'Lățimea de tăiere a imaginii sursă';
$_lang['imageplus.placeholder.crop.height'] = 'Înălțimea de tăiere a imaginii sursă';
$_lang['imageplus.placeholder.crop.x'] = 'Poziția x de tăiere a imaginii sursă';
$_lang['imageplus.placeholder.crop.y'] = 'Poziția y de tăiere a imaginii sursă';
$_lang['imageplus.placeholder.options'] = 'phpThumb șir de opțiuni pentru a genera imaginea miniaturală';
$_lang['imageplus.placeholder.crop.options'] = 'phpThumb crop șir de opțiuni pentru generarea imaginii thumbnail';
$_lang['imageplus.error.image_too_small.title'] = 'Imagine prea mică';
$_lang['imageplus.error.image_too_small.msg'] = 'Imaginea selectată este prea mică pentru a fi utilizată aici. Vă rugăm să selectați o altă imagine.';
$_lang['imageplus.error.image_not_found.title'] = 'Imaginea nu a fost găsită';
$_lang['imageplus.error.image_not_found.msg'] = 'Imaginea nu a fost găsită și nu poate fi decupată. Vă rugăm să selectați o altă imagine.';
