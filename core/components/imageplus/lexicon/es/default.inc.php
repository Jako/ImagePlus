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
$_lang['imageplus.caption'] = 'Título';
$_lang['imageplus.credits'] = 'Créditos';
/** Input options render **/
$_lang['imageplus.input_section'] = 'Image+ Options';
$_lang['imageplus.input_section_desc'] = 'Las siguientes opciones pueden ser anuladas por la configuración del contexto/sistema. Por favor, lea la <a href="https://jako.github.io/ImagePlus/usage/#contextsystem-settings" target="_blank">documentación</a> para conocer las claves apropiadas en la configuración del contexto/sistema.';
$_lang['imageplus.thumbnailHeight'] = 'Altura de las miniaturas';
$_lang['imageplus.thumbnailHeight_desc'] = '(Opcional, Entero) La altura de la miniatura de la imagen en el panel de variables de la plantilla. Predeterminado: 250';
$_lang['imageplus.allowAltTag'] = 'Etiqueta Alt';
$_lang['imageplus.allowAltTag_desc'] = 'Permitir que el usuario introduzca un título/etiqueta de alto para la imagen.';
$_lang['imageplus.allowCaption'] = 'Mostrar campo de subtítulos';
$_lang['imageplus.allowCaption_desc'] = 'Permite al usuario introducir un título para la imagen.';
$_lang['imageplus.allowCredits'] = 'Mostrar campo de créditos';
$_lang['imageplus.allowCredits_desc'] = 'Permitir al usuario introducir un crédito para la imagen.';
/** Output options render **/
$_lang['imageplus.phpThumbParams'] = 'Parámetros phpThumb adicionales';
$_lang['imageplus.phpThumbParams_desc'] = '(Opcional) Parámetros de phpThumb para el marcador de posición options del chunk de salida, por ejemplo, para un filtro de salida pthumb. La documentación se puede encontrar <a href="https://raw.githubusercontent.com/JamesHeinrich/phpThumb/master/docs/phpthumb.readme.txt" target="_blank">aquí</a>.';
$_lang['imageplus.outputChunk'] = 'Resultado chunk';
$_lang['imageplus.outputChunk_desc'] = 'Selecciona un chunk para obtener un resultado del tv. Dejar en vacío para obtener un resultado de la url cruda.';
$_lang['imageplus.generateUrl'] = 'Usar la URL del archivo de recorte';
$_lang['imageplus.generateUrl_desc'] = '(Opcional) La URL del archivo de recorte puede no ser necesaria si se genera la miniatura en el chunk de salida, es decir, mediante un filtro de salida pthumb.';
$_lang['imageplus.generateUrl_desc_warning'] = 'Tiene que activar esta opción, cuando no especifica un chunk de salida en las opciones de salida o cuando utiliza el marcador de posición [[+url]] en el chunk de salida especificado. En caso contrario, la imagen no se recortará y se devolverá la URL de la imagen original.';
/** Placeholder descriptions */
$_lang['imageplus.placeholder.url'] = 'URL del archivo de recorte';
$_lang['imageplus.placeholder.alt'] = 'Alt text';
$_lang['imageplus.placeholder.width'] = 'Ancho de la imagen en miniatura (se ignora si es 0)';
$_lang['imageplus.placeholder.height'] = 'Altura de la imagen en miniatura (se ignora si es 0)';
$_lang['imageplus.placeholder.source.src'] = 'Ruta de acceso a la imagen de origen';
$_lang['imageplus.placeholder.source.width'] = 'Anchura de la imagen de origen';
$_lang['imageplus.placeholder.source.height'] = 'Altura de la imagen de origen';
$_lang['imageplus.placeholder.crop.width'] = 'Ancho de recorte de la imagen de origen';
$_lang['imageplus.placeholder.crop.height'] = 'Altura de recorte de la imagen de origen';
$_lang['imageplus.placeholder.crop.x'] = 'Recorte de la posición x de la imagen de origen';
$_lang['imageplus.placeholder.crop.y'] = 'Posición de recorte y de la imagen de origen';
$_lang['imageplus.placeholder.options'] = 'Cadena de opciones phpThumb para generar la imagen en miniatura';
$_lang['imageplus.placeholder.crop.options'] = 'Cadena de opciones phpThumb para generar la imagen en miniatura';
$_lang['imageplus.error.image_too_small.title'] = 'Imagen demasiado pequeña';
$_lang['imageplus.error.image_too_small.msg'] = 'La imagen seleccionada es demasiado pequeña para ser utilizada aquí. Por favor, seleccione una imagen diferente.';
$_lang['imageplus.error.image_not_found.title'] = 'Imagen no encontrada';
$_lang['imageplus.error.image_not_found.msg'] = 'La imagen no fue encontrada y no puede ser recortada. Por favor, seleccione una imagen diferente.';
