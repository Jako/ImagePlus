<?php
/**
 * Default Lexicon Entries for Image+
 *
 * @package imageplus
 * @subpackage lexicon
 */
$_lang['imageplus'] = 'Image+';
$_lang['imageplus.editor_title'] = 'Editor Image+';
$_lang['imageplus.alt_text'] = 'Testo per l’attributo Alt';
$_lang['imageplus.caption'] = 'Didascalia';
$_lang['imageplus.credits'] = 'Crediti';
/** Input options render **/
$_lang['imageplus.input_section'] = 'Opzioni Image+';
$_lang['imageplus.input_section_desc'] = 'Le seguenti opzioni possono essere sovrascritte dalle impostazioni di sistema/contesto. Si prega di leggere la <a href="https://jako.github.io/ImagePlus/usage/#contextsystem-settings" target="_blank">documentazione</a> per conoscere le chiavi da utilizzare nelle impostazioni di sistema/contesto.';
$_lang['imageplus.thumbnailHeight'] = 'Altezza delle anteprime';
$_lang['imageplus.thumbnailHeight_desc'] = '(Opzionale, Intero) L’altezza della miniatura dell’immagine nel pannello della Template Variable. Predefinito: 250';
$_lang['imageplus.allowAltTag'] = 'Visualizza campo tag Alt';
$_lang['imageplus.allowAltTag_desc'] = 'Consente all’utente di inserire un titolo/tag-alt per l’immagine.';
$_lang['imageplus.allowCaption'] = 'Visualizza campo didascalia';
$_lang['imageplus.allowCaption_desc'] = 'Consente all’utente di inserire una didascalia per l’immagine.';
$_lang['imageplus.allowCredits'] = 'Mostra il campo Credits';
$_lang['imageplus.allowCredits_desc'] = 'Consente all’utente di inserire un credito per l’immagine.';
/** Output options render **/
$_lang['imageplus.phpThumbParams'] = 'Parametri aggiuntivi di phpThumb';
$_lang['imageplus.phpThumbParams_desc'] = '(Facoltativo) Parametri di phpThumb per il segnaposto options del chunk di output, per esempio per un filtro di output pThumb. La documentazione può essere trovata <a href="https://raw.githubusercontent.com/JamesHeinrich/phpThumb/master/docs/phpthumb.readme.txt" target="_blank">qui</a>.';
$_lang['imageplus.outputChunk'] = 'Chunk Output';
$_lang['imageplus.outputChunk_desc'] = 'Seleziona un chunk per l’output della variabile tv. Lascia vuoto per avere un semplice url come output.';
$_lang['imageplus.generateUrl'] = 'Usa l’URL del file di ritaglio';
$_lang['imageplus.generateUrl_desc'] = '(Facoltativo) L’URL del file di ritaglio potrebbe non essere necessario se la miniatura viene generata nel chunk di output, per esempio con un filtro di output pThumb.';
$_lang['imageplus.generateUrl_desc_warning'] = 'Devi attivare questa opzione quando non specifichi un chunk di output nelle opzioni di output o quando usi il segnaposto [[+url]] nel chunk di output specificato. Altrimenti l’immagine non viene ritagliata e viene restituito l’URL dell’immagine originale.';
/** Placeholder descriptions */
$_lang['imageplus.placeholder.url'] = 'URL del file di ritaglio';
$_lang['imageplus.placeholder.alt'] = 'Testo per l’attributo Alt';
$_lang['imageplus.placeholder.width'] = 'Larghezza dell’anteprima dell’immagine (ignorata quando 0)';
$_lang['imageplus.placeholder.height'] = 'Altezza dell’anteprima dell’immagine (ignorata quando 0)';
$_lang['imageplus.placeholder.source.src'] = 'Percorso dell’immagine di origine';
$_lang['imageplus.placeholder.source.width'] = 'Larghezza dell’immagine di origine';
$_lang['imageplus.placeholder.source.height'] = 'Altezza dell’immagine di origine';
$_lang['imageplus.placeholder.crop.width'] = 'Larghezza del ritaglio dell’immagine di origine';
$_lang['imageplus.placeholder.crop.height'] = 'Altezza del ritaglio dell’immagine di origine';
$_lang['imageplus.placeholder.crop.x'] = 'Posizione orizzontale (asse x) del ritaglio dell’immagine di origine';
$_lang['imageplus.placeholder.crop.y'] = 'Posizione verticale (asse y) del ritaglio dell’immagine di origine';
$_lang['imageplus.placeholder.options'] = 'L’opzione su phpThumb per generare l’anteprima dell’immagine';
$_lang['imageplus.placeholder.crop.options'] = 'L’opzione di ritaglio su phpthumb per generare l’anteprima dell’immagine';
$_lang['imageplus.error.image_too_small.title'] = 'Immagine troppo piccola';
$_lang['imageplus.error.image_too_small.msg'] = 'L’immagine selezionata è troppo piccola per essere usata qui. Si prega di selezionare un’altra immagine.';
$_lang['imageplus.error.image_not_found.title'] = 'L’immagine non è stata trovata';
$_lang['imageplus.error.image_not_found.msg'] = 'L’immagine non è stata trovata e perciò non può essere tagliata. Si prega di selezionare un’altra immagine.';
