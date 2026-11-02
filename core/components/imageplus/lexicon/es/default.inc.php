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
$_lang['imageplus.minWidth'] = 'Ancho mínimo de la imagen';
$_lang['imageplus.minWidth_desc'] = '(Opcional, entero) El ancho mínimo de la imagen seleccionada o subida en píxeles.';
$_lang['imageplus.minHeight'] = 'Altura mínima de la imagen';
$_lang['imageplus.minHeight_desc'] = '(Opcional, entero) La altura mínima de la imagen seleccionada o subida en píxeles.';
$_lang['imageplus.thumbnailHeight'] = 'Altura de las miniaturas';
$_lang['imageplus.thumbnailHeight_desc'] = '(Opcional, Entero) La altura de la miniatura de la imagen en el panel de variables de la plantilla. Predeterminado: 250';
$_lang['imageplus.inlinePreview'] = 'Mostrar todas las imágenes en una línea';
$_lang['imageplus.inlinePreview_desc'] = '(Opcional, Sí/No) Mostrar los recortes y la imagen original en una línea, cuando el panel de la variable de plantilla tiene al menos 700 píxeles de ancho. Por defecto: Sí';
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
/** Image crops **/
$_lang['imageplus.sizes'] = 'Recortes de imagen';
$_lang['imageplus.sizes_desc'] = '(Opcional, JSON) Un objeto codificado en JSON para los recortes de imagen, p. ej. {"desktop": {"name": "Desktop", "size": "1000x500"}, "mobile": {"name": "Mobile", "proportion": "4x3"}}. Cada recorte puede tener un tamaño (size), una proporción (proportion), un ancho (width) o una altura (height). Si este valor está vacío, se usa un recorte de imagen sin restricciones.';
$_lang['imageplus.crop'] = 'Recorte de imagen';
$_lang['imageplus.crop_desc'] = '(Opcional) La clave del recorte de imagen usado para la salida. Déjelo vacío para usar el primer recorte de imagen.';
$_lang['imageplus.placeholder.crop.url'] = 'URL del archivo de recorte creado en el manager';
/** Crop window **/
$_lang['imageplus.cropimage'] = 'Recortar imagen';
$_lang['imageplus.cropimage.changes.content'] = 'Hay cambios sin guardar, ¿desea continuar?';
$_lang['imageplus.cropimage.changes.title'] = 'Advertencia';
$_lang['imageplus.cropimage.changes_saved'] = 'Cambios guardados';
$_lang['imageplus.cropimage.changes_unsaved'] = 'Hay cambios sin guardar.';
$_lang['imageplus.cropimage.crop'] = 'Recortar imagen';
$_lang['imageplus.cropimage.crop.height'] = 'Altura';
$_lang['imageplus.cropimage.crop.proportion'] = 'Proporción';
$_lang['imageplus.cropimage.crop.width'] = 'Ancho';
$_lang['imageplus.cropimage.crop.x'] = 'Izquierda';
$_lang['imageplus.cropimage.crop.y'] = 'Arriba';
$_lang['imageplus.cropimage.default'] = 'Original';
$_lang['imageplus.cropimage.error_crop'] = 'Error al crear el archivo de recorte.';
$_lang['imageplus.cropimage.error_cropheight_cropwidth'] = 'El valor de cropWidth o cropHeight está vacío.';
$_lang['imageplus.cropimage.error_file_not_set'] = 'No se ha indicado ningún archivo.';
$_lang['imageplus.cropimage.error_image'] = 'No se pudo guardar el recorte de imagen.';
$_lang['imageplus.cropimage.error_image_not_exists'] = 'La imagen no existe.';
$_lang['imageplus.cropimage.error_image_not_valid'] = 'La imagen no es un archivo válido.';
$_lang['imageplus.cropimage.error_min_width'] = 'La imagen tiene [[+width]] píxeles de ancho, pero debe tener al menos [[+min_width]] píxeles de ancho.';
$_lang['imageplus.cropimage.error_min_height'] = 'La imagen tiene [[+height]] píxeles de alto, pero debe tener al menos [[+min_height]] píxeles de alto.';
$_lang['imageplus.cropimage.error_memory'] = 'La imagen ([[+width]] x [[+height]] píxeles) es demasiado grande para recortarla. Recortarla necesita unos [[+memory]] de memoria, pero el memory_limit de PHP es [[+limit]] y no se puede aumentar. Suba una imagen más pequeña o pida al administrador del servidor que aumente el memory_limit.';
$_lang['imageplus.cropimage.original'] = 'Imagen original';
$_lang['imageplus.cropimage.original.download'] = 'Descargar la imagen original';
$_lang['imageplus.cropimage.original.height'] = 'Altura';
$_lang['imageplus.cropimage.original.width'] = 'Ancho';
$_lang['imageplus.cropimage.reload'] = 'Recargar imagen';
$_lang['imageplus.cropimage.reload.content'] = 'La imagen se carga de nuevo y todos los recortes guardados se crean de nuevo a partir de ella, p. ej. si la imagen se ha sustituido por una imagen modificada con el mismo nombre de archivo. Los cambios sin guardar se descartan. ¿Desea continuar?';
$_lang['imageplus.cropimage.reload.title'] = 'Recargar imagen';
$_lang['imageplus.cropimage.reload_ratio.content'] = 'La proporción de la imagen ha cambiado. Compruebe los recortes de imagen y guárdelos de nuevo si es necesario.';
$_lang['imageplus.cropimage.reload_ratio.title'] = 'Advertencia';
$_lang['imageplus.cropimage.reloading'] = 'Creando de nuevo los recortes de imagen …';
$_lang['imageplus.cropimage.reset'] = 'Deshacer cambios';
$_lang['imageplus.cropimage.save'] = 'Guardar recorte';
$_lang['imageplus.cropimage.saving'] = 'Guardando el recorte de imagen …';
$_lang['imageplus.cropimage.select_file'] = 'Seleccionar archivo …';
$_lang['imageplus.cropimage.sets'] = 'Seleccionar recorte de imagen';
$_lang['imageplus.cropimage.success_image'] = 'Recorte de imagen guardado';
$_lang['imageplus.cropimage.target'] = 'Imagen de destino';
$_lang['imageplus.cropimage.target.height'] = 'Altura';
$_lang['imageplus.cropimage.target.width'] = 'Ancho';
$_lang['imageplus.cropimage.unset.content'] = 'Algunos recortes de imagen no se han creado, ¿desea continuar?';
$_lang['imageplus.cropimage.unset.title'] = 'Advertencia';
