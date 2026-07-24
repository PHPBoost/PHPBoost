/**
 * Drag and drop jQuery plugin - Version: 2.1
 * @copyright   &copy; 2005-2026 PHPBoost - 2019 babsolune
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1
*/

(function($) {

    $.fn.extend({
        dndfiles: function(options) {
            var settings = {
                filesNbr: '.files-nbr',
                filesList: '.ulist',
                multiple: false,
                maxFileSize: 500000000, // 500Mo
                maxFilesSize: -1,
                maxWidth: -1,
                maxHeight: -1,
                allowedExtensions: ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'],
                warningText: 'Upload désactivé en raison de fichier(s) invalide(s) :',
                warningExtension: ' extension non autorisée <br />',
                warningFileSize: ' Fichier trop lourd <br />',
                warningFilesNbr: ' L espace total alloué est dépassé <br />',
                warningFilesDim: ' Les dimensions dépassent le maximum autorisé <br />',
                progressBarGlobal: '.upload-progress-global',
                progressBarItem: true
            };
            var param = $.extend(settings, options);

            return this.each(function() {
                var $input = $(this);
                var $form = $input.closest('form');
                var $dndArea = $input.closest('.dnd-area');
                var $filesList = $dndArea.siblings(param.filesList);
                var $warningContainer = $dndArea.find('label .d-block');
                var $uploadHelp = $dndArea.find('.upload-help');
                var $submitBtn = $form.find('button[type="submit"]');
                
                // Conteneur interne DataTransfer pour cumuler / filtrer les fichiers
                var dataTransfer = new DataTransfer();

                if (param.multiple) {
                    $input.attr('multiple', 'multiple');
                    $dndArea.find(param.filesNbr).html('0');
                }

                // Drag & Drop visual feedback
                $input.on('dragover mouseover', function() { $input.closest('.dnd-dropzone').addClass('dragover'); });
                $input.on('dragleave drop mouseleave', function() { $input.closest('.dnd-dropzone').removeClass('dragover'); });

                function formatBytes(bytes, decimals) {
                    if (bytes === 0) return "0 Bytes";
                    var k = 1024,
                        dm = decimals || 2,
                        sizes = ["Bytes", "KB", "MB", "GB", "TB"],
                        i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i];
                }

                // Synchronise l'input HTML avec l'objet DataTransfer interne
                function syncInputFiles() {
                    $input[0].files = dataTransfer.files;
                    renderList();
                }

                // Rendu UI de la liste de fichiers et des avertissements
                function renderList() {
                    $filesList.empty();
                    $warningContainer.html('').removeClass('message-helper bgc warning small');
                    $uploadHelp.removeClass('warning');
                    $submitBtn.prop("disabled", false);

                    var files = dataTransfer.files;
                    var filesNbr = files.length;
                    var totalWeight = 0;
                    var hasError = false;
                    var warningMessages = '';

                    if (param.multiple) {
                        $dndArea.find(param.filesNbr).html(filesNbr);
                    }

                    if (filesNbr > 0) {
                        $('.clear-list').css('display', 'inline-block');
                    } else {
                        $('.clear-list').css('display', 'none');
                        return; // Rien à afficher si la liste est vide
                    }

                    $.each(files, function(i, file) {
                        var fileName = file.name;
                        var fileSize = file.size;
                        var fileType = file.type;
                        var ext = fileName.indexOf('.') !== -1 ? fileName.split('.').pop().toLowerCase() : '';
                        var isWarning = false;

                        totalWeight += fileSize;

                        // Validation Extension & Taille
                        if (param.allowedExtensions.indexOf(ext) === -1) {
                            isWarning = true;
                            warningMessages += '<span class="filename">' + fileName + '</span> : ' + param.warningExtension;
                        }
                        if (fileSize > param.maxFileSize) {
                            isWarning = true;
                            warningMessages += '<span class="filename">' + fileName + '</span> : ' + param.warningFileSize;
                        }

                        if (isWarning) hasError = true;

                        // Validation Dimensions si Image
                        if (fileType.indexOf('image/') === 0) {
                            var img = new Image();
                            img.onload = function() {
                                if ((param.maxWidth > -1 && this.width > param.maxWidth) || 
                                    (param.maxHeight > -1 && this.height > param.maxHeight)) {
                                    $submitBtn.prop('disabled', true);
                                    $uploadHelp.addClass('warning');
                                    $warningContainer.addClass('message-helper bgc warning small')
                                                     .append('<span class="filename">' + fileName + '</span> : ' + param.warningFilesDim);
                                }
                            };
                            img.src = URL.createObjectURL(file);
                        }

                        // Icône selon le type
                        var icon = 'fa-file';
                        if (fileType.indexOf('image/') === 0) icon = 'fa-file-image';
                        else if (fileType.indexOf('audio/') === 0) icon = 'fa-file-audio';
                        else if (fileType.indexOf('video/') === 0) icon = 'fa-file-video';
                        else if (fileType.indexOf('application/zip') === 0) icon = 'fa-file-archive';
                        else if (fileType.indexOf('application/pdf') === 0) icon = 'fa-file-pdf';

                        var warningClass = isWarning ? 'warning' : '';
                        var preview = fileType.indexOf('image/') === 0 ? 
                            '<img src="' + URL.createObjectURL(file) + '" /> ' : 
                            '<i class="far ' + icon + '"></i> ';

                        var progressHtml = param.progressBarItem ? 
                            '<div class="file-progress"><div class="file-progress-bar" style="width:0%"></div></div>' : '';

                        var $li = $('<li class="' + warningClass + '" data-index="' + i + '">' +
                            preview + fileName + ' <sup>' + formatBytes(fileSize) + '</sup>' +
                            progressHtml +
                            '<span class="fa fa-times-circle fa-lg close-item-file"></span>' +
                            '</li>');

                        // Suppression d'un fichier individuel
                        $li.find('.close-item-file').on('click', function() {
                            removeFile(i);
                        });

                        $filesList.append($li);
                    });

                    // Vérification du poids global
                    if (param.maxFilesSize > -1 && totalWeight > param.maxFilesSize) {
                        hasError = true;
                        warningMessages += param.warningFilesNbr;
                    }

                    // Affichage groupé des erreurs s'il y en a
                    if (hasError) {
                        $submitBtn.prop('disabled', true);
                        $uploadHelp.addClass('warning');
                        $warningContainer.addClass('message-helper bgc warning small')
                                         .html(param.warningText + '<br/>' + warningMessages);
                    }
                }

                // Ajout de nouveaux fichiers au DataTransfer
                function addFiles(newFiles) {
                    if (!param.multiple) {
                        dataTransfer = new DataTransfer(); // Si simple upload, on remplace
                    }
                    $.each(newFiles, function(i, file) {
                        dataTransfer.items.add(file);
                    });
                    syncInputFiles();
                }

                // Suppression d'un fichier du DataTransfer
                function removeFile(index) {
                    var newDataTransfer = new DataTransfer();
                    $.each(dataTransfer.files, function(i, file) {
                        if (i !== index) {
                            newDataTransfer.items.add(file);
                        }
                    });
                    dataTransfer = newDataTransfer;
                    syncInputFiles();
                }

                // Événement d'ajout via la zone de sélection / drag drop
                $input.on('change', function() {
                    if (this.files.length > 0) {
                        addFiles(this.files);
                    }
                });

                // Vider toute la liste
                $('.clear-list').on('click', function(e) {
                    e.preventDefault();
                    dataTransfer = new DataTransfer();
                    syncInputFiles();
                });

                // Gestion de la soumission AJAX avec progression
                $form.on('submit', function(e) {
                    if (dataTransfer.files.length === 0) return;

                    e.preventDefault();
                    var formData = new FormData(this);

                    $.ajax({
                        url: $form.attr('action') || window.location.href,
                        type: $form.attr('method') || 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        xhr: function() {
                            var xhr = $.ajaxSettings.xhr();
                            if (xhr.upload) {
                                xhr.upload.addEventListener('progress', function(e) {
                                    if (e.lengthComputable) {
                                        var percent = Math.round((e.loaded / e.total) * 100);

                                        if ($(param.progressBarGlobal).length) {
                                            $(param.progressBarGlobal).css('width', percent + '%').text(percent + '%');
                                        }

                                        if (param.progressBarItem) {
                                            $filesList.find('.file-progress-bar').css('width', percent + '%');
                                        }
                                    }
                                }, false);
                            }
                            return xhr;
                        },
                        success: function(response) {
                            window.location.reload();
                        },
                        error: function() {
                            alert('Une erreur est survenue lors du téléchargement.');
                        }
                    });
                });

            });
        }
    });
})(jQuery);