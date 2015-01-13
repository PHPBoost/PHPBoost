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
	var login = $('login' + searchid).value;
	if (login != '')
	{
		if ($('search_img' + searchid))
			$('search_img' + searchid).innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
		var xhr_object = xmlhttprequest_init(PATH_TO_ROOT + '/kernel/framework/ajax/member_xmlhttprequest.php?token=' + TOKEN + '&' + insert_mode + '=1');
		data = 'login=' + login + '&divid=' + searchid;
		xhr_object.onreadystatechange = function() 
		{
			if (xhr_object.readyState == 4 && xhr_object.status == 200) 
			{
				if ($('search_img' + searchid))
					$('search_img' + searchid).innerHTML = '';
				if ($("xmlhttprequest-result-search" + searchid))
					$("xmlhttprequest-result-search" + searchid).innerHTML = xhr_object.responseText;
				jQuery('#xmlhttprequest-result-search' + searchid).slideDown();
			}
			else if (xhr_object.readyState == 4) 
			{
				if ($('search_img' + searchid))
					$('search_img' + searchid).innerHTML = '';
			}
		}
		xmlhttprequest_sender(xhr_object, data);
	}	
	else
		alert(alert_empty_login);
}

//Fonction d'ajout de membre dans les autorisations.
function XMLHttpRequest_add_member_auth(searchid, user_id, login, alert_already_auth)
{
    var selectid = $('members_auth' + searchid);
    for(var i = 0; i < selectid.length; i++) //Vérifie que le membre n'est pas déjà dans la liste.
    {
        if (selectid[i].value == user_id)
        {
            alert(alert_already_auth);
            return;
        }
    }
    var oOption = new Option(login, user_id);
    oOption.id = searchid + 'm' + (selectid.length - 1);
        oOption.selected = true;

    if ($('members_auth' + searchid)) //Ajout du membre.
        $('members_auth' + searchid).options[selectid.length] = oOption;
}

//Sélection des formulaires.
function check_select_multiple(id, status)
{
	var i;	

	//Sélection des groupes.
	var selectidgroups = $('groups_auth' + id);
	for(i = 0; i < selectidgroups.length; i++)
	{	
		if (selectidgroups[i])
			selectidgroups[i].selected = status;
	}
	
	//Sélection des membres.
	var selectidmember = $('members_auth' + id);
	for(i = 0; i < selectidmember.length; i++)
	{	
		if (selectidmember[i])
			selectidmember[i].selected = status;
	}	
}

//Sélection auto des rangs supérieur à celui cliqué.
function check_select_multiple_ranks(id, start)
{
	var i;			
	for(i = start; i <= 2; i++)
	{	
		if ($(id + i))
			$(id + i).selected = true;
	}
}

// Crée un lien de pagination javascript
function writePagin(fctName, fctArgs, isCurrentPage, textPagin, i)
{
    pagin = '<span class="pagination';
    if ( isCurrentPage)
        pagin += ' pagination_current_page text-strong';
    pagin += '">';
    pagin += '<a href="javascript:' + fctName + '(' + i + fctArgs + ')">' + textPagin + '</a>';
    pagin += '</span>&nbsp;';
    
    return pagin;
}

// Crée la pagination à partir du nom du bloc de page, du bloc de pagination, du nombre de résultats
// du nombre de résultats par page ...
function ChangePagination(page, nbPages, blocPagin, blocName, nbPagesBefore, nbPagesAfter)
{
    var pagin = '';
    if ( nbPages > 1)
    {
        if (arguments.length < 5)
        {
            nbPagesBefore = 3;
            nbPagesAfter = 3;
        }
        
        var before = Math.max(0, page - nbPagesBefore);
        var after = Math.min(nbPages, page + nbPagesAfter + 1);
        
        var fctName = 'ChangePagination';
        var fctArgs = ', '  + nbPages + ', \'' + blocPagin + '\', \'' + blocName + '\', ' + nbPagesBefore + ', ' + nbPagesAfter;
        
        // Début
        if (page != 0)
            pagin += writePagin(fctName, fctArgs, false, '&laquo;', 0);
        
        // Before
        for ( var i = before; i < page; i++)
            pagin += writePagin(fctName, fctArgs, false, i + 1, i);
        
        // Page courante
        pagin += writePagin(fctName, fctArgs, true, page + 1, page);
        
        // After
        for ( var i = page + 1; i < after; i++)
            pagin += writePagin(fctName, fctArgs, false, i + 1, i);
        
        // Fin
        if (page != nbPages - 1)
            pagin += writePagin(fctName, fctArgs, false, '&raquo;', nbPages - 1);
    }
    
    // On cache tous les autre résultats du module
    for ( var i = 0; i < nbPages; i++)
    	jQuery('#' + blocName + '_' + i).fadeOut();
        
    // On montre la page demandée
    jQuery('#' + blocName + '_' + page).fadeIn();
    
    // Mise à jour de la pagination
    $(blocPagin).innerHTML = pagin;
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