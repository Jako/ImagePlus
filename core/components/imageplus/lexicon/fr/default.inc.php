<?php
/**
 * Default Lexicon Entries for Image+
 *
 * @package imageplus
 * @subpackage lexicon
 */
$_lang['imageplus'] = 'Image+';
$_lang['imageplus.editor_title'] = 'Éditeur Image+';
$_lang['imageplus.alt_text'] = 'Texte de l’attribut Alt';
$_lang['imageplus.caption'] = 'Légende';
$_lang['imageplus.credits'] = 'Crédits';
/** Input options render **/
$_lang['imageplus.input_section'] = 'Options d’Image+';
$_lang['imageplus.input_section_desc'] = 'Les options suivantes pourraient être remplacées par des paramètres de contexte/système. Veuillez lire la <a href="https://jako.github.io/ImagePlus/usage/#contextsystem-settings" target="_blank"> documentation </a> pour connaître les clés appropriées dans les paramètres du contexte/système.';
$_lang['imageplus.minWidth'] = 'Largeur minimale de l’image';
$_lang['imageplus.minWidth_desc'] = '(Optionnel, entier) La largeur minimale de l’image sélectionnée ou téléversée en pixels.';
$_lang['imageplus.minHeight'] = 'Hauteur minimale de l’image';
$_lang['imageplus.minHeight_desc'] = '(Optionnel, entier) La hauteur minimale de l’image sélectionnée ou téléversée en pixels.';
$_lang['imageplus.thumbnailHeight'] = 'Hauteur miniature';
$_lang['imageplus.thumbnailHeight_desc'] = '(Optionnel, entier) Hauteur de la miniature dans le panneau de variable de modèle (TV). Par défaut : 250';
$_lang['imageplus.inlinePreview'] = 'Afficher toutes les images sur une ligne';
$_lang['imageplus.inlinePreview_desc'] = '(Optionnel, Oui/Non) Affiche aussi les recadrages et l’image originale sur une ligne, lorsque le panneau de variable de modèle (TV) a une largeur d’au moins 700 pixels. Par défaut : Oui';
$_lang['imageplus.allowAltTag'] = 'Afficher le champ tag alt';
$_lang['imageplus.allowAltTag_desc'] = 'Permettre à l’utilisateur à entrer un titre/tag alt pour l’image.';
$_lang['imageplus.allowCaption'] = 'Afficher le champ de légende';
$_lang['imageplus.allowCaption_desc'] = 'Permettre à l’utilisateur d’entrer une légende pour l’image.';
$_lang['imageplus.allowCredits'] = 'Afficher le champ de crédits';
$_lang['imageplus.allowCredits_desc'] = 'Permettre à l’utilisateur d’entrer un crédit pour l’image.';
/** Output options render **/
$_lang['imageplus.phpThumbParams'] = 'Paramètres phpThumb additionnels';
$_lang['imageplus.phpThumbParams_desc'] = '(Optionnel) Paramètres phpThumb pour le placeholder options du chunk de sortie, par exemple pour un filtre pthumb. La documentation est disponible <a href="https://raw.githubusercontent.com/JamesHeinrich/phpThumb/master/docs/phpthumb.readme.txt" target="_blank">ici</a>.';
$_lang['imageplus.outputChunk'] = 'Chunk d’affichage';
$_lang['imageplus.outputChunk_desc'] = 'Sélectionnez le chunk utilisé pour afficher le résultat de la TV. Laissez vide pour obtenir l’URL brute du résultat.';
$_lang['imageplus.generateUrl'] = 'Utiliser l’URL du fichier de recadrage';
$_lang['imageplus.generateUrl_desc'] = '(Optionnel) L’URL du fichier de recadrage n’est peut-être pas nécessaire, si vous générez la miniature dans le chunk de sortie (exemple par un filtre pthumb).';
$_lang['imageplus.generateUrl_desc_warning'] = 'Vous devez activer cette option lorsque vous ne spécifiez pas de morceau de sortie dans les options de sortie ou lorsque vous utilisez le caractère de remplacement [[+url]] dans le morceau de sortie spécifié. Sinon, l’image n’est pas recadrée et l’URL de l’image originale est renvoyée.';
/** Placeholder descriptions */
$_lang['imageplus.placeholder.url'] = 'URL du fichier de recadrage';
$_lang['imageplus.placeholder.alt'] = 'Texte de l’attribut Alt';
$_lang['imageplus.placeholder.width'] = 'Largeur de l’image miniature (ignorée quand 0)';
$_lang['imageplus.placeholder.height'] = 'Hauteur de l’image miniature (ignorée quand 0)';
$_lang['imageplus.placeholder.source.src'] = 'Chemin de l’image source';
$_lang['imageplus.placeholder.source.width'] = 'Largeur de l’image source';
$_lang['imageplus.placeholder.source.height'] = 'Hauteur de l’image source';
$_lang['imageplus.placeholder.crop.width'] = 'Largeur de l’image source recadrée';
$_lang['imageplus.placeholder.crop.height'] = 'Hauteur de l’image source recadrée';
$_lang['imageplus.placeholder.crop.x'] = 'Position recadrage en x de l’image source';
$_lang['imageplus.placeholder.crop.y'] = 'Position recadrage en y de l’image source';
$_lang['imageplus.placeholder.options'] = 'chaîne d’option phpThumb pour générer l’image miniature';
$_lang['imageplus.placeholder.crop.options'] = 'chaîne d’option de recadrage phpThumb pour générer l’image miniature';
$_lang['imageplus.error.image_too_small.title'] = 'Image trop petite';
$_lang['imageplus.error.image_too_small.msg'] = 'L’image sélectionnée est trop petite pour être utilisé ici. Veuillez sélectionner une image différente.';
$_lang['imageplus.error.image_not_found.title'] = 'Image non trouvée';
$_lang['imageplus.error.image_not_found.msg'] = 'L’image est introuvable et ne peut être recadrée. Veuillez sélectionner une image différente.';
/** Image crops **/
$_lang['imageplus.sizes'] = 'Recadrages d’image';
$_lang['imageplus.sizes_desc'] = '(Facultatif, JSON) Un objet encodé en JSON pour les recadrages d’image, par ex. {"desktop": {"name": "Desktop", "size": "1000x500"}, "mobile": {"name": "Mobile", "proportion": "4x3"}}. Chaque recadrage peut avoir une taille (size), une proportion (proportion), une largeur (width) ou une hauteur (height). Si cette valeur est vide, un recadrage sans contrainte est utilisé.';
$_lang['imageplus.crop'] = 'Recadrage d’image';
$_lang['imageplus.crop_desc'] = '(Facultatif) La clé du recadrage d’image utilisé pour la sortie. Laisser vide pour utiliser le premier recadrage.';
$_lang['imageplus.placeholder.crop.url'] = 'URL du fichier de recadrage créé dans le manager';
/** Crop window **/
$_lang['imageplus.cropimage'] = 'Recadrer l’image';
$_lang['imageplus.cropimage.changes.content'] = 'Il y a des changements non sauvés, voulez-vous continuer ?';
$_lang['imageplus.cropimage.changes.title'] = 'Avertissement';
$_lang['imageplus.cropimage.changes_saved'] = 'Modifications enregistrées';
$_lang['imageplus.cropimage.changes_unsaved'] = 'Il y a des changements non sauvés.';
$_lang['imageplus.cropimage.crop'] = 'Recadrer l’image';
$_lang['imageplus.cropimage.crop.height'] = 'Hauteur';
$_lang['imageplus.cropimage.crop.proportion'] = 'Ratio';
$_lang['imageplus.cropimage.crop.width'] = 'Largeur';
$_lang['imageplus.cropimage.crop.x'] = 'Gauche';
$_lang['imageplus.cropimage.crop.y'] = 'Haut de page';
$_lang['imageplus.cropimage.default'] = 'Original';
$_lang['imageplus.cropimage.error_crop'] = 'Erreur lors de la création d’un fichier de recadrage d’image.';
$_lang['imageplus.cropimage.error_cropheight_cropwidth'] = 'La valeur de cropWidth ou cropHeight est vide.';
$_lang['imageplus.cropimage.error_file_not_set'] = 'Aucun fichier n’est spécifié.';
$_lang['imageplus.cropimage.error_image'] = 'Le recadrage de l’image n’a pas pu être enregistré.';
$_lang['imageplus.cropimage.error_image_not_exists'] = 'L’image n’existe pas.';
$_lang['imageplus.cropimage.error_image_not_valid'] = 'L’image n’est pas un fichier valide.';
$_lang['imageplus.cropimage.error_min_width'] = 'L’image a une largeur de [[+width]] pixels, mais elle doit avoir une largeur d’au moins [[+min_width]] pixels.';
$_lang['imageplus.cropimage.error_min_height'] = 'L’image a une hauteur de [[+height]] pixels, mais elle doit avoir une hauteur d’au moins [[+min_height]] pixels.';
$_lang['imageplus.cropimage.error_memory'] = 'L’image ([[+width]] x [[+height]] pixels) est trop grande pour être recadrée. Le recadrage nécessite environ [[+memory]] de mémoire, mais la limite memory_limit de PHP est de [[+limit]] et ne peut pas être augmentée. Veuillez télécharger une image plus petite ou demander à l’administrateur du serveur d’augmenter la valeur memory_limit.';
$_lang['imageplus.cropimage.original'] = 'Image originale';
$_lang['imageplus.cropimage.original.download'] = 'Télécharger l’image originale';
$_lang['imageplus.cropimage.original.height'] = 'Hauteur';
$_lang['imageplus.cropimage.original.width'] = 'Largeur';
$_lang['imageplus.cropimage.reset'] = 'Réinitialiser';
$_lang['imageplus.cropimage.save'] = 'Enregistrer le recadrage de l’image';
$_lang['imageplus.cropimage.saving'] = 'Enregistrement du recadrage de l’image …';
$_lang['imageplus.cropimage.sets'] = 'Sélectionner le recadrage de l’image';
$_lang['imageplus.cropimage.success_image'] = 'Recadrage de l’image sauvegardé';
$_lang['imageplus.cropimage.target'] = 'Image cible';
$_lang['imageplus.cropimage.target.height'] = 'Hauteur';
$_lang['imageplus.cropimage.target.width'] = 'Largeur';
$_lang['imageplus.cropimage.unset.content'] = 'Certaines images ne sont pas créées, voulez-vous continuer ?';
$_lang['imageplus.cropimage.unset.title'] = 'Avertissement';
$_lang['imageplus.cropimage.select_file'] = 'Sélectionner un fichier …';
