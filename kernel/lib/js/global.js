/*! jQuery Responsive Menu 2015-09-16 CSSMenuMaker */
/* Adaptation pour PHPBoost 30/03/2016 */
(function($) {

	$.fn.menumaker = function(options) {
	
		var cssmenu = $(this), settings = $.extend({
			title: "Menu",
			format: "dropdown",
			breakpoint: 768,
			sticky: false,
			static: false,
			actionslinks: false
		}, options);

		var regtitle = new RegExp('[^A-Za-z0-9]', 'gi');

		return this.each(function() {
			menu_title = settings.title.replace(regtitle,'').toLowerCase();
			cssmenu.find('li ul').parent().addClass('has-sub');
			cssmenu.prepend('<div id="menu-button-' + menu_title + '" class="menu-button">' + settings.title + '</div>');
			$(this).find(".cssmenu-img").prependTo(this);
			$(this).find(".cssmenu-img").clone().prependTo( "#menu-button-" + menu_title );
			$(this).find('#menu-button-' + menu_title).on('click', function(){
				$(this).toggleClass('menu-opened');
				var mainmenu = $(this).next('ul');
				if (mainmenu.hasClass('open')) {
					mainmenu.addClass('close').removeClass('open');
				}
				else {
					mainmenu.removeClass('close').addClass('open');
				}
			});
	
			multiTg = function() {
				cssmenu.find('.has-sub').prepend('<span class="submenu-button"></span>');
				cssmenu.find('.submenu-button').on('click', function() {
					$(this).toggleClass('submenu-opened');
					if ($(this).siblings('ul').hasClass('open')) {
						$(this).siblings('ul').addClass('close').removeClass('open');
					}
					else {
						$(this).siblings('ul').addClass('open').removeClass('close');
					}
				});
			};
	
			multiTg();

			resizeFix = function() {
				$smallscreen = window.matchMedia('(max-width: ' + settings.breakpoint + 'px)').matches;

				if (!$smallscreen) {
					cssmenu.find('ul').removeClass('close');
					cssmenu.find('ul').removeClass('open');
					cssmenu.removeClass('small-screen');
					cssmenu.find('#menu-button-' + menu_title).removeClass('menu-opened');
				}
	
				if ($smallscreen && !cssmenu.hasClass('small-screen')) {

					if (settings.static) {
						cssmenu.find('ul').addClass('open');
						cssmenu.find('ul').removeClass('close');
						cssmenu.find('#menu-button-' + menu_title).addClass('menu-opened');
					}

					if (settings.actionslinks) {
						cssmenu.find('.level-0').addClass('close');
						cssmenu.find('.level-0').removeClass('open');
					}

					if (!settings.actionslinks && !settings.static) {
						cssmenu.find('ul').removeClass('open');
						cssmenu.find('ul').addClass('close');
					}

					cssmenu.addClass('small-screen');
				}

			};

			resizeFix();
			return $(window).on('resize', resizeFix);
		});
	};
})(jQuery);

/* ===================================================
 *  jquery-sortable.js v0.9.13
 *  http://johnny.github.com/jquery-sortable/
 * ===================================================
 *  Copyright (c) 2012 Jonas von Andrian
 *  All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without
 *  modification, are permitted provided that the following conditions are met:
 *  * Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *  * Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *  * The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 *  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 *  ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 *  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 *  DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
 *  DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 *  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 *  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 *  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 *  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * ========================================================== */
!function(d,B,m,f){function v(a,b){var c=Math.max(0,a[0]-b[0],b[0]-a[1]),e=Math.max(0,a[2]-b[1],b[1]-a[3]);return c+e}function w(a,b,c,e){var k=a.length;e=e?"offset":"position";for(c=c||0;k--;){var g=a[k].el?a[k].el:d(a[k]),l=g[e]();l.left+=parseInt(g.css("margin-left"),10);l.top+=parseInt(g.css("margin-top"),10);b[k]=[l.left-c,l.left+g.outerWidth()+c,l.top-c,l.top+g.outerHeight()+c]}}function p(a,b){var c=b.offset();return{left:a.left-c.left,top:a.top-c.top}}function x(a,b,c){b=[b.left,b.top];c=
c&&[c.left,c.top];for(var e,k=a.length,d=[];k--;)e=a[k],d[k]=[k,v(e,b),c&&v(e,c)];return d=d.sort(function(a,b){return b[1]-a[1]||b[2]-a[2]||b[0]-a[0]})}function q(a){this.options=d.extend({},n,a);this.containers=[];this.options.rootGroup||(this.scrollProxy=d.proxy(this.scroll,this),this.dragProxy=d.proxy(this.drag,this),this.dropProxy=d.proxy(this.drop,this),this.placeholder=d(this.options.placeholder),a.isValidTarget||(this.options.isValidTarget=f))}function t(a,b){this.el=a;this.options=d.extend({},
z,b);this.group=q.get(this.options);this.rootGroup=this.options.rootGroup||this.group;this.handle=this.rootGroup.options.handle||this.rootGroup.options.itemSelector;var c=this.rootGroup.options.itemPath;this.target=c?this.el.find(c):this.el;this.target.on(r.start,this.handle,d.proxy(this.dragInit,this));this.options.drop&&this.group.containers.push(this)}var r,z={drag:!0,drop:!0,exclude:"",nested:!0,vertical:!0},n={afterMove:function(a,b,c){},containerPath:"",containerSelector:"ol, ul",distance:0,
delay:0,handle:"",itemPath:"",itemSelector:"li",bodyClass:"dragging",draggedClass:"dragged",isValidTarget:function(a,b){return!0},onCancel:function(a,b,c,e){},onDrag:function(a,b,c,e){a.css(b)},onDragStart:function(a,b,c,e){a.css({height:a.outerHeight(),width:a.outerWidth()});a.addClass(b.group.options.draggedClass);d("body").addClass(b.group.options.bodyClass)},onDrop:function(a,b,c,e){a.removeClass(b.group.options.draggedClass).removeAttr("style");d("body").removeClass(b.group.options.bodyClass)},
onMousedown:function(a,b,c){if(!c.target.nodeName.match(/^(input|select|textarea)$/i))return c.preventDefault(),!0},placeholderClass:"placeholder",placeholder:'<li class="placeholder"></li>',pullPlaceholder:!0,serialize:function(a,b,c){a=d.extend({},a.data());if(c)return[b];b[0]&&(a.children=b);delete a.subContainers;delete a.sortable;return a},tolerance:0},s={},y=0,A={left:0,top:0,bottom:0,right:0};r={start:"touchstart.sortable mousedown.sortable",drop:"touchend.sortable touchcancel.sortable mouseup.sortable",
drag:"touchmove.sortable mousemove.sortable",scroll:"scroll.sortable"};q.get=function(a){s[a.group]||(a.group===f&&(a.group=y++),s[a.group]=new q(a));return s[a.group]};q.prototype={dragInit:function(a,b){this.$document=d(b.el[0].ownerDocument);var c=d(a.target).closest(this.options.itemSelector);c.length&&(this.item=c,this.itemContainer=b,!this.item.is(this.options.exclude)&&this.options.onMousedown(this.item,n.onMousedown,a)&&(this.setPointer(a),this.toggleListeners("on"),this.setupDelayTimer(),
this.dragInitDone=!0))},drag:function(a){if(!this.dragging){if(!this.distanceMet(a)||!this.delayMet)return;this.options.onDragStart(this.item,this.itemContainer,n.onDragStart,a);this.item.before(this.placeholder);this.dragging=!0}this.setPointer(a);this.options.onDrag(this.item,p(this.pointer,this.item.offsetParent()),n.onDrag,a);a=this.getPointer(a);var b=this.sameResultBox,c=this.options.tolerance;(!b||b.top-c>a.top||b.bottom+c<a.top||b.left-c>a.left||b.right+c<a.left)&&!this.searchValidTarget()&&
(this.placeholder.detach(),this.lastAppendedItem=f)},drop:function(a){this.toggleListeners("off");this.dragInitDone=!1;if(this.dragging){if(this.placeholder.closest("html")[0])this.placeholder.before(this.item).detach();else this.options.onCancel(this.item,this.itemContainer,n.onCancel,a);this.options.onDrop(this.item,this.getContainer(this.item),n.onDrop,a);this.clearDimensions();this.clearOffsetParent();this.lastAppendedItem=this.sameResultBox=f;this.dragging=!1}},searchValidTarget:function(a,b){a||
(a=this.relativePointer||this.pointer,b=this.lastRelativePointer||this.lastPointer);for(var c=x(this.getContainerDimensions(),a,b),e=c.length;e--;){var d=c[e][0];if(!c[e][1]||this.options.pullPlaceholder)if(d=this.containers[d],!d.disabled){if(!this.$getOffsetParent()){var g=d.getItemOffsetParent();a=p(a,g);b=p(b,g)}if(d.searchValidTarget(a,b))return!0}}this.sameResultBox&&(this.sameResultBox=f)},movePlaceholder:function(a,b,c,e){var d=this.lastAppendedItem;if(e||!d||d[0]!==b[0])b[c](this.placeholder),
this.lastAppendedItem=b,this.sameResultBox=e,this.options.afterMove(this.placeholder,a,b)},getContainerDimensions:function(){this.containerDimensions||w(this.containers,this.containerDimensions=[],this.options.tolerance,!this.$getOffsetParent());return this.containerDimensions},getContainer:function(a){return a.closest(this.options.containerSelector).data(m)},$getOffsetParent:function(){if(this.offsetParent===f){var a=this.containers.length-1,b=this.containers[a].getItemOffsetParent();if(!this.options.rootGroup)for(;a--;)if(b[0]!=
this.containers[a].getItemOffsetParent()[0]){b=!1;break}this.offsetParent=b}return this.offsetParent},setPointer:function(a){a=this.getPointer(a);if(this.$getOffsetParent()){var b=p(a,this.$getOffsetParent());this.lastRelativePointer=this.relativePointer;this.relativePointer=b}this.lastPointer=this.pointer;this.pointer=a},distanceMet:function(a){a=this.getPointer(a);return Math.max(Math.abs(this.pointer.left-a.left),Math.abs(this.pointer.top-a.top))>=this.options.distance},getPointer:function(a){var b=
a.originalEvent||a.originalEvent.touches&&a.originalEvent.touches[0];return{left:a.pageX||b.pageX,top:a.pageY||b.pageY}},setupDelayTimer:function(){var a=this;this.delayMet=!this.options.delay;this.delayMet||(clearTimeout(this._mouseDelayTimer),this._mouseDelayTimer=setTimeout(function(){a.delayMet=!0},this.options.delay))},scroll:function(a){this.clearDimensions();this.clearOffsetParent()},toggleListeners:function(a){var b=this;d.each(["drag","drop","scroll"],function(c,e){b.$document[a](r[e],b[e+
"Proxy"])})},clearOffsetParent:function(){this.offsetParent=f},clearDimensions:function(){this.traverse(function(a){a._clearDimensions()})},traverse:function(a){a(this);for(var b=this.containers.length;b--;)this.containers[b].traverse(a)},_clearDimensions:function(){this.containerDimensions=f},_destroy:function(){s[this.options.group]=f}};t.prototype={dragInit:function(a){var b=this.rootGroup;!this.disabled&&!b.dragInitDone&&this.options.drag&&this.isValidDrag(a)&&b.dragInit(a,this)},isValidDrag:function(a){return 1==
a.which||"touchstart"==a.type&&1==a.originalEvent.touches.length},searchValidTarget:function(a,b){var c=x(this.getItemDimensions(),a,b),e=c.length,d=this.rootGroup,g=!d.options.isValidTarget||d.options.isValidTarget(d.item,this);if(!e&&g)return d.movePlaceholder(this,this.target,"append"),!0;for(;e--;)if(d=c[e][0],!c[e][1]&&this.hasChildGroup(d)){if(this.getContainerGroup(d).searchValidTarget(a,b))return!0}else if(g)return this.movePlaceholder(d,a),!0},movePlaceholder:function(a,b){var c=d(this.items[a]),
e=this.itemDimensions[a],k="after",g=c.outerWidth(),f=c.outerHeight(),h=c.offset(),h={left:h.left,right:h.left+g,top:h.top,bottom:h.top+f};this.options.vertical?b.top<=(e[2]+e[3])/2?(k="before",h.bottom-=f/2):h.top+=f/2:b.left<=(e[0]+e[1])/2?(k="before",h.right-=g/2):h.left+=g/2;this.hasChildGroup(a)&&(h=A);this.rootGroup.movePlaceholder(this,c,k,h)},getItemDimensions:function(){this.itemDimensions||(this.items=this.$getChildren(this.el,"item").filter(":not(."+this.group.options.placeholderClass+
", ."+this.group.options.draggedClass+")").get(),w(this.items,this.itemDimensions=[],this.options.tolerance));return this.itemDimensions},getItemOffsetParent:function(){var a=this.el;return"relative"===a.css("position")||"absolute"===a.css("position")||"fixed"===a.css("position")?a:a.offsetParent()},hasChildGroup:function(a){return this.options.nested&&this.getContainerGroup(a)},getContainerGroup:function(a){var b=d.data(this.items[a],"subContainers");if(b===f){var c=this.$getChildren(this.items[a],
"container"),b=!1;c[0]&&(b=d.extend({},this.options,{rootGroup:this.rootGroup,group:y++}),b=c[m](b).data(m).group);d.data(this.items[a],"subContainers",b)}return b},$getChildren:function(a,b){var c=this.rootGroup.options,e=c[b+"Path"],c=c[b+"Selector"];a=d(a);e&&(a=a.find(e));return a.children(c)},_serialize:function(a,b){var c=this,e=this.$getChildren(a,b?"item":"container").not(this.options.exclude).map(function(){return c._serialize(d(this),!b)}).get();return this.rootGroup.options.serialize(a,
e,b)},traverse:function(a){d.each(this.items||[],function(b){(b=d.data(this,"subContainers"))&&b.traverse(a)});a(this)},_clearDimensions:function(){this.itemDimensions=f},_destroy:function(){var a=this;this.target.off(r.start,this.handle);this.el.removeData(m);this.options.drop&&(this.group.containers=d.grep(this.group.containers,function(b){return b!=a}));d.each(this.items||[],function(){d.removeData(this,"subContainers")})}};var u={enable:function(){this.traverse(function(a){a.disabled=!1})},disable:function(){this.traverse(function(a){a.disabled=
!0})},serialize:function(){return this._serialize(this.el,!0)},refresh:function(){this.traverse(function(a){a._clearDimensions()})},destroy:function(){this.traverse(function(a){a._destroy()})}};d.extend(t.prototype,u);d.fn[m]=function(a){var b=Array.prototype.slice.call(arguments,1);return this.map(function(){var c=d(this),e=c.data(m);if(e&&u[a])return u[a].apply(e,b)||this;e||a!==f&&"object"!==typeof a||c.data(m,new t(c,a));return this})}}(jQuery,window,"sortable");

/**
*  Ajax Autocomplete for jQuery, version 1.4.7
*  (c) 2017 Tomas Kirda
*
*  Ajax Autocomplete for jQuery is freely distributable under the terms of an MIT-style license.
*  For details, see the web site: https://github.com/devbridge/jQuery-Autocomplete
*/
!function(a){"use strict";"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports&&"function"==typeof require?require("jquery"):jQuery)}(function(a){"use strict";function b(c,d){var e=this;e.element=c,e.el=a(c),e.suggestions=[],e.badQueries=[],e.selectedIndex=-1,e.currentValue=e.element.value,e.timeoutId=null,e.cachedResponse={},e.onChangeTimeout=null,e.onChange=null,e.isLocal=!1,e.suggestionsContainer=null,e.noSuggestionsContainer=null,e.options=a.extend({},b.defaults,d),e.classes={selected:"autocomplete-selected",suggestion:"autocomplete-suggestion"},e.hint=null,e.hintValue="",e.selection=null,e.initialize(),e.setOptions(d)}function c(a,b,c){return a.value.toLowerCase().indexOf(c)!==-1}function d(b){return"string"==typeof b?a.parseJSON(b):b}function e(a,b){if(!b)return a.value;var c="("+g.escapeRegExChars(b)+")";return a.value.replace(new RegExp(c,"gi"),"<strong>$1</strong>").replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/&lt;(\/?strong)&gt;/g,"<$1>")}function f(a,b){return'<div class="autocomplete-group">'+b+"</div>"}var g=function(){return{escapeRegExChars:function(a){return a.replace(/[|\\{}()[\]^$+*?.]/g,"\\$&")},createNode:function(a){var b=document.createElement("div");return b.className=a,b.style.position="absolute",b.style.display="none",b}}}(),h={ESC:27,TAB:9,RETURN:13,LEFT:37,UP:38,RIGHT:39,DOWN:40},i=a.noop;b.utils=g,a.Autocomplete=b,b.defaults={ajaxSettings:{},autoSelectFirst:!1,appendTo:"body",serviceUrl:null,lookup:null,onSelect:null,width:"auto",minChars:1,maxHeight:300,deferRequestBy:0,params:{},formatResult:e,formatGroup:f,delimiter:null,zIndex:9999,type:"GET",noCache:!1,onSearchStart:i,onSearchComplete:i,onSearchError:i,preserveInput:!1,containerClass:"autocomplete-suggestions",tabDisabled:!1,dataType:"text",currentRequest:null,triggerSelectOnValidInput:!0,preventBadQueries:!0,lookupFilter:c,paramName:"query",transformResult:d,showNoSuggestionNotice:!1,noSuggestionNotice:"No results",orientation:"bottom",forceFixPosition:!1},b.prototype={initialize:function(){var c,d=this,e="."+d.classes.suggestion,f=d.classes.selected,g=d.options;d.element.setAttribute("autocomplete","off"),d.noSuggestionsContainer=a('<div class="autocomplete-no-suggestion"></div>').html(this.options.noSuggestionNotice).get(0),d.suggestionsContainer=b.utils.createNode(g.containerClass),c=a(d.suggestionsContainer),c.appendTo(g.appendTo||"body"),"auto"!==g.width&&c.css("width",g.width),c.on("mouseover.autocomplete",e,function(){d.activate(a(this).data("index"))}),c.on("mouseout.autocomplete",function(){d.selectedIndex=-1,c.children("."+f).removeClass(f)}),c.on("click.autocomplete",e,function(){d.select(a(this).data("index"))}),c.on("click.autocomplete",function(){clearTimeout(d.blurTimeoutId)}),d.fixPositionCapture=function(){d.visible&&d.fixPosition()},a(window).on("resize.autocomplete",d.fixPositionCapture),d.el.on("keydown.autocomplete",function(a){d.onKeyPress(a)}),d.el.on("keyup.autocomplete",function(a){d.onKeyUp(a)}),d.el.on("blur.autocomplete",function(){d.onBlur()}),d.el.on("focus.autocomplete",function(){d.onFocus()}),d.el.on("change.autocomplete",function(a){d.onKeyUp(a)}),d.el.on("input.autocomplete",function(a){d.onKeyUp(a)})},onFocus:function(){var a=this;a.fixPosition(),a.el.val().length>=a.options.minChars&&a.onValueChange()},onBlur:function(){var a=this;a.blurTimeoutId=setTimeout(function(){a.hide()},200)},abortAjax:function(){var a=this;a.currentRequest&&(a.currentRequest.abort(),a.currentRequest=null)},setOptions:function(b){var c=this,d=a.extend({},c.options,b);c.isLocal=Array.isArray(d.lookup),c.isLocal&&(d.lookup=c.verifySuggestionsFormat(d.lookup)),d.orientation=c.validateOrientation(d.orientation,"bottom"),a(c.suggestionsContainer).css({"max-height":d.maxHeight+"px",width:d.width+"px","z-index":d.zIndex}),this.options=d},clearCache:function(){this.cachedResponse={},this.badQueries=[]},clear:function(){this.clearCache(),this.currentValue="",this.suggestions=[]},disable:function(){var a=this;a.disabled=!0,clearTimeout(a.onChangeTimeout),a.abortAjax()},enable:function(){this.disabled=!1},fixPosition:function(){var b=this,c=a(b.suggestionsContainer),d=c.parent().get(0);if(d===document.body||b.options.forceFixPosition){var e=b.options.orientation,f=c.outerHeight(),g=b.el.outerHeight(),h=b.el.offset(),i={top:h.top,left:h.left};if("auto"===e){var j=a(window).height(),k=a(window).scrollTop(),l=-k+h.top-f,m=k+j-(h.top+g+f);e=Math.max(l,m)===l?"top":"bottom"}if("top"===e?i.top+=-f:i.top+=g,d!==document.body){var n,o=c.css("opacity");b.visible||c.css("opacity",0).show(),n=c.offsetParent().offset(),i.top-=n.top,i.top+=d.scrollTop,i.left-=n.left,b.visible||c.css("opacity",o).hide()}"auto"===b.options.width&&(i.width=b.el.outerWidth()+"px"),c.css(i)}},isCursorAtEnd:function(){var a,b=this,c=b.el.val().length,d=b.element.selectionStart;return"number"==typeof d?d===c:!document.selection||(a=document.selection.createRange(),a.moveStart("character",-c),c===a.text.length)},onKeyPress:function(a){var b=this;if(!b.disabled&&!b.visible&&a.which===h.DOWN&&b.currentValue)return void b.suggest();if(!b.disabled&&b.visible){switch(a.which){case h.ESC:b.el.val(b.currentValue),b.hide();break;case h.RIGHT:if(b.hint&&b.options.onHint&&b.isCursorAtEnd()){b.selectHint();break}return;case h.TAB:if(b.hint&&b.options.onHint)return void b.selectHint();if(b.selectedIndex===-1)return void b.hide();if(b.select(b.selectedIndex),b.options.tabDisabled===!1)return;break;case h.RETURN:if(b.selectedIndex===-1)return void b.hide();b.select(b.selectedIndex);break;case h.UP:b.moveUp();break;case h.DOWN:b.moveDown();break;default:return}a.stopImmediatePropagation(),a.preventDefault()}},onKeyUp:function(a){var b=this;if(!b.disabled){switch(a.which){case h.UP:case h.DOWN:return}clearTimeout(b.onChangeTimeout),b.currentValue!==b.el.val()&&(b.findBestHint(),b.options.deferRequestBy>0?b.onChangeTimeout=setTimeout(function(){b.onValueChange()},b.options.deferRequestBy):b.onValueChange())}},onValueChange:function(){if(this.ignoreValueChange)return void(this.ignoreValueChange=!1);var b=this,c=b.options,d=b.el.val(),e=b.getQuery(d);return b.selection&&b.currentValue!==e&&(b.selection=null,(c.onInvalidateSelection||a.noop).call(b.element)),clearTimeout(b.onChangeTimeout),b.currentValue=d,b.selectedIndex=-1,c.triggerSelectOnValidInput&&b.isExactMatch(e)?void b.select(0):void(e.length<c.minChars?b.hide():b.getSuggestions(e))},isExactMatch:function(a){var b=this.suggestions;return 1===b.length&&b[0].value.toLowerCase()===a.toLowerCase()},getQuery:function(b){var c,d=this.options.delimiter;return d?(c=b.split(d),a.trim(c[c.length-1])):b},getSuggestionsLocal:function(b){var c,d=this,e=d.options,f=b.toLowerCase(),g=e.lookupFilter,h=parseInt(e.lookupLimit,10);return c={suggestions:a.grep(e.lookup,function(a){return g(a,b,f)})},h&&c.suggestions.length>h&&(c.suggestions=c.suggestions.slice(0,h)),c},getSuggestions:function(b){var c,d,e,f,g=this,h=g.options,i=h.serviceUrl;if(h.params[h.paramName]=b,h.onSearchStart.call(g.element,h.params)!==!1){if(d=h.ignoreParams?null:h.params,a.isFunction(h.lookup))return void h.lookup(b,function(a){g.suggestions=a.suggestions,g.suggest(),h.onSearchComplete.call(g.element,b,a.suggestions)});g.isLocal?c=g.getSuggestionsLocal(b):(a.isFunction(i)&&(i=i.call(g.element,b)),e=i+"?"+a.param(d||{}),c=g.cachedResponse[e]),c&&Array.isArray(c.suggestions)?(g.suggestions=c.suggestions,g.suggest(),h.onSearchComplete.call(g.element,b,c.suggestions)):g.isBadQuery(b)?h.onSearchComplete.call(g.element,b,[]):(g.abortAjax(),f={url:i,data:d,type:h.type,dataType:h.dataType},a.extend(f,h.ajaxSettings),g.currentRequest=a.ajax(f).done(function(a){var c;g.currentRequest=null,c=h.transformResult(a,b),g.processResponse(c,b,e),h.onSearchComplete.call(g.element,b,c.suggestions)}).fail(function(a,c,d){h.onSearchError.call(g.element,b,a,c,d)}))}},isBadQuery:function(a){if(!this.options.preventBadQueries)return!1;for(var b=this.badQueries,c=b.length;c--;)if(0===a.indexOf(b[c]))return!0;return!1},hide:function(){var b=this,c=a(b.suggestionsContainer);a.isFunction(b.options.onHide)&&b.visible&&b.options.onHide.call(b.element,c),b.visible=!1,b.selectedIndex=-1,clearTimeout(b.onChangeTimeout),a(b.suggestionsContainer).hide(),b.signalHint(null)},suggest:function(){if(!this.suggestions.length)return void(this.options.showNoSuggestionNotice?this.noSuggestions():this.hide());var b,c=this,d=c.options,e=d.groupBy,f=d.formatResult,g=c.getQuery(c.currentValue),h=c.classes.suggestion,i=c.classes.selected,j=a(c.suggestionsContainer),k=a(c.noSuggestionsContainer),l=d.beforeRender,m="",n=function(a,c){var f=a.data[e];return b===f?"":(b=f,d.formatGroup(a,b))};return d.triggerSelectOnValidInput&&c.isExactMatch(g)?void c.select(0):(a.each(c.suggestions,function(a,b){e&&(m+=n(b,g,a)),m+='<div class="'+h+'" data-index="'+a+'">'+f(b,g,a)+"</div>"}),this.adjustContainerWidth(),k.detach(),j.html(m),a.isFunction(l)&&l.call(c.element,j,c.suggestions),c.fixPosition(),j.show(),d.autoSelectFirst&&(c.selectedIndex=0,j.scrollTop(0),j.children("."+h).first().addClass(i)),c.visible=!0,void c.findBestHint())},noSuggestions:function(){var b=this,c=b.options.beforeRender,d=a(b.suggestionsContainer),e=a(b.noSuggestionsContainer);this.adjustContainerWidth(),e.detach(),d.empty(),d.append(e),a.isFunction(c)&&c.call(b.element,d,b.suggestions),b.fixPosition(),d.show(),b.visible=!0},adjustContainerWidth:function(){var b,c=this,d=c.options,e=a(c.suggestionsContainer);"auto"===d.width?(b=c.el.outerWidth(),e.css("width",b>0?b:300)):"flex"===d.width&&e.css("width","")},findBestHint:function(){var b=this,c=b.el.val().toLowerCase(),d=null;c&&(a.each(b.suggestions,function(a,b){var e=0===b.value.toLowerCase().indexOf(c);return e&&(d=b),!e}),b.signalHint(d))},signalHint:function(b){var c="",d=this;b&&(c=d.currentValue+b.value.substr(d.currentValue.length)),d.hintValue!==c&&(d.hintValue=c,d.hint=b,(this.options.onHint||a.noop)(c))},verifySuggestionsFormat:function(b){return b.length&&"string"==typeof b[0]?a.map(b,function(a){return{value:a,data:null}}):b},validateOrientation:function(b,c){return b=a.trim(b||"").toLowerCase(),a.inArray(b,["auto","bottom","top"])===-1&&(b=c),b},processResponse:function(a,b,c){var d=this,e=d.options;a.suggestions=d.verifySuggestionsFormat(a.suggestions),e.noCache||(d.cachedResponse[c]=a,e.preventBadQueries&&!a.suggestions.length&&d.badQueries.push(b)),b===d.getQuery(d.currentValue)&&(d.suggestions=a.suggestions,d.suggest())},activate:function(b){var c,d=this,e=d.classes.selected,f=a(d.suggestionsContainer),g=f.find("."+d.classes.suggestion);return f.find("."+e).removeClass(e),d.selectedIndex=b,d.selectedIndex!==-1&&g.length>d.selectedIndex?(c=g.get(d.selectedIndex),a(c).addClass(e),c):null},selectHint:function(){var b=this,c=a.inArray(b.hint,b.suggestions);b.select(c)},select:function(a){var b=this;b.hide(),b.onSelect(a)},moveUp:function(){var b=this;if(b.selectedIndex!==-1)return 0===b.selectedIndex?(a(b.suggestionsContainer).children("."+b.classes.suggestion).first().removeClass(b.classes.selected),b.selectedIndex=-1,b.ignoreValueChange=!1,b.el.val(b.currentValue),void b.findBestHint()):void b.adjustScroll(b.selectedIndex-1)},moveDown:function(){var a=this;a.selectedIndex!==a.suggestions.length-1&&a.adjustScroll(a.selectedIndex+1)},adjustScroll:function(b){var c=this,d=c.activate(b);if(d){var e,f,g,h=a(d).outerHeight();e=d.offsetTop,f=a(c.suggestionsContainer).scrollTop(),g=f+c.options.maxHeight-h,e<f?a(c.suggestionsContainer).scrollTop(e):e>g&&a(c.suggestionsContainer).scrollTop(e-c.options.maxHeight+h),c.options.preserveInput||(c.ignoreValueChange=!0,c.el.val(c.getValue(c.suggestions[b].value))),c.signalHint(null)}},onSelect:function(b){var c=this,d=c.options.onSelect,e=c.suggestions[b];c.currentValue=c.getValue(e.value),c.currentValue===c.el.val()||c.options.preserveInput||c.el.val(c.currentValue),c.signalHint(null),c.suggestions=[],c.selection=e,a.isFunction(d)&&d.call(c.element,e)},getValue:function(a){var b,c,d=this,e=d.options.delimiter;return e?(b=d.currentValue,c=b.split(e),1===c.length?a:b.substr(0,b.length-c[c.length-1].length)+a):a},dispose:function(){var b=this;b.el.off(".autocomplete").removeData("autocomplete"),a(window).off("resize.autocomplete",b.fixPositionCapture),a(b.suggestionsContainer).remove()}},a.fn.devbridgeAutocomplete=function(c,d){var e="autocomplete";return arguments.length?this.each(function(){var f=a(this),g=f.data(e);"string"==typeof c?g&&"function"==typeof g[c]&&g[c](d):(g&&g.dispose&&g.dispose(),g=new b(this,c),f.data(e,g))}):this.first().data(e)},a.fn.autocomplete||(a.fn.autocomplete=a.fn.devbridgeAutocomplete)});

/*
 * jQuery Basic Table
 * Author: Jerry Low
 */

(function($) {
	$.fn.basictable = function(options) {
		var setup = function(table, data) {
			if (data.tableWrap) {
				table.wrap('<div class="bt-wrapper"></div>');
			}

			var format = '';

			if (table.find('thead th').length) {
				format = 'thead th';
			}
			else if (table.find('th').length) {
				format = 'tr:first th';
			}
			else {
				format = 'tr:first td';
			}

			$.each(table.find(format), function() {
				var $heading = $(this);

				$.each(table.find('tbody tr'), function() {
					var $cell = $(this).find('td:eq(' + $heading.index() + ')');

					if ($cell.html() === '' || $cell.html() === '&nbsp;') {
						$cell.addClass('bt-hide');
					}
					else {
						$cell.attr('data-th', $heading.text());

						if (data.contentWrap) {
							$cell.wrapInner('<span class="bt-content"></span>');
						}
					}
				});
			});
		};

		var unwrap = function(table) {
			$.each(table.find('td'), function() {
				var $cell = $(this);
				var content = $cell.children('.bt-content').html();
				$cell.html(content);
			});
		};

		var check = function(table, data) {
			// Only change when table is larger than parent if force
			// responsive is turned off.
			if (!data.forceResponsive) {
				if (table.removeClass('bt').outerWidth() > table.parent().width()) {
					start(table, data);
				}
				else {
					end(table, data);
				}
			}
			else {
				if ($(window).width() <= data.breakpoint) {
					start(table, data);
				}
				else {
					end(table, data);
				}
			}
		};

		var start = function(table, data) {
			table.addClass('bt');
			$(".footer-error-list").attr('colspan','1');
			$(".footer-error-list404").attr('colspan','1');

			if (data.tableWrapper) {
				table.parent('.bt-wrapper').addClass('active');
			}
		};

		var end = function(table, data) {
			table.removeClass('bt');
			$(".footer-error-list").attr('colspan','2');
			$(".footer-error-list404").attr('colspan','4');  

			if (data.tableWrapper) {
				table.parent('.bt-wrapper').removeClass('active');
			}
		};

		var destroy = function(table, data) {
			table.find('td').removeAttr('data-th');

			if (data.tableWrap) {
				table.unwrap();
			}

			if (data.contentWrap) {
				unwrap(table);
			}

			table.removeData('basictable');
		};

		var resize = function(table) {
			if (table.data('basictable')) {
				check(table, table.data('basictable'));
			}
		};

		// Get table.
		var table = this;

		// If table has already executed.
		if (table.length === 0 || table.data('basictable')) {
			if (table.data('basictable')) {
				// Destroy basic table.
				if (options == 'destroy') {
					destroy(table, table.data('basictable'));
				}
				// Start responsive mode.
				else if (options === 'start') {
					start(table, table.data('basictable'));
				}
				else if (options === 'stop') {
					end(table, table.data('basictable'));
				}
				else {
					check(table);
				}
			}
			return false;
		}

		// Extend Settings.
		var settings = $.extend({}, $.fn.basictable.defaults, options);

		var vars = {
			breakpoint: settings.breakpoint,
			contentWrap: settings.contentWrap,
			forceResponsive: settings.forceResponsive,
			noResize: settings.noResize,
			tableWrapper: settings.tableWrapper
		};

		// Initiate
		table.data('basictable', vars);

		setup(table, table.data('basictable'));

		if (!vars.noResize) {
			check(table, table.data('basictable'));

			$(window).bind('resize.basictable', function() {
				resize(table);
			});
		}
	};

	$.fn.basictable.defaults = {
		breakpoint: 981,
		contentWrap: true,
		forceResponsive: true,
		noResize: false,
		tableWrap: false
	};
})(jQuery);

//Recherche d'une chaîne dans une autre.
function strpos(haystack, needle)
{
	var i = haystack.indexOf(needle, 0); // returns -1
	return i >= 0 ? i : false;
}

//Add information hide balise
jQuery(document).ready(function(){
	var IDCODE = 1;
	jQuery('.formatter-hide').each( function(){
		if ( jQuery(this).hasClass('no-js') )
		{
			jQuery(this).attr('id','formatter-hide-container-' + IDCODE);
			jQuery(this).removeClass('no-js');
			jQuery(this).attr('onClick', 'bb_hide(' + IDCODE + ', 1, event);');
			jQuery(this).children('.formatter-content').before('<span id="formatter-hide-message-' + IDCODE + '" class="formatter-hide-message">' + L_HIDE_MESSAGE + '</span>');
			jQuery(this).children('.formatter-content').before('<span id="formatter-hide-close-button-' + IDCODE + '" class="formatter-hide-close-button" "title="' + L_HIDE_HIDEBLOCK + '" onclick="bb_hide(' + IDCODE + ', 0, event);"><i class="fa fa-times"></i><span class="formatter-hide-close-button-txt">' + L_HIDE_HIDEBLOCK + '</span></span>');
			IDCODE = IDCODE + 1;
		}
	} );
} );	

//Hide / show hide balise content
function bb_hide(idcode, show, event)
{
	var idcode = (typeof idcode !== 'undefined') ? idcode : 0;
	var show = (typeof show !== 'undefined') ? show : 0;

	event.stopPropagation();
	jQuery('#formatter-hide-container-' + idcode).toggleClass('formatter-show');
	if (show == 1)
	{
		jQuery('#formatter-hide-container-' + idcode).removeAttr('onClick');
	}
	else
	{
		jQuery('#formatter-hide-container-' + idcode).attr('onClick', 'bb_hide(' + idcode + ', 1, event);');
	}
}


//Add button "Copy to clipboard" on Coding balise
jQuery(document).ready(function(){
	var IDCODE = 1;
	jQuery('.formatter-code').each( function(){
		if ( !jQuery(this).children('.formatter-content').hasClass('copy-code-content') )
		{
			jQuery(this).prepend('<span id="copy-code-' + IDCODE + '" class="copy-code" title="' + L_COPYTOCLIPBOARD + '" onclick="copy_code_clipboard(' + IDCODE + ')"><i class="fa fa-clipboard"><span class="copy-code-txt">' + L_COPYTOCLIPBOARD + '</span></i></span>');
			jQuery(this).children('.formatter-content').attr("id", 'copy-code-' + IDCODE + '-content');
			jQuery(this).children('.formatter-content').addClass('copy-code-content');
			IDCODE = IDCODE + 1;	
		}
	} );
} );	

//Function copy_code_clipboard
//
// Description : 
// This function copy the content of your specific selection to clipboard.
//
// parameters : one
// {idcode} correspond to the ID selector you want to select.
//  - if it's a number : ID selector is 'copy-code-{idcode}-content'
//  - if it's a string : ID selector is '{idcode}'
//
// Return : -
//
// Comments : 
// if container is an HTMLTextAreaElement, we use select() function of TextArea element instead of specific SelectElement function
//
function copy_code_clipboard(idcode)
{
	if ( Number.isInteger(idcode) )
		idcode = 'copy-code-' + idcode + '-content';
	
	var ElementtoCopy = document.getElementById( idcode );
	
	if (ElementtoCopy instanceof HTMLTextAreaElement)
		ElementtoCopy.select();
	else
		SelectElement(ElementtoCopy);

	try { 
		var successful = document.execCommand('copy');
	} 
	catch(err) {
		alert('Your browser do not authorize this operation');
	}	
}

//Function SelectElement
//
// Description : 
// The content will be selected on your page as if you had selected it with your mouse
//
// parameters : one
// {element} correspond to the element you want to select
//
// Return : -
//
// Comments : -
//
function SelectElement(element) {
	var range = document.createRange();
	range.selectNodeContents(element); 

	var selection = window.getSelection();
	selection.removeAllRanges();
	selection.addRange(range); 
}

//Function copy_to_clipboard
//
// Description : 
// This function copy the content of parameter to clipboard.
//
// parameters : one
// {tocopy} correspond to the content you want to copy.
//
// Return : -
//
// Comments : -
//
function copy_to_clipboard(tocopy)
{
	var dummy = $('<input>').val(tocopy).appendTo('body').select()

	try { 
		var successful = document.execCommand('copy');
	} 
	catch(err) {
		alert('Your browser do not authorize this operation');
	}	
}

//Function open_submenu
//
// Description : 
// This function add CSS Class to the specified CSS ID
//
// parameters : three
// {myid} correspond to the specific element you want to add your CSS class
// {myclass} correspond to the name of CSS class you want to add to your specific element.
// {closeother} correspond to the name of CSS class you want to add to your specific element.
//
// Return : -
//
// Comments : if {myclass} is missing, we use CSS class "opened"
// Comments : if {closeother} is defined, we close every elements with {closeother} CSS class
//
function open_submenu(myid, myclass, closeother)
{
	var myclass = (typeof myclass !== 'undefined') ? myclass : "opened";
	var closeother = (typeof closeother !== 'undefined') ? closeother : false;

	if (closeother == false)
		jQuery('#' + myid).toggleClass(myclass); 
	else {
		if (jQuery('#' + myid).hasClass(myclass))
			jQuery('.' + closeother).removeClass(myclass);
		else {
			jQuery('.' + closeother).removeClass(myclass);
			jQuery('#' + myid).addClass(myclass);
		}
	}
}

//Function opensubmenu
//
// Description : 
// This function add CSS Class to the specified CSS ID to open a submenu
//
// options : four
// {osmCloseExcept} correspond to the specific element you doesn't want to close on click.
// {osmCloseButton} correspond to the specific button for closed submenu.
// {osmTarget} correspond to the name of CSS class of you element you want to add a specific CSS class.
// {osmClass} correspond to the name of CSS class you want to add to your specific element.
//
// Return : -
//
// Comments :
//   - if {osmClass} is missing, we use CSS class "opened"
//   - if {osmCloseButton} is missing, we use element "a.close-button"
//   - use CSS selector "." or "#" for {osmCloseExcept} and {osmTarget}
//   - for all elements used * in {osmCloseExcept} like '.myClass *'
//
(function($) {
	$.fn.opensubmenu = function( options ) {
		var defaults = $(this), params = $.extend({
			osmCloseExcept: '',
			osmCloseButton: 'a.close-button',
			osmTarget: '',
			osmClass: 'opened'
		}, options);

		return this.each(function() {
			$(this).on('click', function(event) {
				event.preventDefault();
				if ($(this).closest(params.osmTarget).hasClass(params.osmClass))
					$(document).find(params.osmTarget).removeClass(params.osmClass);
				else {
					$(document).find(params.osmTarget).removeClass(params.osmClass);
					$(this).closest(params.osmTarget).addClass(params.osmClass);
				}
				event.stopPropagation();
			});
			$(document).on('click',function(event) {
				if (($(event.target).is(params.osmCloseExcept) === false || $(event.target).is(params.osmCloseButton) === true)) {
					$(document).find(params.osmTarget).removeClass(params.osmClass);
				}
			});
		});
	};
})(jQuery);

//Function multiple_checkbox_check
//
// Description : 
// This function check or uncheck all checkbox with specific id
//
// options : one
// {status} correspond to the status we need (check or uncheck).
//
// Return : -
//
// Comments :
//
function multiple_checkbox_check(status, nbr_element, except_element)
{
	var i;
	var except_element = (typeof except_element !== 'undefined') ? except_element : 0;
	for(i = 1; i <= nbr_element; i++)
	{
		if($('#multiple-checkbox-' + i)[0] && i != except_element)
			$('#multiple-checkbox-' + i)[0].checked = status;
	}
	try {
		$('.check-all')[0].checked = status;
	}
	catch (err) {}
}

//Barre de progression, 
function change_progressbar(id_element, value, informations) {
	var progress_bar_el = jQuery('#' + id_element).children('.progressbar').css('width', value + '%');

	if (informations) {
		jQuery('#' + id_element).children('.progressbar-infos').text(informations);
	}
	else {
		jQuery('#' + id_element).children('.progressbar-infos').text(value + '%');
	}
}

//Fonction de préparation de l'ajax.
function xmlhttprequest_init(filename)
{
	var xhr_object = null;
	if (window.XMLHttpRequest) //Firefox
		xhr_object = new XMLHttpRequest();
	else if (window.ActiveXObject) //Internet Explorer
		xhr_object = new ActiveXObject("Microsoft.XMLHTTP");

	xhr_object.open('POST', filename, true);

	return xhr_object;
}

//Fonction ajax d'envoi.
function xmlhttprequest_sender(xhr_object, data)
{
	xhr_object.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr_object.send(data);
}

//Echape les variables de type chaînes dans les requêtes xmlhttprequest.
function escape_xmlhttprequest(contents)
{
	contents = contents.replace(/\+/g, '%2B');
	contents = contents.replace(/&/g, '%26');
	
	return contents;
}

//Fonction de recherche des membres.
function XMLHttpRequest_search_members(searchid, theme, insert_mode, alert_empty_login)
{
	var login = jQuery('#login' + searchid).val();
	if( login != "" )
	{
		if (jQuery('#search_img' + searchid))
			jQuery('#search_img' + searchid).append('<i class="fa fa-spinner fa-spin"></i>');

		jQuery.ajax({
			url: PATH_TO_ROOT + '/kernel/framework/ajax/member_xmlhttprequest.php?' + insert_mode + '=1',
			type: "post",
			dataType: "html",
			data: {'login': login, 'divid' : searchid, 'token' : TOKEN},
			success: function(returnData){
				if (jQuery('#search_img' + searchid))
					jQuery('#search_img' + searchid).children("i").remove();

				if (jQuery("#xmlhttprequest-result-search" + searchid))
					jQuery("#xmlhttprequest-result-search" + searchid).html(returnData);

				jQuery("#xmlhttprequest-result-search" + searchid).fadeIn();
			},
			error: function(e){
				jQuery('#search_img' + searchid).children("i").remove();
			}
		});
	}	
	else
		alert(alert_empty_login);
}

//Pour savoir si une fonction existe
function functionExists(function_name)
{
	// http://kevin.vanzonneveld.net
	// + original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// + improved by: Steve Clay
	// + improved by: Legaev Andrey
	// * example 1: function_exists('isFinite');
	// * returns 1: true 
	if (typeof function_name == 'string')
	{
		return (typeof window[function_name] == 'function');
	}
	else
	{
		return (function_name instanceof Function);
	}
}

//Includes synchronously a js file
function include(file)
{
	if (window.document.getElementsByTagName)
	{
		script = window.document.createElement("script");
		script.type = "text/javascript";
		script.src = file;
		document.documentElement.firstChild.appendChild(script);
	}
}

//Affiche le lecteur vidéo avec la bonne URL, largeur et hauteur
playerflowPlayerRequired = false;
function insertMoviePlayer(id)
{
	if (!playerflowPlayerRequired)
	{
		include(PATH_TO_ROOT + '/kernel/lib/flash/flowplayer/flowplayer.js');
		playerflowPlayerRequired = true;
	}
	flowPlayerDisplay(id);
}

//Construit le lecteur à partir du moment où son code a été interprété par l'interpréteur javascript
function flowPlayerDisplay(id)
{
	//Construit et affiche un lecteur vidéo de type flowplayer
	//Si la fonction n'existe pas, on attend qu'elle soit interprétée
	if (!functionExists('flowplayer'))
	{
		setTimeout('flowPlayerDisplay(\'' + id + '\')', 100);
		return;
	}
	//On lance le flowplayer
	flowplayer(id, PATH_TO_ROOT + '/kernel/lib/flash/flowplayer/flowplayer.swf', { 
			clip: { 
				url: jQuery('#' + id).attr('href'),
				autoPlay: false 
			}
		}
	);
}

var delay = 300; //Délai après lequel le bloc est automatiquement masqué, après le départ de la souris.
var timeout;
var displayed = false;
var previous_bblock;

//Affiche le bloc.
function bb_display_block(divID, field)
{
	var i;
	
	if( timeout )
		clearTimeout(timeout);
	
	var block = document.getElementById('bb-block' + divID + field);
	if( block.style.display == 'none' )
	{
		if( document.getElementById(previous_bblock) )
			document.getElementById(previous_bblock).style.display = 'none';
		block.style.display = 'block';
		displayed = true;
		previous_bblock = 'bb-block' + divID + field;
	}
	else
	{
		block.style.display = 'none';
		displayed = false;
	}
}

//Cache le bloc.
function bb_hide_block(bbfield, field, stop)
{
	if( stop && timeout )
	{
		clearTimeout(timeout);
	}
	else if( displayed )
	{
		clearTimeout(timeout);
		timeout = setTimeout('bb_display_block(\'' + bbfield + '\',	\'' + field + '\')', delay);
	}
}

// Gestion de la position du scroll (scroll-to-top + cookie-bar)
function scroll_to( position ) {
	if ( position > 800) {
		jQuery('#scroll-to-top').fadeIn();
	} else {
		jQuery('#scroll-to-top').fadeOut();
	}

	if ( position > 1) {
		jQuery('#cookie-bar-container').addClass('fixed');
	} 
	else {
		jQuery('#cookie-bar-container').removeClass('fixed');
	}
}

jQuery(document).ready(function(){
	scroll_to($(this).scrollTop());

	jQuery(window).scroll(function(){
		scroll_to($(this).scrollTop());
	});

	//Scroll to Top or Bottom
	jQuery('#scroll-to-top').on('click',function(){
		jQuery('html, body').animate({scrollTop : 0},1200);
		return false;
	});
	jQuery('#scroll-to-bottom').on('click',function(){
		jQuery('html, body').animate({scrollTop: $(document).height()-$(window).height()},1200);
		return false;
	});
});

//Gestion Cookie BBCode et Cookiebar
//Envoi le cookie au client.
function sendCookie(name, value, delai)
{
	var delai = (typeof delai !== 'undefined') ? delai : 1; //1 mois de validité par défaut.
	var date = new Date();
	date.setMonth(date.getMonth() + delai); 
	document.cookie = name + '=' + value + '; Expires=' + date.toGMTString() + '; Path=/';
}

//Récupère la valeur du cookie.
function getCookie(name) 
{
	start = document.cookie.indexOf(name + "=")
	if( start >= 0 ) 
	{
		start += name.length + 1;
		end = document.cookie.indexOf(';', start);
		
		if( end < 0 ) 
			end = document.cookie.length;
		
		return document.cookie.substring(start, end);
	}
	return '';
}

//Supprime le cookie.
function eraseCookie(name) {
	sendCookie(name,"",-1);
}

/**
 * jQuery Lined Textarea Plugin 
 *   http://alan.blog-city.com/jquerylinedtextarea.htm
 *
 * Copyright (c) 2010 Alan Williamson
 * 
 * Version: 
 *    $Id: jquery-linedtextarea.js 464 2010-01-08 10:36:33Z alan $
 *
 * Released under the MIT License:
 *    http://www.opensource.org/licenses/mit-license.php
 *
 * History:
 *   - 2010.01.08: Fixed a Google Chrome layout problem
 *   - 2010.01.07: Refactored code for speed/readability; Fixed horizontal sizing
 *   - 2010.01.06: Initial Release
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
