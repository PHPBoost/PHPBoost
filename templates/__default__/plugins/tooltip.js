/**
 * Tooltip jQuery plugin - Version: 0.3
 * @copyright   &copy; 2005-2023 PHPBoost - 2019 babsolune
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 11 17
 * @since       PHPBoost 6.0 - 2019 11 15
*/

jQuery.fn.tooltip = function(content,pos,classes) {

	if(this.length==0) return this;
	if(!content){
		labelText = this.attr('data-tooltip');
		if(!labelText){
			labelText = this.attr('aria-label');
		}
	}

	// Define default position
	pos = ( pos === undefined || pos === null ) ? 'all' : pos;

	// Add Optional Classes
	classes = classes || '';
	var classAttr = (classes !== '') ?  classes : '';

	var tooltipWidth = this.outerWidth(),
		tooltipHeight = this.outerHeight(),
		tooltipDim = {
			"t": this.offset().top ,
			'r': this.offset().left + tooltipWidth ,
			'b': this.offset().top + tooltipHeight ,
			'l': this.offset().left
		}
	;

	// Force position values to be integers
	for(var i in tooltipDim) {
		tooltipDim[i] = parseInt( tooltipDim[i] );
	}

	// Set tooltip and/or params
	if($('#tooltip')[0] === undefined)
		$('body').append('<div id="tooltip" class="tooltip '+classAttr+'" ></div>');  // Add one or...
	else
		$('#tooltip').attr("class", "tooltip " + classes);  // Change the existing one...

	var $tooltip = $('#tooltip');

	$tooltip.html(labelText);

	// Dimensions are tricky so we use a Float
	var w = parseFloat($tooltip.outerWidth()),
		h = parseFloat($tooltip.outerHeight());
	var left = 0, top = 0;

	// Calculates and returns the position that the tooltip! should be displayed...
	function _autoPosition(){
		var splitPos = pos.split(',');
		if (splitPos.length == 1){
			switch (splitPos[0]){
				case 'all':
					splitPos = ['b','l','t','r'];
					break;
				case 'hor':
					splitPos = ['l','r'];
					break;
				case 'ver':
					splitPos = ['b','t'];
					break;
			}
		}
		// Calculate and store the available spaces...
		var space = {
				'b' :   $(window).height() - ($(window).scrollTop() + tooltipDim.b),
				'l' :   tooltipDim.l - $(window).scrollLeft(),
				't' :   tooltipDim.t - $(window).scrollTop(),
				'r' :   ( $(window).width() - tooltipDim.r ) + $(window).scrollLeft()
			},
			wRemain = (w - tooltipWidth)/ 2,
			hRemain = (h - tooltipHeight)/2;

		// Check the position in the defined order...
		for( var i = 0; i<splitPos.length;i++ ){
			if (('toprightbottomleft').search(splitPos[i])>=0){
				splitPos[i] = splitPos[i][0];

			}
			switch (splitPos[i]){
				case 'b':
					if(h < space.b && wRemain < space.l && wRemain < space.r ){ return 'b';}
					break;
				case 'l':
					if(w < space.l && hRemain < space.t && hRemain < space.b ){ return 'l';}
					break;
				case 't':
					if(h < space.t && wRemain < space.l && wRemain < space.r ){ return 't';}
					break;
				case 'r':
					if(w < space.r && hRemain < space.t && hRemain < space.b ){ return 'r'; }
					break;
			}
		}
		return 'b';
	}

	// data-tooltip-pos
	if(pos.split(',').length > 0){ pos = _autoPosition();}

	// Place the element to the chosen position
	switch (pos){
		case 'b':
			left = parseInt(tooltipDim.l + tooltipWidth/2 - w/2);
			top = parseInt( tooltipDim.t + tooltipHeight );
			break;
		case 'l':
			left = parseInt(tooltipDim.l - w);
			top = parseInt( tooltipDim.t + tooltipHeight/2 - h/2);
			break;
		case 't':
			left = parseInt(tooltipDim.l + tooltipWidth/2 - w/2);
			top = parseInt( tooltipDim.t - h);
			break;
		case 'r':
			left = parseInt(tooltipDim.l + tooltipWidth);
			top = parseInt( tooltipDim.t + tooltipHeight/2 - h/2);
			break;
		default : // b
			left = parseInt(tooltipDim.l + tooltipWidth/2 - w/2);
			top = parseInt( tooltipDim.t + tooltipHeight );
			break;
	}

	// add tooltip and set position
	$tooltip
		.css({
			'opacity':1,
			'visibility': 'visible',
			"left": left,
			"top": top
		})
		.addClass('position-'+pos)
	;

	// remove tooltip
	this.leave = function(){
		$tooltip.css({
			'opacity': 0,
			'visibility': 'hidden'
		})
	};

	// remove tooltip if not on screen
	this._check = function(){
		if(!this.closest('html').length)
			this.leave();
	};

	return this;
};

// plugin init
var tooltipController = function(attr){

	attr = 'aria-label' || 'data-tooltip';
	var checkerIn = '['+attr+']';
	var $tar;

	function tooltipCheck(e){
		var $tmptar = jQuery(e.target).closest(checkerIn);
		$tar = ( $tmptar.is(checkerIn) && !$tmptar.is($tar) ) ? $tmptar : ( $tar || $tmptar );
		if($tar.is(checkerIn)){
			var tooltipClass = $tar.attr('data-tooltip-class') || '';
			var tooltipPos = $tar.attr('data-tooltip-pos') || 'all';
			$tar.tooltip(null,tooltipPos,tooltipClass);
			jQuery($tar).off('mouseleave touchend',removeTooltip).one('mouseleave touchend',removeTooltip);
		}
	}

	var removeTooltip = function(e){
		if (jQuery($tar).length > 0)
			$tar.leave();
		$tar = undefined;
	};

	$(document).off('mouseover touchstart',tooltipCheck).on('mouseover touchstart',tooltipCheck);
	return this;

}();
