/**
 * Lightbox v2.7.1
 * by Lokesh Dhakar - http://lokeshdhakar.com/projects/lightbox2/
 *
 * @license http://creativecommons.org/licenses/by/2.5/
 * - Free for use in both personal and commercial projects
 * - Attribution requires leaving author name, author link, and the license info intact
 */
(function(){var a=jQuery,b=function(){function a(){this.fadeDuration=500,this.fitImagesInViewport=!0,this.resizeDuration=700,this.positionFromTop=50,this.showImageNumberLabel=!0,this.alwaysShowNavOnTouchDevices=!1,this.wrapAround=!1}return a.prototype.albumLabel=function(a,b){return"Image "+a+" of "+b},a}(),c=function(){function b(a){this.options=a,this.album=[],this.currentImageIndex=void 0,this.init()}return b.prototype.init=function(){this.enable(),this.build()},b.prototype.enable=function(){var b=this;a("body").on("click","a[rel^=lightbox], area[rel^=lightbox], a[data-lightbox], area[data-lightbox]",function(c){return b.start(a(c.currentTarget)),!1})},b.prototype.build=function(){var b=this;a("<div id='lightboxOverlay' class='lightboxOverlay'></div><div id='lightbox' class='lightbox'><div class='lb-outerContainer'><div class='lb-container'><img class='lb-image' src='' /><div class='lb-nav'><a class='lb-prev' href='' ></a><a class='lb-next' href='' ></a></div><div class='lb-loader'><a class='lb-cancel'></a></div></div></div><div class='lb-dataContainer'><div class='lb-data'><div class='lb-details'><span class='lb-caption'></span><span class='lb-number'></span></div><div class='lb-closeContainer'><a class='lb-close'></a></div></div></div></div>").appendTo(a("body")),this.$lightbox=a("#lightbox"),this.$overlay=a("#lightboxOverlay"),this.$outerContainer=this.$lightbox.find(".lb-outerContainer"),this.$container=this.$lightbox.find(".lb-container"),this.containerTopPadding=parseInt(this.$container.css("padding-top"),10),this.containerRightPadding=parseInt(this.$container.css("padding-right"),10),this.containerBottomPadding=parseInt(this.$container.css("padding-bottom"),10),this.containerLeftPadding=parseInt(this.$container.css("padding-left"),10),this.$overlay.hide().on("click",function(){return b.end(),!1}),this.$lightbox.hide().on("click",function(c){return"lightbox"===a(c.target).attr("id")&&b.end(),!1}),this.$outerContainer.on("click",function(c){return"lightbox"===a(c.target).attr("id")&&b.end(),!1}),this.$lightbox.find(".lb-prev").on("click",function(){return b.changeImage(0===b.currentImageIndex?b.album.length-1:b.currentImageIndex-1),!1}),this.$lightbox.find(".lb-next").on("click",function(){return b.changeImage(b.currentImageIndex===b.album.length-1?0:b.currentImageIndex+1),!1}),this.$lightbox.find(".lb-loader, .lb-close").on("click",function(){return b.end(),!1})},b.prototype.start=function(b){function c(a){d.album.push({link:a.attr("href"),title:a.attr("data-title")||a.attr("title")})}var d=this,e=a(window);e.on("resize",a.proxy(this.sizeOverlay,this)),a("select, object, embed").css({visibility:"hidden"}),this.sizeOverlay(),this.album=[];var f,g=0,h=b.attr("data-lightbox");if(h){f=a(b.prop("tagName")+'[data-lightbox="'+h+'"]');for(var i=0;i<f.length;i=++i)c(a(f[i])),f[i]===b[0]&&(g=i)}else if("lightbox"===b.attr("rel"))c(b);else{f=a(b.prop("tagName")+'[rel="'+b.attr("rel")+'"]');for(var j=0;j<f.length;j=++j)c(a(f[j])),f[j]===b[0]&&(g=j)}var k=e.scrollTop()+this.options.positionFromTop,l=e.scrollLeft();this.$lightbox.css({top:k+"px",left:l+"px"}).fadeIn(this.options.fadeDuration),this.changeImage(g)},b.prototype.changeImage=function(b){var c=this;this.disableKeyboardNav();var d=this.$lightbox.find(".lb-image");this.$overlay.fadeIn(this.options.fadeDuration),a(".lb-loader").fadeIn("slow"),this.$lightbox.find(".lb-image, .lb-nav, .lb-prev, .lb-next, .lb-dataContainer, .lb-numbers, .lb-caption").hide(),this.$outerContainer.addClass("animating");var e=new Image;e.onload=function(){var f,g,h,i,j,k,l;d.attr("src",c.album[b].link),f=a(e),d.width(e.width),d.height(e.height),c.options.fitImagesInViewport&&(l=a(window).width(),k=a(window).height(),j=l-c.containerLeftPadding-c.containerRightPadding-20,i=k-c.containerTopPadding-c.containerBottomPadding-120,(e.width>j||e.height>i)&&(e.width/j>e.height/i?(h=j,g=parseInt(e.height/(e.width/h),10),d.width(h),d.height(g)):(g=i,h=parseInt(e.width/(e.height/g),10),d.width(h),d.height(g)))),c.sizeContainer(d.width(),d.height())},e.src=this.album[b].link,this.currentImageIndex=b},b.prototype.sizeOverlay=function(){this.$overlay.width(a(window).width()).height(a(document).height())},b.prototype.sizeContainer=function(a,b){function c(){d.$lightbox.find(".lb-dataContainer").width(g),d.$lightbox.find(".lb-prevLink").height(h),d.$lightbox.find(".lb-nextLink").height(h),d.showImage()}var d=this,e=this.$outerContainer.outerWidth(),f=this.$outerContainer.outerHeight(),g=a+this.containerLeftPadding+this.containerRightPadding,h=b+this.containerTopPadding+this.containerBottomPadding;e!==g||f!==h?this.$outerContainer.animate({width:g,height:h},this.options.resizeDuration,"swing",function(){c()}):c()},b.prototype.showImage=function(){this.$lightbox.find(".lb-loader").hide(),this.$lightbox.find(".lb-image").fadeIn("slow"),this.updateNav(),this.updateDetails(),this.preloadNeighboringImages(),this.enableKeyboardNav()},b.prototype.updateNav=function(){var a=!1;try{document.createEvent("TouchEvent"),a=this.options.alwaysShowNavOnTouchDevices?!0:!1}catch(b){}this.$lightbox.find(".lb-nav").show(),this.album.length>1&&(this.options.wrapAround?(a&&this.$lightbox.find(".lb-prev, .lb-next").css("opacity","1"),this.$lightbox.find(".lb-prev, .lb-next").show()):(this.currentImageIndex>0&&(this.$lightbox.find(".lb-prev").show(),a&&this.$lightbox.find(".lb-prev").css("opacity","1")),this.currentImageIndex<this.album.length-1&&(this.$lightbox.find(".lb-next").show(),a&&this.$lightbox.find(".lb-next").css("opacity","1"))))},b.prototype.updateDetails=function(){var b=this;"undefined"!=typeof this.album[this.currentImageIndex].title&&""!==this.album[this.currentImageIndex].title&&this.$lightbox.find(".lb-caption").html(this.album[this.currentImageIndex].title).fadeIn("fast").find("a").on("click",function(){location.href=a(this).attr("href")}),this.album.length>1&&this.options.showImageNumberLabel?this.$lightbox.find(".lb-number").text(this.options.albumLabel(this.currentImageIndex+1,this.album.length)).fadeIn("fast"):this.$lightbox.find(".lb-number").hide(),this.$outerContainer.removeClass("animating"),this.$lightbox.find(".lb-dataContainer").fadeIn(this.options.resizeDuration,function(){return b.sizeOverlay()})},b.prototype.preloadNeighboringImages=function(){if(this.album.length>this.currentImageIndex+1){var a=new Image;a.src=this.album[this.currentImageIndex+1].link}if(this.currentImageIndex>0){var b=new Image;b.src=this.album[this.currentImageIndex-1].link}},b.prototype.enableKeyboardNav=function(){a(document).on("keyup.keyboard",a.proxy(this.keyboardAction,this))},b.prototype.disableKeyboardNav=function(){a(document).off(".keyboard")},b.prototype.keyboardAction=function(a){var b=27,c=37,d=39,e=a.keyCode,f=String.fromCharCode(e).toLowerCase();e===b||f.match(/x|o|c/)?this.end():"p"===f||e===c?0!==this.currentImageIndex?this.changeImage(this.currentImageIndex-1):this.options.wrapAround&&this.album.length>1&&this.changeImage(this.album.length-1):("n"===f||e===d)&&(this.currentImageIndex!==this.album.length-1?this.changeImage(this.currentImageIndex+1):this.options.wrapAround&&this.album.length>1&&this.changeImage(0))},b.prototype.end=function(){this.disableKeyboardNav(),a(window).off("resize",this.sizeOverlay),this.$lightbox.fadeOut(this.options.fadeDuration),this.$overlay.fadeOut(this.options.fadeDuration),a("select, object, embed").css({visibility:"visible"})},b}();a(function(){{var a=new b;new c(a)}})}).call(this);
//# sourceMappingURL=lightbox.min.map

/* ===================================================
 *  jquery-sortable.js v0.9.12
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
!function(d,y,k,j){function s(a,b){var c=Math.max(0,a[0]-b[0],b[0]-a[1]),e=Math.max(0,a[2]-b[1],b[1]-a[3]);return c+e}function t(a,b,c,e){for(var h=a.length,e=e?"offset":"position",c=c||0;h--;){var f=a[h].el?a[h].el:d(a[h]),i=f[e]();i.left+=parseInt(f.css("margin-left"),10);i.top+=parseInt(f.css("margin-top"),10);b[h]=[i.left-c,i.left+f.outerWidth()+c,i.top-c,i.top+f.outerHeight()+c]}}function m(a,b){var c=b.offset();return{left:a.left-c.left,top:a.top-c.top}}function u(a,b,c){for(var b=[b.left,b.top],
c=c&&[c.left,c.top],e,h=a.length,d=[];h--;)e=a[h],d[h]=[h,s(e,b),c&&s(e,c)];return d=d.sort(function(a,b){return b[1]-a[1]||b[2]-a[2]||b[0]-a[0]})}function n(a){this.options=d.extend({},l,a);this.containers=[];this.options.rootGroup||(this.scrollProxy=d.proxy(this.scroll,this),this.dragProxy=d.proxy(this.drag,this),this.dropProxy=d.proxy(this.drop,this),this.placeholder=d(this.options.placeholder),a.isValidTarget||(this.options.isValidTarget=j))}function q(a,b){this.el=a;this.options=d.extend({},
w,b);this.group=n.get(this.options);this.rootGroup=this.options.rootGroup||this.group;this.handle=this.rootGroup.options.handle||this.rootGroup.options.itemSelector;var c=this.rootGroup.options.itemPath;this.target=c?this.el.find(c):this.el;this.target.on(o.start,this.handle,d.proxy(this.dragInit,this));this.options.drop&&this.group.containers.push(this)}var o,w={drag:!0,drop:!0,exclude:"",nested:!0,vertical:!0},l={afterMove:function(){},containerPath:"",containerSelector:"ol, ul",distance:0,delay:0,
handle:"",itemPath:"",itemSelector:"li",isValidTarget:function(){return!0},onCancel:function(){},onDrag:function(a,b){a.css(b)},onDragStart:function(a){a.css({height:a.height(),width:a.width()});a.addClass("dragged");d("body").addClass("dragging")},onDrop:function(a){a.removeClass("dragged").removeAttr("style");d("body").removeClass("dragging")},onMousedown:function(a,b,c){if(!c.target.nodeName.match(/^(input|select)$/i))return c.preventDefault(),!0},placeholder:'<li class="placeholder"/>',pullPlaceholder:!0,
serialize:function(a,b,c){a=d.extend({},a.data());if(c)return[b];b[0]&&(a.children=b);delete a.subContainers;delete a.sortable;return a},tolerance:0},p={},v=0,x={left:0,top:0,bottom:0,right:0};o={start:"touchstart.sortable mousedown.sortable",drop:"touchend.sortable touchcancel.sortable mouseup.sortable",drag:"touchmove.sortable mousemove.sortable",scroll:"scroll.sortable"};n.get=function(a){p[a.group]||(a.group===j&&(a.group=v++),p[a.group]=new n(a));return p[a.group]};n.prototype={dragInit:function(a,
b){this.$document=d(b.el[0].ownerDocument);this.item=d(a.target).closest(this.options.itemSelector);this.itemContainer=b;!this.item.is(this.options.exclude)&&this.options.onMousedown(this.item,l.onMousedown,a)&&(this.setPointer(a),this.toggleListeners("on"),this.setupDelayTimer(),this.dragInitDone=!0)},drag:function(a){if(!this.dragging){if(!this.distanceMet(a)||!this.delayMet)return;this.options.onDragStart(this.item,this.itemContainer,l.onDragStart,a);this.item.before(this.placeholder);this.dragging=
!0}this.setPointer(a);this.options.onDrag(this.item,m(this.pointer,this.item.offsetParent()),l.onDrag,a);var b=a.pageX||a.originalEvent.pageX,a=a.pageY||a.originalEvent.pageY,c=this.sameResultBox,e=this.options.tolerance;if(!c||c.top-e>a||c.bottom+e<a||c.left-e>b||c.right+e<b)this.searchValidTarget()||this.placeholder.detach()},drop:function(a){this.toggleListeners("off");this.dragInitDone=!1;if(this.dragging){if(this.placeholder.closest("html")[0])this.placeholder.before(this.item).detach();else this.options.onCancel(this.item,
this.itemContainer,l.onCancel,a);this.options.onDrop(this.item,this.getContainer(this.item),l.onDrop,a);this.clearDimensions();this.clearOffsetParent();this.lastAppendedItem=this.sameResultBox=j;this.dragging=!1}},searchValidTarget:function(a,b){a||(a=this.relativePointer||this.pointer,b=this.lastRelativePointer||this.lastPointer);for(var c=u(this.getContainerDimensions(),a,b),e=c.length;e--;){var d=c[e][0];if(!c[e][1]||this.options.pullPlaceholder)if(d=this.containers[d],!d.disabled){if(!this.$getOffsetParent())var f=
d.getItemOffsetParent(),a=m(a,f),b=m(b,f);if(d.searchValidTarget(a,b))return!0}}this.sameResultBox&&(this.sameResultBox=j)},movePlaceholder:function(a,b,c,e){var d=this.lastAppendedItem;if(e||!(d&&d[0]===b[0]))b[c](this.placeholder),this.lastAppendedItem=b,this.sameResultBox=e,this.options.afterMove(this.placeholder,a,b)},getContainerDimensions:function(){this.containerDimensions||t(this.containers,this.containerDimensions=[],this.options.tolerance,!this.$getOffsetParent());return this.containerDimensions},
getContainer:function(a){return a.closest(this.options.containerSelector).data(k)},$getOffsetParent:function(){if(this.offsetParent===j){var a=this.containers.length-1,b=this.containers[a].getItemOffsetParent();if(!this.options.rootGroup)for(;a--;)if(b[0]!=this.containers[a].getItemOffsetParent()[0]){b=!1;break}this.offsetParent=b}return this.offsetParent},setPointer:function(a){a=this.getPointer(a);if(this.$getOffsetParent()){var b=m(a,this.$getOffsetParent());this.lastRelativePointer=this.relativePointer;
this.relativePointer=b}this.lastPointer=this.pointer;this.pointer=a},distanceMet:function(a){a=this.getPointer(a);return Math.max(Math.abs(this.pointer.left-a.left),Math.abs(this.pointer.top-a.top))>=this.options.distance},getPointer:function(a){return{left:a.pageX||a.originalEvent.pageX,top:a.pageY||a.originalEvent.pageY}},setupDelayTimer:function(){var a=this;this.delayMet=!this.options.delay;this.delayMet||(clearTimeout(this._mouseDelayTimer),this._mouseDelayTimer=setTimeout(function(){a.delayMet=
!0},this.options.delay))},scroll:function(){this.clearDimensions();this.clearOffsetParent()},toggleListeners:function(a){var b=this;d.each(["drag","drop","scroll"],function(c,e){b.$document[a](o[e],b[e+"Proxy"])})},clearOffsetParent:function(){this.offsetParent=j},clearDimensions:function(){this.traverse(function(a){a._clearDimensions()})},traverse:function(a){a(this);for(var b=this.containers.length;b--;)this.containers[b].traverse(a)},_clearDimensions:function(){this.containerDimensions=j},_destroy:function(){p[this.options.group]=
j}};q.prototype={dragInit:function(a){var b=this.rootGroup;!this.disabled&&!b.dragInitDone&&this.options.drag&&this.isValidDrag(a)&&b.dragInit(a,this)},isValidDrag:function(a){return 1==a.which||"touchstart"==a.type&&1==a.originalEvent.touches.length},searchValidTarget:function(a,b){var c=u(this.getItemDimensions(),a,b),e=c.length,d=this.rootGroup,f=!d.options.isValidTarget||d.options.isValidTarget(d.item,this);if(!e&&f)return d.movePlaceholder(this,this.target,"append"),!0;for(;e--;)if(d=c[e][0],
!c[e][1]&&this.hasChildGroup(d)){if(this.getContainerGroup(d).searchValidTarget(a,b))return!0}else if(f)return this.movePlaceholder(d,a),!0},movePlaceholder:function(a,b){var c=d(this.items[a]),e=this.itemDimensions[a],h="after",f=c.outerWidth(),i=c.outerHeight(),g=c.offset(),g={left:g.left,right:g.left+f,top:g.top,bottom:g.top+i};this.options.vertical?b.top<=(e[2]+e[3])/2?(h="before",g.bottom-=i/2):g.top+=i/2:b.left<=(e[0]+e[1])/2?(h="before",g.right-=f/2):g.left+=f/2;this.hasChildGroup(a)&&(g=x);
this.rootGroup.movePlaceholder(this,c,h,g)},getItemDimensions:function(){this.itemDimensions||(this.items=this.$getChildren(this.el,"item").filter(":not(.placeholder, .dragged)").get(),t(this.items,this.itemDimensions=[],this.options.tolerance));return this.itemDimensions},getItemOffsetParent:function(){var a=this.el;return"relative"===a.css("position")||"absolute"===a.css("position")||"fixed"===a.css("position")?a:a.offsetParent()},hasChildGroup:function(a){return this.options.nested&&this.getContainerGroup(a)},
getContainerGroup:function(a){var b=d.data(this.items[a],"subContainers");if(b===j){var c=this.$getChildren(this.items[a],"container"),b=!1;c[0]&&(b=d.extend({},this.options,{rootGroup:this.rootGroup,group:v++}),b=c[k](b).data(k).group);d.data(this.items[a],"subContainers",b)}return b},$getChildren:function(a,b){var c=this.rootGroup.options,e=c[b+"Path"],c=c[b+"Selector"],a=d(a);e&&(a=a.find(e));return a.children(c)},_serialize:function(a,b){var c=this,e=this.$getChildren(a,b?"item":"container").not(this.options.exclude).map(function(){return c._serialize(d(this),
!b)}).get();return this.rootGroup.options.serialize(a,e,b)},traverse:function(a){d.each(this.items||[],function(){var b=d.data(this,"subContainers");b&&b.traverse(a)});a(this)},_clearDimensions:function(){this.itemDimensions=j},_destroy:function(){var a=this;this.target.off(o.start,this.handle);this.el.removeData(k);this.options.drop&&(this.group.containers=d.grep(this.group.containers,function(b){return b!=a}));d.each(this.items||[],function(){d.removeData(this,"subContainers")})}};var r={enable:function(){this.traverse(function(a){a.disabled=
!1})},disable:function(){this.traverse(function(a){a.disabled=!0})},serialize:function(){return this._serialize(this.el,!0)},refresh:function(){this.traverse(function(a){a._clearDimensions()})},destroy:function(){this.traverse(function(a){a._destroy()})}};d.extend(q.prototype,r);d.fn[k]=function(a){var b=Array.prototype.slice.call(arguments,1);return this.map(function(){var c=d(this),e=c.data(k);if(e&&r[a])return r[a].apply(e,b)||this;!e&&(a===j||"object"===typeof a)&&c.data(k,new q(c,a));return this})}}(jQuery,
window,"sortable");

//Recherche d'une chaîne dans une autre.
function strpos(haystack, needle)
{
    var i = haystack.indexOf(needle, 0); // returns -1
    return i >= 0 ? i : false;
}

//Affichage/Masquage de la balise hide.
function bb_hide(div2)
{
	var divs = div2.getElementsByTagName('div');
	var div3 = divs[0];
	if (div3.style.visibility == 'visible')
	{
		div3.style.visibility = 'hidden';
		div2.style.height = '10px';
		div2.style.overflow = 'hidden';
	}
	else
	{	
		div3.style.visibility = 'visible';
		div2.style.height = 'auto';
		div2.style.overflow = 'auto';
	}
	
	return true;
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
			jQuery('#search_img' + searchid).text('<i class="fa fa-spinner fa-spin"></i>');

		jQuery.ajax({
			url: PATH_TO_ROOT + '/kernel/framework/ajax/member_xmlhttprequest.php?token=' + TOKEN + '&' + insert_mode + '=1',
			type: "post",
			dataType: "html",
			data: {'login': login, 'divid' : searchid},
			success: function(returnData){
				if (jQuery('#search_img' + searchid))
					jQuery('#search_img' + searchid).text('');

				if (jQuery("#xmlhttprequest-result-search" + searchid))
					jQuery("#xmlhttprequest-result-search" + searchid).html(returnData);

				jQuery('#xmlhttprequest-result-search').fadeIn();
			},
			error: function(e){
				alert(e);
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
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Steve Clay
    // +   improved by: Legaev Andrey
    // *     example 1: function_exists('isFinite');
    // *     returns 1: true 
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

var delay = 1000; //Délai après lequel le bloc est automatiquement masqué, après le départ de la souris.
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
		timeout = setTimeout('bb_display_block(\'' + bbfield + '\',  \'' + field + '\')', delay);
	}	
}