<?php
/**
 * Default Lexicon Entries for Image+
 *
 * @package imageplus
 * @subpackage lexicon
 */
$_lang['imageplus'] = 'Image+';
$_lang['imageplus.editor_title'] = 'Редактор Image+';
$_lang['imageplus.alt_text'] = 'Альтернатиный текст (alt)';
$_lang['imageplus.caption'] = 'Заголовок';
$_lang['imageplus.credits'] = 'Авторы';
/** Input options render **/
$_lang['imageplus.input_section'] = 'Image+ параметры';
$_lang['imageplus.input_section_desc'] = 'Следующие параметры могут быть переопределены контекстом/системными настройками. Пожалуйста, прочитайте <a href="https://jako.github.io/ImagePlus/usage/#contextsystem-settings" target="_blank">документацию</a> для соответствующих ключей в контексте/системных настройках.';
$_lang['imageplus.thumbnailHeight'] = 'Высота иконки';
$_lang['imageplus.thumbnailHeight_desc'] = '(Необязательно) Высота изображения в панели управления TV. По умолчанию: 250';
$_lang['imageplus.allowAltTag'] = 'Показывать поле для атрибута Аlt';
$_lang['imageplus.allowAltTag_desc'] = 'Позволяет пользователю ввести заголовок/альтернативный текст для изображения.';
$_lang['imageplus.allowCaption'] = 'Показать поле заголовка';
$_lang['imageplus.allowCaption_desc'] = 'Разрешить пользователю ввести подпись для изображения.';
$_lang['imageplus.allowCredits'] = 'Показать поле «Авторы»';
$_lang['imageplus.allowCredits_desc'] = 'Разрешить пользователю ввести Автора для изображения.';
/** Output options render **/
$_lang['imageplus.phpThumbParams'] = 'Дополнительные параметры phpThumb';
$_lang['imageplus.phpThumbParams_desc'] = 'Добавляет дополнительные фильтры и другие параметры для phpThumb. Документацию можно найти <a href="https://raw.githubusercontent.com/JamesHeinrich/phpThumb/master/docs/phpthumb.readme.txt" target="_blank">здесь</a>.';
$_lang['imageplus.outputChunk'] = 'Чанк для вывода';
$_lang['imageplus.outputChunk_desc'] = 'Выберите чанк для вывода TV. Оставьте пустым для обычного вывода текстом.';
$_lang['imageplus.generateUrl'] = 'Генерировать Thumb URL';
$_lang['imageplus.generateUrl_desc'] = '(Необязательно) URL иконки может быть необязательным, если вы генерируете иконку в чанке, т.е. когда phpThumb используется как output filter.';
$_lang['imageplus.generateUrl_desc_warning'] = 'Вы должны активировать эту опцию, когда вы не указываете выходной блок в опциях вывода или когда вы используете заполнитель [[+url]] в указанном выходном блоке. В противном случае изображение не обрезается/не изменяется, и возвращается исходный путь к изображению.';
/** Placeholder descriptions */
$_lang['imageplus.placeholder.url'] = 'URL иконки изображения';
$_lang['imageplus.placeholder.alt'] = 'Альтернатиный текст (alt)';
$_lang['imageplus.placeholder.width'] = 'Ширина иконки изображения (игнорируется когда 0)';
$_lang['imageplus.placeholder.height'] = 'Высота иконки изображения (игнорируется когда 0)';
$_lang['imageplus.placeholder.source.src'] = 'Путь к исходному изображению';
$_lang['imageplus.placeholder.source.width'] = 'Ширина исходного изображения';
$_lang['imageplus.placeholder.source.height'] = 'Высота исходного изображения';
$_lang['imageplus.placeholder.crop.width'] = 'Ширина обрезки исходного изображения';
$_lang['imageplus.placeholder.crop.height'] = 'Высота обрезки исходного изображения';
$_lang['imageplus.placeholder.crop.x'] = 'Позиция обрезки исходного изображения по оси X (горизонталь)';
$_lang['imageplus.placeholder.crop.y'] = 'Позиция обрезки исходного изображения по оси Y (вертикаль)';
$_lang['imageplus.placeholder.options'] = 'Строка параметров phpThumb для генерации иконки изображения';
$_lang['imageplus.placeholder.crop.options'] = 'Строка опций обрезки phpThumb для генерации иконки изображения';
$_lang['imageplus.error.image_too_small.title'] = 'Изображение слишком маленькое';
$_lang['imageplus.error.image_too_small.msg'] = 'Выбранное изображение слишком маленькое для использования здесь. Выберите другое изображение, пожалуйста.';
$_lang['imageplus.error.image_not_found.title'] = 'Изображение не найдено';
$_lang['imageplus.error.image_not_found.msg'] = 'Изображение не найдено и не может быть обрезано. Выберите другое изображение, пожалуйста.';
