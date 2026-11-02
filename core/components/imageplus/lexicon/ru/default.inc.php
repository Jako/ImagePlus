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
$_lang['imageplus.minWidth'] = 'Минимальная ширина изображения';
$_lang['imageplus.minWidth_desc'] = '(Необязательно, целое число) Минимальная ширина выбранного или загруженного изображения в пикселях.';
$_lang['imageplus.minHeight'] = 'Минимальная высота изображения';
$_lang['imageplus.minHeight_desc'] = '(Необязательно, целое число) Минимальная высота выбранного или загруженного изображения в пикселях.';
$_lang['imageplus.thumbnailHeight'] = 'Высота иконки';
$_lang['imageplus.thumbnailHeight_desc'] = '(Необязательно) Высота изображения в панели управления TV. По умолчанию: 250';
$_lang['imageplus.inlinePreview'] = 'Показывать все изображения в одну строку';
$_lang['imageplus.inlinePreview_desc'] = '(Необязательно, Да/Нет) Показывать обрезки и исходное изображение в одну строку, если панель TV имеет ширину не менее 700 пикселей. По умолчанию: Да';
$_lang['imageplus.allowAltTag'] = 'Показывать поле для атрибута Аlt';
$_lang['imageplus.allowAltTag_desc'] = 'Позволяет пользователю ввести заголовок/альтернативный текст для изображения.';
$_lang['imageplus.allowCaption'] = 'Показать поле заголовка';
$_lang['imageplus.allowCaption_desc'] = 'Разрешить пользователю ввести подпись для изображения.';
$_lang['imageplus.allowCredits'] = 'Показать поле «Авторы»';
$_lang['imageplus.allowCredits_desc'] = 'Разрешить пользователю ввести Автора для изображения.';
/** Output options render **/
$_lang['imageplus.phpThumbParams'] = 'Дополнительные параметры phpThumb';
$_lang['imageplus.phpThumbParams_desc'] = '(Необязательно) Параметры phpThumb для плейсхолдера options в чанке вывода, например для output filter pthumb. Документацию можно найти <a href="https://raw.githubusercontent.com/JamesHeinrich/phpThumb/master/docs/phpthumb.readme.txt" target="_blank">здесь</a>.';
$_lang['imageplus.outputChunk'] = 'Чанк для вывода';
$_lang['imageplus.outputChunk_desc'] = 'Выберите чанк для вывода TV. Оставьте пустым для обычного вывода текстом.';
/** Placeholder descriptions */
$_lang['imageplus.placeholder.url'] = 'URL файла обрезки';
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
$_lang['imageplus.placeholder.options'] = 'Строка параметров phpThumb для генерации миниатюры изображения';
$_lang['imageplus.placeholder.crop.options'] = 'Строка опций обрезки phpThumb для генерации иконки изображения';
$_lang['imageplus.error.image_too_small.title'] = 'Изображение слишком маленькое';
$_lang['imageplus.error.image_too_small.msg'] = 'Выбранное изображение слишком маленькое для использования здесь. Выберите другое изображение, пожалуйста.';
$_lang['imageplus.error.image_not_found.title'] = 'Изображение не найдено';
$_lang['imageplus.error.image_not_found.msg'] = 'Изображение не найдено и не может быть обрезано. Выберите другое изображение, пожалуйста.';
/** Image crops **/
$_lang['imageplus.sizes'] = 'Обрезки изображения';
$_lang['imageplus.sizes_desc'] = '(Необязательно, JSON) JSON-объект для обрезок изображения, например {"desktop": {"name": "Desktop", "size": "1000x500"}, "mobile": {"name": "Mobile", "proportion": "4x3"}}. Каждая обрезка может иметь размер (size), пропорцию (proportion), ширину (width) или высоту (height). Если значение пустое, используется одна обрезка без ограничений.';
$_lang['imageplus.crop'] = 'Обрезка изображения';
$_lang['imageplus.crop_desc'] = '(Необязательно) Ключ обрезки изображения, используемой для вывода. Оставьте пустым, чтобы использовать первую обрезку.';
$_lang['imageplus.placeholder.crop.url'] = 'URL файла обрезки, созданного в менеджере';
/** Crop window **/
$_lang['imageplus.cropimage'] = 'Обрезать изображение';
$_lang['imageplus.cropimage.changes.content'] = 'Есть несохранённые изменения. Продолжить?';
$_lang['imageplus.cropimage.changes.title'] = 'Предупреждение';
$_lang['imageplus.cropimage.changes_saved'] = 'Изменения сохранены';
$_lang['imageplus.cropimage.changes_unsaved'] = 'Есть несохранённые изменения.';
$_lang['imageplus.cropimage.crop'] = 'Обрезать изображение';
$_lang['imageplus.cropimage.crop.height'] = 'Высота';
$_lang['imageplus.cropimage.crop.proportion'] = 'Пропорция';
$_lang['imageplus.cropimage.crop.width'] = 'Ширина';
$_lang['imageplus.cropimage.crop.x'] = 'Слева';
$_lang['imageplus.cropimage.crop.y'] = 'Сверху';
$_lang['imageplus.cropimage.default'] = 'Оригинал';
$_lang['imageplus.cropimage.error_crop'] = 'Ошибка при создании файла обрезки.';
$_lang['imageplus.cropimage.error_cropheight_cropwidth'] = 'Значение cropWidth или cropHeight пустое.';
$_lang['imageplus.cropimage.error_file_not_set'] = 'Файл не указан.';
$_lang['imageplus.cropimage.error_image'] = 'Не удалось сохранить обрезку изображения.';
$_lang['imageplus.cropimage.error_image_not_exists'] = 'Изображение не существует.';
$_lang['imageplus.cropimage.error_image_not_valid'] = 'Изображение не является допустимым файлом.';
$_lang['imageplus.cropimage.error_min_width'] = 'Ширина изображения [[+width]] пикселей, но должна быть не менее [[+min_width]] пикселей.';
$_lang['imageplus.cropimage.error_min_height'] = 'Высота изображения [[+height]] пикселей, но должна быть не менее [[+min_height]] пикселей.';
$_lang['imageplus.cropimage.error_memory'] = 'Изображение ([[+width]] x [[+height]] пикселей) слишком большое для обрезки. Для обрезки нужно около [[+memory]] памяти, но memory_limit PHP равен [[+limit]] и не может быть увеличен. Загрузите изображение меньшего размера или попросите администратора сервера увеличить memory_limit.';
$_lang['imageplus.cropimage.original'] = 'Исходное изображение';
$_lang['imageplus.cropimage.original.download'] = 'Скачать исходное изображение';
$_lang['imageplus.cropimage.original.height'] = 'Высота';
$_lang['imageplus.cropimage.original.width'] = 'Ширина';
$_lang['imageplus.cropimage.reload'] = 'Перезагрузить изображение';
$_lang['imageplus.cropimage.reload.content'] = 'Изображение загружается заново, и все сохранённые обрезки создаются из него заново, например если изображение было заменено изменённым изображением с тем же именем файла. Несохранённые изменения будут потеряны. Продолжить?';
$_lang['imageplus.cropimage.reload.title'] = 'Перезагрузить изображение';
$_lang['imageplus.cropimage.reload_ratio.content'] = 'Соотношение сторон изображения изменилось. Проверьте обрезки изображения и при необходимости сохраните их заново.';
$_lang['imageplus.cropimage.reload_ratio.title'] = 'Предупреждение';
$_lang['imageplus.cropimage.reloading'] = 'Обрезки изображения создаются заново …';
$_lang['imageplus.cropimage.reset'] = 'Отменить изменения';
$_lang['imageplus.cropimage.save'] = 'Сохранить обрезку';
$_lang['imageplus.cropimage.saving'] = 'Сохранение обрезки изображения …';
$_lang['imageplus.cropimage.select_file'] = 'Выбрать файл …';
$_lang['imageplus.cropimage.sets'] = 'Выберите обрезку изображения';
$_lang['imageplus.cropimage.success_image'] = 'Обрезка изображения сохранена';
$_lang['imageplus.cropimage.target'] = 'Целевое изображение';
$_lang['imageplus.cropimage.target.height'] = 'Высота';
$_lang['imageplus.cropimage.target.width'] = 'Ширина';
$_lang['imageplus.cropimage.unset.content'] = 'Некоторые обрезки изображения не созданы. Продолжить?';
$_lang['imageplus.cropimage.unset.title'] = 'Предупреждение';
