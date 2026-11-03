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
$_lang['imageplus.minWidth'] = 'Larghezza minima dell’immagine';
$_lang['imageplus.minWidth_desc'] = '(Facoltativo, intero) La larghezza minima dell’immagine selezionata o caricata in pixel.';
$_lang['imageplus.minHeight'] = 'Altezza minima dell’immagine';
$_lang['imageplus.minHeight_desc'] = '(Facoltativo, intero) L’altezza minima dell’immagine selezionata o caricata in pixel.';
$_lang['imageplus.thumbnailHeight'] = 'Altezza delle anteprime';
$_lang['imageplus.thumbnailHeight_desc'] = '(Opzionale, Intero) L’altezza della miniatura dell’immagine nel pannello della Template Variable. Predefinito: 250';
$_lang['imageplus.inlinePreview'] = 'Mostra tutte le immagini su una riga';
$_lang['imageplus.inlinePreview_desc'] = '(Facoltativo, Sì/No) Mostra i ritagli e l’immagine originale su una riga, quando il pannello della template variable è largo almeno 700 pixel. Predefinito: Sì';
$_lang['imageplus.format'] = 'Formato immagine';
$_lang['imageplus.format_desc'] = '(Opzionale) Il formato immagine dei file di ritaglio. Predefinito: WebP';
$_lang['imageplus.format_image'] = 'Formato dell\'immagine';
$_lang['imageplus.uploadPath'] = 'Percorso di caricamento';
$_lang['imageplus.uploadPath_desc'] = '(Opzionale) Il percorso nella media source per le immagini caricate. Sovrascrive l\'impostazione di sistema imageplus.upload_path. Segnaposto disponibili: [[+year]], [[+month]], [[+day]], [[+user]], [[+username]], [[+resource_id]], [[+tv_id]].';
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
$_lang['imageplus.placeholder.options'] = 'Stringa di opzioni phpThumb per generare l’anteprima dell’immagine';
$_lang['imageplus.placeholder.crop.options'] = 'L’opzione di ritaglio su phpthumb per generare l’anteprima dell’immagine';
$_lang['imageplus.error.image_too_small.title'] = 'Immagine troppo piccola';
$_lang['imageplus.error.image_too_small.msg'] = 'L’immagine selezionata è troppo piccola per essere usata qui. Si prega di selezionare un’altra immagine.';
$_lang['imageplus.error.image_not_found.title'] = 'L’immagine non è stata trovata';
$_lang['imageplus.error.image_not_found.msg'] = 'L’immagine non è stata trovata e perciò non può essere tagliata. Si prega di selezionare un’altra immagine.';
/** Image crops **/
$_lang['imageplus.sizes'] = 'Ritagli dell’immagine';
$_lang['imageplus.sizes_desc'] = '(Facoltativo, JSON) Un oggetto codificato in JSON per i ritagli dell’immagine, ad es. {"desktop": {"name": "Desktop", "size": "1000x500"}, "mobile": {"name": "Mobile", "proportion": "4x3"}}. Ogni ritaglio può avere una dimensione (size), una proporzione (proportion), una larghezza (width) o un’altezza (height). Se questo valore è vuoto, viene usato un ritaglio senza vincoli.';
$_lang['imageplus.crop'] = 'Ritaglio dell’immagine';
$_lang['imageplus.crop_desc'] = '(Facoltativo) La chiave del ritaglio dell’immagine usato per l’output. Lascia vuoto per usare il primo ritaglio.';
$_lang['imageplus.placeholder.crop.url'] = 'URL del file di ritaglio creato nel manager';
/** Crop window **/
$_lang['imageplus.cropimage'] = 'Ritaglia immagine';
$_lang['imageplus.cropimage.changes.content'] = 'Ci sono modifiche non salvate, vuoi continuare?';
$_lang['imageplus.cropimage.changes.title'] = 'Attenzione';
$_lang['imageplus.cropimage.changes_saved'] = 'Modifiche salvate';
$_lang['imageplus.cropimage.changes_unsaved'] = 'Ci sono modifiche non salvate.';
$_lang['imageplus.cropimage.crop'] = 'Ritaglia immagine';
$_lang['imageplus.cropimage.crop.height'] = 'Altezza';
$_lang['imageplus.cropimage.crop.proportion'] = 'Proporzione';
$_lang['imageplus.cropimage.crop.width'] = 'Larghezza';
$_lang['imageplus.cropimage.crop.x'] = 'Sinistra';
$_lang['imageplus.cropimage.crop.y'] = 'Alto';
$_lang['imageplus.cropimage.default'] = 'Originale';
$_lang['imageplus.cropimage.error_crop'] = 'Errore durante la creazione del file di ritaglio.';
$_lang['imageplus.cropimage.error_cropheight_cropwidth'] = 'Il valore di cropWidth o cropHeight è vuoto.';
$_lang['imageplus.cropimage.error_file_not_set'] = 'Nessun file specificato.';
$_lang['imageplus.cropimage.error_format'] = 'Il formato immagine [[+format]] non è supportato dalla libreria GD del server.';
$_lang['imageplus.cropimage.error_image'] = 'Il ritaglio dell’immagine non può essere salvato.';
$_lang['imageplus.cropimage.error_image_not_exists'] = 'L’immagine non esiste.';
$_lang['imageplus.cropimage.error_image_not_valid'] = 'L’immagine non è un file valido.';
$_lang['imageplus.cropimage.error_min_width'] = 'L’immagine è larga [[+width]] pixel, ma deve essere larga almeno [[+min_width]] pixel.';
$_lang['imageplus.cropimage.error_min_height'] = 'L’immagine è alta [[+height]] pixel, ma deve essere alta almeno [[+min_height]] pixel.';
$_lang['imageplus.cropimage.error_memory'] = 'L’immagine ([[+width]] x [[+height]] pixel) è troppo grande per essere ritagliata. Il ritaglio richiede circa [[+memory]] di memoria, ma il memory_limit di PHP è [[+limit]] e non può essere aumentato. Carica un’immagine più piccola o chiedi all’amministratore del server di aumentare il memory_limit.';
$_lang['imageplus.cropimage.original'] = 'Immagine originale';
$_lang['imageplus.cropimage.original.download'] = 'Scarica l’immagine originale';
$_lang['imageplus.cropimage.original.height'] = 'Altezza';
$_lang['imageplus.cropimage.original.width'] = 'Larghezza';
$_lang['imageplus.cropimage.reload'] = 'Ricarica immagine';
$_lang['imageplus.cropimage.reload.content'] = 'L’immagine viene caricata di nuovo e tutti i ritagli salvati vengono ricreati da essa, ad es. se l’immagine è stata sostituita da un’immagine modificata con lo stesso nome file. Le modifiche non salvate vengono scartate. Vuoi continuare?';
$_lang['imageplus.cropimage.reload.title'] = 'Ricarica immagine';
$_lang['imageplus.cropimage.reload_ratio.content'] = 'Le proporzioni dell’immagine sono cambiate. Controlla i ritagli dell’immagine e salvali di nuovo, se necessario.';
$_lang['imageplus.cropimage.reload_ratio.title'] = 'Attenzione';
$_lang['imageplus.cropimage.reloading'] = 'Ricreazione dei ritagli dell’immagine …';
$_lang['imageplus.cropimage.reset'] = 'Annulla modifiche';
$_lang['imageplus.cropimage.save'] = 'Salva ritaglio';
$_lang['imageplus.cropimage.saving'] = 'Salvataggio del ritaglio dell’immagine …';
$_lang['imageplus.cropimage.select_file'] = 'Seleziona file …';
$_lang['imageplus.cropimage.sets'] = 'Seleziona il ritaglio dell’immagine';
$_lang['imageplus.cropimage.success_image'] = 'Ritaglio dell’immagine salvato';
$_lang['imageplus.cropimage.target'] = 'Immagine di destinazione';
$_lang['imageplus.cropimage.target.height'] = 'Altezza';
$_lang['imageplus.cropimage.target.width'] = 'Larghezza';
$_lang['imageplus.cropimage.unset.content'] = 'Alcuni ritagli dell’immagine non sono stati creati, vuoi continuare?';
$_lang['imageplus.cropimage.unset.title'] = 'Attenzione';
