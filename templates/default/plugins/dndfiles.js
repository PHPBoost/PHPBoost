/**
 * Drag and drop jQuery plugin - Version: 1.0
 * @copyright   &copy; 2005-2020 PHPBoost - 2019 babsolune
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babso@labsoweb.fr>
 * @version     PHPBoost 5.3 - last update: 2019 10 03
 * @since       PHPBoost 5.3 - 2019 09 23
*/

(function($) {

    $.fn.extend({
        dndfiles: function(options) {
            // set the default parameters
            var settings = {
                filesNbr: '.files-nbr', // class to display the number of selected files
                filesList: '.ulist', // class to display the list of selected files
                multiple: false, // add or not 'multiple attribut to the input'
                maxFileSize: '500000000', // weight max for one file
                maxFilesSize: '-1', // weight max for all files (-1 = unlimited)
                maxWidth: '-1', // max width for pictures  (-1 = unlimited)
                maxHeight: '-1', // max height for pictures (-1 = unlimited)
                allowedExtensions: ['jpg', 'jpeg', 'png', 'gif', 'svg'], // allowed extensions
                warningText: 'Upload have been disabled because of bad file:', // main warning text
                warningExtension: 'bad extension <br />',
                warningFileSize: 'Too large file <br />',
                warningFilesNbr: 'The size of the allocated space is exceeded <br />',
                warningFilesDim: 'The height or width of the picture exceeds the maximum allowed <br />',
            };
            param = $.extend(settings, options);

            // init vars
    		var $input = $(this),
                extension,
                fileSize,
                fileName,
                fileType,
                fileWidth,
                fileHeight;

            if(param.multiple) { // if multiple parameter is true
                $input.attr('multiple', 'multiple'); // set the attribute 'multiple' to the input
                $(param.filesNbr).html('0'); // send '0' to the frontend icon displaying the number of selected files
            }

            // change design on drag or mouse hovering
            $input.on('dragover mouseover',        function() { $input.closest('.dnd-dropzone').addClass('dragover'); });
            $input.on('dragleave drop mouseleave', function() { $input.closest('.dnd-dropzone').removeClass('dragover'); });

            // turn file size into human readable
            function formatBytes(a, b) {
                if (0 == a) return "0 Bytes";
                var c = 1024,
                    d = b || 2,
                    e = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"],
                    f = Math.floor(Math.log(a) / Math.log(c));
                return parseFloat((a / Math.pow(c, f)).toFixed(d)) + " " + e[f];
            }

    		$input.parent().on('change', function(e) { // when files are selected, the status of the input changes
                // reset
                if(param.multiple) $input.closest('.dnd-area').find(param.filesNbr).empty(); // if multiple is true => empty the display of number of files
                $input.closest('.dnd-area').siblings(param.filesList).empty(); // empty the list of selected files
                $input.closest('form').find('button[type="submit"]').prop("disabled", false); // remove the 'disabled' attribute of the upload button
                $input.closest('.dnd-area').find('label p').html(''); // empty the warning texts
                $input.closest('.dnd-area').find('label p').removeClass('message-helper bgc warning small'); // remove the warning classes
                $input.closest('.dnd-area').find('.upload-help').removeClass(' warning');

                // init vars
    			var filesNbr = $input[0].files.length, // count number of files
                    totalWeight = 0,
    				items = $input[0].files,
    				item = '';

                if ($input.attr('multiple')) { // if multiple parameter is true
                    $input.closest('.dnd-area').find(param.filesNbr).append(filesNbr); // send number of files to the icon and...
                }
                if(filesNbr > 0) $('.clear-list').css('display', 'inline-block'); // if it's > 0 then show the 'clear list' button

                // For each file selected
                for(var i=0; i < filesNbr; i++) {
                    var warningClass = '';
    				fileName = items[i].name; // get its name
					fileSize = items[i].size; // get its weight
					fileType = items[i].type; // get its mime type
                    extension = fileName.replace(/^.*\./, ''); // replace special characters in the name
                    if (extension == fileName) extension = ''; // look if extension exists
                    else extension = extension.toLowerCase(); // force extension to lowercase
                    if(fileType.indexOf('image/') === 0) {
                        var file = items[i];
                        var img = new Image();
                        img.onload = function() {
                            fileWidth = this.width;
                            fileHeight = this.height;
                            if((param.maxWidth > -1 && fileWidth > param.maxWidth) || (param.maxHeight > -1 && fileHeight > param.maxHeight))
                            {
                                $input.closest('form').find('button[type="submit"]').attr('disabled', 'disabled'); // disable the upload button
                                $input.closest('.dnd-area').find('.upload-help').addClass(' warning');
                                $input.closest('.dnd-area').find('label p').addClass('message-helper bgc warning small').append(param.warningFileDim);
                            }
                        };
                        img.src = URL.createObjectURL(file);
                    }

                    totalWeight += fileSize; // count weight of all files

                    // items list construction

                    // if an extension is not allowed or a file exceeds the max allowed or the number of files exceeds the max allowed (-1 = rights for admin = unlimited)
                    if(param.allowedExtensions.indexOf(extension) === -1 || fileSize > param.maxFileSize || (totalWeight > param.maxFilesSize && param.maxFilesSize > -1))
                    {
                        warningClass = 'warning'; // set the warning class to the item in the list
                        $input.closest('form').find('button[type="submit"]').attr('disabled', 'disabled'); // disable the upload button
                    }

                    if(param.allowedExtensions.indexOf(extension) === -1) // if an extension is not allowed
                        $input.closest('.dnd-area').find('label p').append('<span class="filename">'+fileName+'</span>', param.warningExtension); // send the extension warning text
                    if(fileSize > param.maxFileSize) // if a file exceeds the max weight allowed
                        $input.closest('.dnd-area').find('label p').append('<span class="filename">'+fileName+'</span>', param.warningFileSize); // send the weight warning text

                    // set the appropriate html depending of the mime type
    				if(fileType.indexOf('image/') === 0)
			            item += '<li class="'+warningClass+'" data-file="'+fileName+'"><img src="' + URL.createObjectURL(items[i]) + '" /> '+fileName+'&nbsp;<sup>'+formatBytes(fileSize)+'</sup><span class="fa fa-times-circle fa-lg close-item"></span></li>';
    				else if(fileType.indexOf('audio/') === 0)
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
    			$($input).closest('.dnd-area').siblings(param.filesList).append(item); // send the list to the page (bellow the dropzone)

                if($input.closest('form').find('button[type="submit"]').attr('disabled')) // if there's a wrong file
                {
                    $input.closest('.dnd-area').find('.upload-help').addClass(' warning');
                    $input.closest('.dnd-area').find('label p').addClass('message-helper bgc warning small'); // add warning classes
                    $input.closest('.dnd-area').find('label p').prepend(param.warningText); // add main warning text
                }
                if(totalWeight > param.maxFilesSize && param.maxFilesSize > -1) // if nbr of files exceeds max allowed
                    $input.closest('.dnd-area').find('label p').append(param.warningFilesNbr); // send the max file warning text

                // next feature : possibility to delete only one file in the list
                $(param.filesList).find('.close-item').each(function(){
                    $(this).on('click', function(){
                        var itemNbr = $('.ulist li:not([value="0"])').length;
                        $(this).parent().empty().val('').hide();
                        var newFilesNbr = itemNbr - 1;
                        $(param.filesNbr).empty().append(newFilesNbr);
                    });
                });

                // reset all when clicking on the 'clear list' button
                $('.clear-list').on('click', function(d) {
                    d.preventDefault(); // deactivate the default button behaviour
                    $input.val(''); // empty the input list of files
                    $input.closest('.dnd-area').find(param.filesNbr).html('0'); // reset the display of the number of files
                    $input.closest('.dnd-area').siblings(param.filesList).empty(); // empty the display of the list  of files
                    $input.closest('form').find('button[type="submit"]').prop("disabled", false); // remove the 'disabled' attribute from the upload button
                    $input.closest('.dnd-area').find('label p').html(''); // remove the warning texts
                    $input.closest('.dnd-area').find('label p').removeClass('message-helper bgc warning small'); // remove the warning classes
                    $input.closest('.dnd-area').find('.upload-help').removeClass(' warning');
                    $input.closest('.dnd-area').find('.clear-list').css('display', 'none');
                });

    		})
        }
    });
})(jQuery);
