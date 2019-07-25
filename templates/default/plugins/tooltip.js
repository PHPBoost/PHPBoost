/**
 * Sortable jQuery plugin - Version: 0.9.13
 * @copyright 	&copy; 2005-2019 PHPBoost - 2012 Xpy
 * @license 	https://www.opensource.org/licenses/mit-license.php
 * @author      Xpy
 * @link        https://github.com/xpy/tipy
 * @doc         https://xpy.github.io/tipy/
 * @version   	PHPBoost 5.3 - last update: 2019 07 25
 * @since   	PHPBoost 5.2 - 2019 07 25
 *
 * @patch		replace data-tipy by aria-label / #tipy by #tooltip - move all bottom in first place
*/

jQuery.fn.tipy = function(text,pos,duration,classes)
{
	if(this.length==0) return this;
	if(this.is('[data-tipied]')){return;}
	var T = this;
	var isTitle = false;
	if(typeof text == 'number'){ classes = duration; duration = pos; pos = text; text = null;}
	if(!text){
		text =T.attr('aria-label');
		if(!text){
			text= T.attr('title');
			isTitle  = true;
			T.attr('data-title',text).attr('title',' ');
		}
	}
	pos = ( pos === undefined || pos === null ) ? 'all' : pos; // Default is 'auto' ( 'all' )
	if(typeof pos == 'number'){ classes = duration; duration = pos; pos="all"; }
	if(typeof duration == 'string') { classes = duration; duration = null;}
	classes = classes || '';
	var classAttr = (classes !== '')?'class="'+classes+'"': ''; // Add Optional Classes...

	duration = (!isNaN(duration) && duration!=null) ? duration : ((text.split(/\s+/).length+1)*1500); // If duration is not Defined it is computed by the text's words

	var objW = T.outerWidth(),
		objH = T.outerHeight(),
		objDim = { "t":T.offset().top , 'r': T.offset().left + objW ,'b': T.offset().top + objH ,'l': T.offset().left };
	for( var i in objDim ) { objDim[i] = parseInt( objDim[i] ); }  // Positioning values should be integers

	// Only one tipy! is allowed at a time
	if($('#tooltip')[0]===undefined){ $('body').append('<div id="tooltip" '+classAttr+' ></div>'); }  // Add one or...
	else { $('#tooltip').attr("class",classes); }  // Change the existing one...

	var $tipy = $('#tooltip');

	$tipy.html(text);

	T.attr('data-tipied',''); // Just to know if my tipy! is being displayed

	// Dimensions are tricky so we use a Float
	var w = parseFloat($tipy.outerWidth()),
		h = parseFloat($tipy.outerHeight());
	var left = 0, top = 0;

	// Calculates and returns the position that the tipy! should be displayed...
	function _autoPosition(){
		var splitPos = pos.split(',');
		if (splitPos.length == 1){
			switch (splitPos[0]){
				case 'all':
					splitPos = ['b','t','r','l','tr','br','bl','tl'];
					break;
				case 'cross':
					splitPos = ['b','t','r','l'];
					break;
				case 'x':
					splitPos = ['tr','br','bl','tl'];
					break;
				case 'hor':
					splitPos = ['r','l'];
					break;
				case 'ver':
					splitPos = ['b','t'];
					break;
			}
		}
		// Calculate and store the available spaces...
		var space = {
			'b' :   $(window).height() - ($(window).scrollTop() + objDim.b),
			't' :   objDim.t - $(window).scrollTop(),
			'r' :   ( $(window).width() - objDim.r ) + $(window).scrollLeft(),
			'l' :   objDim.l - $(window).scrollLeft()
		},
			wRemain = (w - objW)/ 2,
			hRemain = (h - objH)/2;

		// Check the position in the defined order...
		for( var i = 0; i<splitPos.length;i++ ){
			if (('toprightbottomleft').search(splitPos[i])>=0){
				splitPos[i] = splitPos[i][0];
			}
			switch (splitPos[i]){
				case 'b':
					if(h < space.b && wRemain < space.l && wRemain < space.r ){ return 'b';}
					break;
				case 't':
					if(h < space.t && wRemain < space.l && wRemain < space.r ){ return 't';}
					break;
				case 'r':
					if(w < space.r && hRemain < space.t && hRemain < space.b ){ return 'r'; }
					break;
				case 'l':
					if(w < space.l && hRemain < space.t && hRemain < space.b ){ return 'l';}
					break;
				case 'tr':
					if(h < space.t && w < space.r ){ return 'tr';}
					break;
				case 'br':
					if(w < space.r && h < space.b ){ return 'br'; }
					break;
				case 'bl':
					if(h < space.b && w < space.l ){ return 'bl';}
					break;
				case 'tl':
					if(w < space.l && h < space.t ){ return 'tl';}
					break;
				default:
					if(h < space.b && wRemain < space.l && wRemain < space.r ){ return 'b';}
					break;
			}
		}
		return 'b';
	} // _autoPosition

	// If there is more than one positions defined choose the better one
	if(pos.split(',').length > 0){ pos = _autoPosition();} // TODO what if it is not Comma Separated and the keyword is not valid? ( I think it is not a good idea to regexp or iterate)

	// Place the element to the chosen position
	switch (pos){
		case 't':
			left = parseInt(objDim.l + objW/2 - w/2);
			top = parseInt( objDim.t - h);
			break;
		case 'tr':
			left = parseInt(objDim.l + objW);
			top = parseInt( objDim.t - h);
			break;
		case 'r':
			left = parseInt(objDim.l + objW);
			top = parseInt( objDim.t + objH/2 - h/2);
			break;
		case 'br':
			left = parseInt(objDim.l + objW);
			top = parseInt( objDim.t + objH );
			break;
		case 'b':
			left = parseInt(objDim.l + objW/2 - w/2);
			top = parseInt( objDim.t + objH );
			break;
		case 'bl':
			left = parseInt(objDim.l - w);
			top = parseInt( objDim.t + objH );
			break;
		case 'l':
			left = parseInt(objDim.l - w);
			top = parseInt( objDim.t + objH/2 - h/2);
			break;
		case 'tl':
			left = parseInt(objDim.l - w);
			top = parseInt(objDim.t - h);
			break;
		default :
			left = parseInt(objDim.l + objW/2 - w/2);
			top = parseInt( objDim.t + objH );
			break;
	}

	// The Destructor
	T.leave = function(){
		$tipy.off('click');
		if(tipyInterval){ window.clearInterval(tipyInterval); }
		$tipy.stop(true,false).animate({'opacity':0},300).remove();
		if(isTitle){
			if(isTitle){ T.attr('title',T.attr('data-title')).removeAttr('data-title');}
		}
		T.removeAttr('data-tipied');
	};

	// Destroy the tipy! if the element that triggered it is not still on the screen
	T._check = function(){
		if(!T.closest('html').length){
			T.leave();
		}
	};
	// For Debugging Purposes
	/*
	console.log(
		{"myLeft":left,
			"myTop":top,
			"myW":w,
			"myH":h,
			"objW":objW,
			"objH":objH,
			"objDim":objDim
	});
	*/

	// Put it in position and add the position class
	$tipy.css({"display":"block","left":left,"top":top});
	$tipy.addClass('position-'+pos);

	$tipy.one('click', T.leave);

	var tipyInterval = window.setInterval(T._check,300);

	$tipy.stop(true,false).animate({'opacity':1},300,function(){
		if( duration > 0 ) {
		$tipy.delay(duration * .5).animate({'opacity':0},(duration * .5), function(){T.leave();});
		}
	});

	return T;
};

// This is a self executing function which is needed for the default hover functionality
var tipyController = function(attr){

	attr = attr || 'aria-label';
//	var isTitle = attr == 'title'; // TODO title implementation has bugs for nested titled elements
	var checkerIn = '['+attr+']';
	var $tar;
	var disabled = false;

	this.disable = function(){disabled = true;};
	this.enable = function(){disabled = false;};
	this.setAttr = function(newAttr){ attr = newAttr; };

	var removeTipy = function(e){
		$tar.leave();
		$tar = undefined;
	};

	function tipyCheck(e){
		if(!disabled){
		var $tmptar = $(e.target).closest(checkerIn);
		$tar = ( $tmptar.is(checkerIn) && !$tmptar.is($tar) ) ? $tmptar : ( $tar || $tmptar );
		if($tar.is(checkerIn) && !$tar.is('[data-tipied]')){
			var tipyClass = $tar.attr('data-tipy-class') || '';
			var tipyPos = $tar.attr('data-tipy-pos') || 'all';
			$tar.tipy(null,tipyPos,0,tipyClass);
			$($tar).off('mouseleave',removeTipy).one('mouseleave',removeTipy);
		}
		}
	}

	$(document).off('mouseover',tipyCheck).on('mouseover',tipyCheck);
	return this;

}(/* in here you can put any attribute to override the default aria-label attribute*/);
