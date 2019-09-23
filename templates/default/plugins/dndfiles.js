/**
 * Drag and drop jQuery plugin - Version: 1.0
 * @copyright 	&copy; 2005-2019 PHPBoost - 2019 babsolune
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babso@labsoweb.fr>
 * @version   	PHPBoost 5.3 - last update: 2019 09 23
 * @since   	PHPBoost 5.3 - 2019 09 23
*/

(function($) {

    $.fn.extend({
        dndfiles: function(options) {
            var settings = {
                filesNbr: '.files-nbr',
                filesList: '.ulist',
                multiple: false,
                maxFileSize: '500000000',
                maxFilesSize: '-1',
                allowedExtensions: ['jpg', 'jpeg', 'png', 'gif', 'svg'],
                warningText: 'Upload have been disabled because of bad file',
            };
            param = $.extend(settings, options);

            $(param.filesNbr).html('0');

    		var $input = $(this);
            if(param.multiple) $input.attr('multiple', 'multiple');

            $input.on('dragover mouseover', function() {
                $('.dnd-dropzone').addClass('dragover');
            });

            $input.on('dragleave drop mouseleave', function(){
                $('.dnd-dropzone').removeClass('dragover');
            });

            function formatBytes(a, b) {
                if (0 == a) return "0 Bytes";
                var c = 1024,
                    d = b || 2,
                    e = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"],
                    f = Math.floor(Math.log(a) / Math.log(c));
                return parseFloat((a / Math.pow(c, f)).toFixed(d)) + " " + e[f];
            }

    		$input.parent().on('change', function(e){
                $(param.filesNbr).empty();
                $(param.filesList).empty();
                $input.closest('form').find('button[type="submit"]').prop("disabled", false);
                $input.closest('form').find('label p').html('');

    			var filesNbr = $input[0].files.length,
                    totalWeight = 0,
                    totalNames = '',
    				items = $input[0].files,
    				item = '';

                $(param.filesNbr).append(filesNbr);
                if(filesNbr > 0) $('.clear-list').show();

                for(var i=0; i < filesNbr; i++) {
    				var fileName = items[i].name,
    					fileSize = items[i].size,
    					fileType = items[i].type,
                        warningClass = '',
                        extension = fileName.replace(/^.*\./, '');
                    if (extension == fileName) extension = '';
                    else extension = extension.toLowerCase();

                    totalWeight += fileSize;

                    if(param.allowedExtensions.indexOf(extension) === -1 || fileSize > param.maxFileSize || (totalWeight > param.maxFilesSize && param.maxFilesSize > -1))
                    {
                        warningClass = 'warning';
                        $input.closest('form').find('button[type="submit"]').attr('disabled', 'disabled');
                    }

    				if(fileType.indexOf('image/') === 0)
    				{
                        item += '<li class="'+warningClass+'" data-file="'+fileName+'"><img src="' + URL.createObjectURL(items[i]) + '" /> '+fileName+'&nbsp;<sup>'+formatBytes(fileSize)+'</sup><span class="fa fa-times-circle fa-lg close-item"></span></li>';
    				} else if(fileType.indexOf('audio/') === 0)
    					item += '<li class="'+warningClass+'" data-file="'+fileName+'"><i class="far fa-file"></i> '+fileName+'&nbsp;<sup>'+formatBytes(fileSize)+'</sup><span class="fa fa-times-circle fa-lg close-item"></span></li>';
    				else if(fileType.indexOf('video/') === 0)
    					item += '<li class="'+warningClass+'" data-file="'+fileName+'"><i class="far fa-file"></i> '+fileName+'&nbsp;<sup>'+formatBytes(fileSize)+'</sup><span class="fa fa-times-circle fa-lg close-item"></span></li>';
    				else if(fileType.indexOf('application/zip') === 0)
    					item += '<li class="'+warningClass+'" data-file="'+fileName+'"><i class="far fa-file-archive"></i> '+fileName+'&nbsp;<sup>'+formatBytes(fileSize)+'</sup><span class="fa fa-times-circle fa-lg close-item"></span></li>';
    				else if(fileType.indexOf('application/pdf') === 0)
    					item += '<li class="'+warningClass+'" data-file="'+fileName+'"><i class="far fa-file-pdf"></i> '+fileName+'&nbsp;<sup>'+formatBytes(fileSize)+'</sup><span class="fa fa-times-circle fa-lg close-item"></span></li>';
    				else
    					item += '<li class="'+warningClass+'" data-file="'+fileName+'"><i class="far fa-file"></i> '+fileName+'&nbsp;<sup>'+formatBytes(fileSize)+'</sup><span class="fa fa-times-circle fa-lg close-item"></span></li>';
    			}
    			$(param.filesList).append(item);

                if($input.closest('form').find('button[type="submit"]').attr('disabled'))
                    $input.closest('form').find('label p').append(param.warningText);

                $(param.filesList).find('.close-item').each(function(){
                    $(this).on('click', function(){
                        var itemNbr = $('.ulist li:not([value="0"])').length;
                        $(this).parent().empty().val('').hide();
                        var newFilesNbr = itemNbr - 1;
                        $(param.filesNbr).empty().append(newFilesNbr);
                    });
                });

                $('.clear-list').on('click', function(d) {
                    d.preventDefault();
                    $input.val('');
                    $(param.filesNbr).html('0');
                    $(param.filesList).empty();
                    $input.closest('form').find('button[type="submit"]').prop("disabled", false);
                    $input.closest('form').find('label p').html('');
                });
    		})
        }
    });
})(jQuery);
