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
$_lang['imageplus.minWidth'] = 'Lățimea minimă a imaginii';
$_lang['imageplus.minWidth_desc'] = '(Opțional, număr întreg) Lățimea minimă a imaginii selectate sau încărcate, în pixeli.';
$_lang['imageplus.minHeight'] = 'Înălțimea minimă a imaginii';
$_lang['imageplus.minHeight_desc'] = '(Opțional, număr întreg) Înălțimea minimă a imaginii selectate sau încărcate, în pixeli.';
$_lang['imageplus.thumbnailHeight'] = 'Înălțimea miniaturii';
$_lang['imageplus.thumbnailHeight_desc'] = '(Opțional, număr întreg) Înălțimea miniaturii imaginii din panoul de variabile al șablonului. Implicit: 250';
$_lang['imageplus.inlinePreview'] = 'Afișează toate imaginile pe un rând';
$_lang['imageplus.inlinePreview_desc'] = '(Opțional, Da/Nu) Afișează decupajele și imaginea originală pe un rând, când panoul variabilei de șablon are cel puțin 700 de pixeli lățime. Implicit: Da';
$_lang['imageplus.format'] = 'Format imagine';
$_lang['imageplus.format_desc'] = '(Opțional) Formatul fișierelor de decupare a imaginii. Implicit: WebP';
$_lang['imageplus.format_image'] = 'Formatul imaginii';
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
/** Image crops **/
$_lang['imageplus.sizes'] = 'Decupaje de imagine';
$_lang['imageplus.sizes_desc'] = '(Opțional, JSON) Un obiect codificat JSON pentru decupajele de imagine, de ex. {"desktop": {"name": "Desktop", "size": "1000x500"}, "mobile": {"name": "Mobile", "proportion": "4x3"}}. Fiecare decupaj poate avea o dimensiune (size), o proporție (proportion), o lățime (width) sau o înălțime (height). Dacă această valoare este goală, se folosește un decupaj fără restricții.';
$_lang['imageplus.crop'] = 'Decupaj de imagine';
$_lang['imageplus.crop_desc'] = '(Opțional) Cheia decupajului de imagine folosit pentru ieșire. Lăsați gol pentru a folosi primul decupaj.';
$_lang['imageplus.placeholder.crop.url'] = 'URL-ul fișierului decupat creat în manager';
/** Crop window **/
$_lang['imageplus.cropimage'] = 'Decupează imaginea';
$_lang['imageplus.cropimage.changes.content'] = 'Există modificări nesalvate, doriți să continuați?';
$_lang['imageplus.cropimage.changes.title'] = 'Avertisment';
$_lang['imageplus.cropimage.changes_saved'] = 'Modificări salvate';
$_lang['imageplus.cropimage.changes_unsaved'] = 'Există modificări nesalvate.';
$_lang['imageplus.cropimage.crop'] = 'Decupează imaginea';
$_lang['imageplus.cropimage.crop.height'] = 'Înălțime';
$_lang['imageplus.cropimage.crop.proportion'] = 'Proporție';
$_lang['imageplus.cropimage.crop.width'] = 'Lățime';
$_lang['imageplus.cropimage.crop.x'] = 'Stânga';
$_lang['imageplus.cropimage.crop.y'] = 'Sus';
$_lang['imageplus.cropimage.default'] = 'Original';
$_lang['imageplus.cropimage.error_crop'] = 'Eroare la crearea fișierului decupat.';
$_lang['imageplus.cropimage.error_cropheight_cropwidth'] = 'Valoarea cropWidth sau cropHeight este goală.';
$_lang['imageplus.cropimage.error_file_not_set'] = 'Nu a fost specificat niciun fișier.';
$_lang['imageplus.cropimage.error_format'] = 'Formatul de imagine [[+format]] nu este suportat de biblioteca GD a serverului.';
$_lang['imageplus.cropimage.error_image'] = 'Decupajul imaginii nu a putut fi salvat.';
$_lang['imageplus.cropimage.error_image_not_exists'] = 'Imaginea nu există.';
$_lang['imageplus.cropimage.error_image_not_valid'] = 'Imaginea nu este un fișier valid.';
$_lang['imageplus.cropimage.error_min_width'] = 'Imaginea are [[+width]] pixeli lățime, dar trebuie să aibă cel puțin [[+min_width]] pixeli lățime.';
$_lang['imageplus.cropimage.error_min_height'] = 'Imaginea are [[+height]] pixeli înălțime, dar trebuie să aibă cel puțin [[+min_height]] pixeli înălțime.';
$_lang['imageplus.cropimage.error_memory'] = 'Imaginea ([[+width]] x [[+height]] pixeli) este prea mare pentru a fi decupată. Decuparea necesită aproximativ [[+memory]] de memorie, dar memory_limit al PHP este [[+limit]] și nu poate fi mărit. Încărcați o imagine mai mică sau cereți administratorului serverului să mărească memory_limit.';
$_lang['imageplus.cropimage.original'] = 'Imaginea originală';
$_lang['imageplus.cropimage.original.download'] = 'Descarcă imaginea originală';
$_lang['imageplus.cropimage.original.height'] = 'Înălțime';
$_lang['imageplus.cropimage.original.width'] = 'Lățime';
$_lang['imageplus.cropimage.reload'] = 'Reîncarcă imaginea';
$_lang['imageplus.cropimage.reload.content'] = 'Imaginea este încărcată din nou și toate decupajele salvate sunt create din nou din ea, de ex. dacă imaginea a fost înlocuită cu o imagine modificată cu același nume de fișier. Modificările nesalvate se pierd. Doriți să continuați?';
$_lang['imageplus.cropimage.reload.title'] = 'Reîncarcă imaginea';
$_lang['imageplus.cropimage.reload_ratio.content'] = 'Raportul de aspect al imaginii s-a schimbat. Verificați decupajele imaginii și salvați-le din nou, dacă este necesar.';
$_lang['imageplus.cropimage.reload_ratio.title'] = 'Avertisment';
$_lang['imageplus.cropimage.reloading'] = 'Se creează din nou decupajele imaginii …';
$_lang['imageplus.cropimage.reset'] = 'Anulează modificările';
$_lang['imageplus.cropimage.save'] = 'Salvează decupajul';
$_lang['imageplus.cropimage.saving'] = 'Se salvează decupajul imaginii …';
$_lang['imageplus.cropimage.select_file'] = 'Selectează fișierul …';
$_lang['imageplus.cropimage.sets'] = 'Selectați decupajul imaginii';
$_lang['imageplus.cropimage.success_image'] = 'Decupajul imaginii a fost salvat';
$_lang['imageplus.cropimage.target'] = 'Imaginea țintă';
$_lang['imageplus.cropimage.target.height'] = 'Înălțime';
$_lang['imageplus.cropimage.target.width'] = 'Lățime';
$_lang['imageplus.cropimage.unset.content'] = 'Unele decupaje de imagine nu sunt create, doriți să continuați?';
$_lang['imageplus.cropimage.unset.title'] = 'Avertisment';
