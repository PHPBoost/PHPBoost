/**
 * Linedtextarea jQuery plugin - Version: 3.0.1
 * @copyright   &copy; 2005-2023 PHPBoost - 2010 Alan Williamson
 * @license     https://www.opensource.org/licenses/mit-license.php
 * @author      Alan Williamson
 * @link
 * @doc         https://github.com/cotenoni/jquery-linedtextarea
 * @version     PHPBoost 6.0 - last update: 2020 09 18
 * @since       PHPBoost 5.0 - 2017 03 04
 *
 */
(function($) {

	$.fn.linedtextarea = function(options) {

		// Get the Options
		var opts = $.extend({}, $.fn.linedtextarea.defaults, options);

		/*
		 * Helper function to make sure the line numbers are always
		 * kept up to the current system
		 */
		var fillOutLines = function(codeLines, h, lineNo){
			while ( (codeLines.height() - h ) <= 0 ){
				if ( lineNo == opts.selectedLine )
					codeLines.append("<div class='lineno lineselect'>" + lineNo + "</div>");
				else
					codeLines.append("<div class='lineno'>" + lineNo + "</div>");

				lineNo++;
			}
			return lineNo;
		};

		/*
		 * Iterate through each of the elements are to be applied to
		 */
		return this.each(function() {
			var lineNo = 1;
			var textarea = $(this);

			/* Turn off the wrapping of as we don't want to screw up the line numbers */
			textarea.attr("wrap", "off");
			textarea.css({resize:'none'});
			var originalTextAreaWidth	= textarea.outerWidth();

			/* Wrap the text area in the elements we need */
			textarea.wrap("<div class='linedtextarea'></div>");
			var linedTextAreaDiv	= textarea.parent().wrap("<div class='linedwrap'></div>");
			var linedWrapDiv 		= linedTextAreaDiv.parent();

			linedWrapDiv.prepend("<div class='lines' style='width:50px'></div>");

			var linesDiv	= linedWrapDiv.find(".lines");
			linedWrapDiv.height( textarea.height() + 6 );


			/* Draw the number bar; filling it out where necessary */
			linesDiv.append( "<div class='codelines'></div>" );
			var codeLinesDiv	= linesDiv.find(".codelines");
			lineNo = fillOutLines( codeLinesDiv, linesDiv.height(), 1 );

			/* Move the textarea to the selected line */
			if ( opts.selectedLine != -1 && !isNaN(opts.selectedLine) ){
				var fontSize = parseInt( textarea.height() / (lineNo-2) );
				var position = parseInt( fontSize * opts.selectedLine ) - (textarea.height()/2);
				textarea[0].scrollTop = position;
			}

			/* Set the width */
			var sidebarWidth			= linesDiv.outerWidth();
			var paddingHorizontal 		= parseInt( linedWrapDiv.css("border-left-width") ) + parseInt( linedWrapDiv.css("border-right-width") ) + parseInt( linedWrapDiv.css("padding-left") ) + parseInt( linedWrapDiv.css("padding-right") );
			var linedWrapDivNewWidth 	= originalTextAreaWidth - paddingHorizontal;
			var textareaNewWidth		= originalTextAreaWidth - sidebarWidth - paddingHorizontal - 20;

			/* React to the scroll event */
			textarea.scroll( function(tn){
				var domTextArea		= $(this)[0];
				var scrollTop 		= domTextArea.scrollTop;
				var clientHeight 	= domTextArea.clientHeight;
				codeLinesDiv.css( {'margin-top': (-1*scrollTop) + "px"} );
				lineNo = fillOutLines( codeLinesDiv, scrollTop + clientHeight, lineNo );
			});

			/* Should the textarea get resized outside of our control */
			textarea.resize( function(tn){
				var domTextArea	= $(this)[0];
				linesDiv.height( domTextArea.clientHeight + 6 );
			});

		});
	};

  // default options
	$.fn.linedtextarea.defaults = {
		selectedLine: -1,
		selectedClass: 'lineselect'
	};
})(jQuery);
