<?php
/**
 * Default Lexicon Entries for Image+
 *
 * @package imageplus
 * @subpackage lexicon
 */
$_lang['imageplus'] = 'Image+';
$_lang['imageplus.editor_title'] = 'Image+ Editor';
$_lang['imageplus.alt_text'] = 'Alt-Text';
$_lang['imageplus.caption'] = 'Titel';
$_lang['imageplus.credits'] = 'Bildnachweis';
/** Input options render **/
$_lang['imageplus.input_section'] = 'Image+ Optionen';
$_lang['imageplus.input_section_desc'] = 'Die folgenden Optionen können per System-/Kontexteinstellungen überschrieben werden. Bitte lesen Sie die <a href="https://jako.github.io/ImagePlus/usage/#contextsystem-settings" target="_blank">Dokumentation</a> für die entsprechenden Einträge in den System-/Kontexteinstellungen.';
$_lang['imageplus.minWidth'] = 'Minimale Bildbreite';
$_lang['imageplus.minWidth_desc'] = '(Optional, Integer) Die minimale Breite des ausgewählten oder hochgeladenen Bildes in Pixeln.';
$_lang['imageplus.minHeight'] = 'Minimale Bildhöhe';
$_lang['imageplus.minHeight_desc'] = '(Optional, Integer) Die minimale Höhe des ausgewählten oder hochgeladenen Bildes in Pixeln.';
$_lang['imageplus.thumbnailHeight'] = 'Höhe des Thumbnails';
$_lang['imageplus.thumbnailHeight_desc'] = '(Optional, Integer) Höhe des Thumbnails im Template Variablen Bereich. Standard: 250';
$_lang['imageplus.inlinePreview'] = 'Alle Bilder in einer Zeile anzeigen';
$_lang['imageplus.inlinePreview_desc'] = '(Optional, Ja/Nein) Zeigt die Bildausschnitte und das Originalbild auch in einer Zeile an, wenn der Template Variablen Bereich mindestens 700 Pixel breit ist. Standard: Ja';
$_lang['imageplus.allowAltTag'] = 'Alternatives Textfeld anzeigen';
$_lang['imageplus.allowAltTag_desc'] = 'Ermöglicht die Eingabe eines Alt oder Title-Attributes für das Bild.';
$_lang['imageplus.allowCaption'] = 'Titel Feld anzeigen';
$_lang['imageplus.allowCaption_desc'] = 'Ermöglicht die Eingabe eines Titels für das Bild.';
$_lang['imageplus.allowCredits'] = 'Bildnachweis Feld anzeigen';
$_lang['imageplus.allowCredits_desc'] = 'Ermöglicht die Eingabe eines Bildnachweises für das Bild.';
/** Output options render **/
$_lang['imageplus.phpThumbParams'] = 'Zusätzliche phpThumb Parameter';
$_lang['imageplus.phpThumbParams_desc'] = '(Optional) phpThumb Parameter für den options Platzhalter im Ausgabe Chunk, z.B. für einen pThumb Output Filter. Mehr Informationen zu phpThumb Parametern erhalten Sie <a href="https://raw.githubusercontent.com/JamesHeinrich/phpThumb/master/docs/phpthumb.readme.txt" target="_blank">hier</a>.';
$_lang['imageplus.outputChunk'] = 'Ausgabe Chunk';
$_lang['imageplus.outputChunk_desc'] = '(Optional) Wählen Sie einen Ausgabe Chunk aus. Wenn kein Wert angebeben ist wird der Bildpfad ausgegeben.';
$_lang['imageplus.generateUrl'] = 'URL der Ausschnittsdatei verwenden';
$_lang['imageplus.generateUrl_desc'] = '(Optional) Die URL der Ausschnittsdatei wird eventuell nicht benötigt, wenn das Thumbnail im Ausgabe Chunk z.B. mit einem pThumb Output Filter generiert wird.';
$_lang['imageplus.generateUrl_desc_warning'] = 'Sie müssen diese Option aktivieren, wenn Sie keinen Ausgabe Chunk benutzen oder wenn Sie den [[+url]] Platzhalter im angegebenen Ausgabe Chunk einsetzen. Andernfalls wird das Bild nicht beschnitten und die URL des Originalbilds ausgegeben.';
/** Placeholder descriptions */
$_lang['imageplus.placeholder.url'] = 'URL der Datei des Bildausschnitts';
$_lang['imageplus.placeholder.alt'] = 'Alt-Text';
$_lang['imageplus.placeholder.width'] = 'Breite des Thumbnails (wird ignoriert, wenn 0)';
$_lang['imageplus.placeholder.height'] = 'Höhe des Thumbnails (wird ignoriert, wenn 0)';
$_lang['imageplus.placeholder.source.src'] = 'Server-Pfad zum Originalbild';
$_lang['imageplus.placeholder.source.width'] = 'Minimale Breite des Originalbilds';
$_lang['imageplus.placeholder.source.height'] = 'Minimale Höhe des Originalbilds';
$_lang['imageplus.placeholder.crop.width'] = 'Crop-Breite des Originalbilds';
$_lang['imageplus.placeholder.crop.height'] = 'Crop-Höhe des Originalbilds';
$_lang['imageplus.placeholder.crop.x'] = 'Crop-X-Startposition des Originalbilds';
$_lang['imageplus.placeholder.crop.y'] = 'Crop-Y-Startposition des Originalbilds';
$_lang['imageplus.placeholder.options'] = 'phpThumb Optionen für das Thumbnail';
$_lang['imageplus.placeholder.crop.options'] = 'phpThumb Crop Optionen für das Thumbnail';
$_lang['imageplus.error.image_too_small.title'] = 'Bild ist zu klein';
$_lang['imageplus.error.image_too_small.msg'] = 'Das gewählte Bild ist zu klein um benutzt zu werden. Bitte wählen Sie ein anderes Bild.';
$_lang['imageplus.error.image_not_found.title'] = 'Bild nicht gefunden';
$_lang['imageplus.error.image_not_found.msg'] = 'Das Bild wurde nicht gefunden und kann nicht zugeschnitten werden. Bitte wählen Sie ein anderes Bild.';
/** Image crops **/
$_lang['imageplus.sizes'] = 'Bildausschnitte';
$_lang['imageplus.sizes_desc'] = '(Optional, JSON) Ein JSON-kodiertes Objekt für die Bildausschnitte, z.B. {"desktop": {"name": "Desktop", "size": "1000x500"}, "mobile": {"name": "Mobil", "proportion": "4x3"}}. Jeder Bildausschnitt kann eine Größe (size), ein Seitenverhältnis (proportion), eine Breite (width) oder eine Höhe (height) haben. Ist dieser Wert leer, wird ein Bildausschnitt ohne Vorgaben verwendet.';
$_lang['imageplus.crop'] = 'Bildausschnitt';
$_lang['imageplus.crop_desc'] = '(Optional) Der Schlüssel des Bildausschnitts für die Ausgabe. Leer lassen, um den ersten Bildausschnitt zu verwenden.';
$_lang['imageplus.placeholder.crop.url'] = 'URL der im Manager erstellten Datei des Bildausschnitts';
/** Crop window **/
$_lang['imageplus.cropimage'] = 'Bildausschnitt';
$_lang['imageplus.cropimage.changes.content'] = 'Es gibt ungespeicherte Änderungen, wollen Sie fortfahren?';
$_lang['imageplus.cropimage.changes.title'] = 'Warnung';
$_lang['imageplus.cropimage.changes_saved'] = 'Änderungen gespeichert';
$_lang['imageplus.cropimage.changes_unsaved'] = 'Es gibt ungespeicherte Änderungen.';
$_lang['imageplus.cropimage.crop'] = 'Bild zuschneiden';
$_lang['imageplus.cropimage.crop.height'] = 'Höhe';
$_lang['imageplus.cropimage.crop.proportion'] = 'Verhältnis';
$_lang['imageplus.cropimage.crop.width'] = 'Breite';
$_lang['imageplus.cropimage.crop.x'] = 'Links';
$_lang['imageplus.cropimage.crop.y'] = 'Oben';
$_lang['imageplus.cropimage.default'] = 'Original';
$_lang['imageplus.cropimage.error_crop'] = 'Fehler beim Erstellen der Bildausschnittdatei.';
$_lang['imageplus.cropimage.error_cropheight_cropwidth'] = 'Der Wert von cropWidth oder von cropHeight ist leer.';
$_lang['imageplus.cropimage.error_file_not_set'] = 'Keine Datei angegeben.';
$_lang['imageplus.cropimage.error_image'] = 'Der Bildausschnitt konnte nicht gespeichert werden.';
$_lang['imageplus.cropimage.error_image_not_exists'] = 'Das Bild existiert nicht.';
$_lang['imageplus.cropimage.error_image_not_valid'] = 'Das Bild ist keine gültige Datei.';
$_lang['imageplus.cropimage.error_min_width'] = 'Das Bild ist [[+width]] Pixel breit, es muss aber mindestens [[+min_width]] Pixel breit sein.';
$_lang['imageplus.cropimage.error_min_height'] = 'Das Bild ist [[+height]] Pixel hoch, es muss aber mindestens [[+min_height]] Pixel hoch sein.';
$_lang['imageplus.cropimage.error_memory'] = 'Das Bild ([[+width]] x [[+height]] Pixel) ist zu groß, um zugeschnitten zu werden. Der Zuschnitt benötigt etwa [[+memory]] Arbeitsspeicher, das PHP memory_limit beträgt aber [[+limit]] und kann nicht erhöht werden. Bitte laden Sie ein kleineres Bild hoch oder bitten Sie den Serveradministrator, das memory_limit zu erhöhen.';
$_lang['imageplus.cropimage.original'] = 'Originalbild';
$_lang['imageplus.cropimage.original.download'] = 'Originalbild herunterladen';
$_lang['imageplus.cropimage.original.height'] = 'Höhe';
$_lang['imageplus.cropimage.original.width'] = 'Breite';
$_lang['imageplus.cropimage.reload'] = 'Bild neu laden';
$_lang['imageplus.cropimage.reload.content'] = 'Das Bild wird neu geladen und alle gespeicherten Bildausschnitte werden daraus neu erstellt, z.B. wenn das Bild durch ein geändertes Bild mit dem gleichen Dateinamen ersetzt wurde. Nicht gespeicherte Änderungen werden verworfen. Möchten Sie fortfahren?';
$_lang['imageplus.cropimage.reload.title'] = 'Bild neu laden';
$_lang['imageplus.cropimage.reload_ratio.content'] = 'Das Seitenverhältnis des Bildes hat sich geändert. Bitte prüfen Sie die Bildausschnitte und speichern Sie diese bei Bedarf erneut.';
$_lang['imageplus.cropimage.reload_ratio.title'] = 'Warnung';
$_lang['imageplus.cropimage.reloading'] = 'Die Bildausschnitte werden neu erstellt …';
$_lang['imageplus.cropimage.reset'] = 'Änderungen zurücksetzen';
$_lang['imageplus.cropimage.save'] = 'Ausschnitt speichern';
$_lang['imageplus.cropimage.saving'] = 'Bildausschnitt speichern …';
$_lang['imageplus.cropimage.select_file'] = 'Datei auswählen …';
$_lang['imageplus.cropimage.sets'] = 'Bildausschnitt auswählen';
$_lang['imageplus.cropimage.success_image'] = 'Bildausschnitt gespeichert';
$_lang['imageplus.cropimage.target'] = 'Zielbild';
$_lang['imageplus.cropimage.target.height'] = 'Höhe';
$_lang['imageplus.cropimage.target.width'] = 'Breite';
$_lang['imageplus.cropimage.unset.content'] = 'Einige Bildausschnitt wurden nicht erstellt. Möchten Sie fortfahren?';
$_lang['imageplus.cropimage.unset.title'] = 'Warnung';
