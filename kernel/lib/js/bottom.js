/*! jQuery Mobile v1.4.5 | Copyright 2010, 2014 jQuery Foundation, Inc. | jquery.org/license */

(function(e,t,n){typeof define=="function"&&define.amd?define(["jquery"],function(r){return n(r,e,t),r.mobile}):n(e.jQuery,e,t)})(this,document,function(e,t,n,r){(function(e,t,n,r){function T(e){while(e&&typeof e.originalEvent!="undefined")e=e.originalEvent;return e}function N(t,n){var i=t.type,s,o,a,l,c,h,p,d,v;t=e.Event(t),t.type=n,s=t.originalEvent,o=e.event.props,i.search(/^(mouse|click)/)>-1&&(o=f);if(s)for(p=o.length,l;p;)l=o[--p],t[l]=s[l];i.search(/mouse(down|up)|click/)>-1&&!t.which&&(t.which=1);if(i.search(/^touch/)!==-1){a=T(s),i=a.touches,c=a.changedTouches,h=i&&i.length?i[0]:c&&c.length?c[0]:r;if(h)for(d=0,v=u.length;d<v;d++)l=u[d],t[l]=h[l]}return t}function C(t){var n={},r,s;while(t){r=e.data(t,i);for(s in r)r[s]&&(n[s]=n.hasVirtualBinding=!0);t=t.parentNode}return n}function k(t,n){var r;while(t){r=e.data(t,i);if(r&&(!n||r[n]))return t;t=t.parentNode}return null}function L(){g=!1}function A(){g=!0}function O(){E=0,v.length=0,m=!1,A()}function M(){L()}function _(){D(),c=setTimeout(function(){c=0,O()},e.vmouse.resetTimerDuration)}function D(){c&&(clearTimeout(c),c=0)}function P(t,n,r){var i;if(r&&r[t]||!r&&k(n.target,t))i=N(n,t),e(n.target).trigger(i);return i}function H(t){var n=e.data(t.target,s),r;!m&&(!E||E!==n)&&(r=P("v"+t.type,t),r&&(r.isDefaultPrevented()&&t.preventDefault(),r.isPropagationStopped()&&t.stopPropagation(),r.isImmediatePropagationStopped()&&t.stopImmediatePropagation()))}function B(t){var n=T(t).touches,r,i,o;n&&n.length===1&&(r=t.target,i=C(r),i.hasVirtualBinding&&(E=w++,e.data(r,s,E),D(),M(),d=!1,o=T(t).touches[0],h=o.pageX,p=o.pageY,P("vmouseover",t,i),P("vmousedown",t,i)))}function j(e){if(g)return;d||P("vmousecancel",e,C(e.target)),d=!0,_()}function F(t){if(g)return;var n=T(t).touches[0],r=d,i=e.vmouse.moveDistanceThreshold,s=C(t.target);d=d||Math.abs(n.pageX-h)>i||Math.abs(n.pageY-p)>i,d&&!r&&P("vmousecancel",t,s),P("vmousemove",t,s),_()}function I(e){if(g)return;A();var t=C(e.target),n,r;P("vmouseup",e,t),d||(n=P("vclick",e,t),n&&n.isDefaultPrevented()&&(r=T(e).changedTouches[0],v.push({touchID:E,x:r.clientX,y:r.clientY}),m=!0)),P("vmouseout",e,t),d=!1,_()}function q(t){var n=e.data(t,i),r;if(n)for(r in n)if(n[r])return!0;return!1}function R(){}function U(t){var n=t.substr(1);return{setup:function(){q(this)||e.data(this,i,{});var r=e.data(this,i);r[t]=!0,l[t]=(l[t]||0)+1,l[t]===1&&b.bind(n,H),e(this).bind(n,R),y&&(l.touchstart=(l.touchstart||0)+1,l.touchstart===1&&b.bind("touchstart",B).bind("touchend",I).bind("touchmove",F).bind("scroll",j))},teardown:function(){--l[t],l[t]||b.unbind(n,H),y&&(--l.touchstart,l.touchstart||b.unbind("touchstart",B).unbind("touchmove",F).unbind("touchend",I).unbind("scroll",j));var r=e(this),s=e.data(this,i);s&&(s[t]=!1),r.unbind(n,R),q(this)||r.removeData(i)}}}var i="virtualMouseBindings",s="virtualTouchID",o="vmouseover vmousedown vmousemove vmouseup vclick vmouseout vmousecancel".split(" "),u="clientX clientY pageX pageY screenX screenY".split(" "),a=e.event.mouseHooks?e.event.mouseHooks.props:[],f=e.event.props.concat(a),l={},c=0,h=0,p=0,d=!1,v=[],m=!1,g=!1,y="addEventListener"in n,b=e(n),w=1,E=0,S,x;e.vmouse={moveDistanceThreshold:10,clickDistanceThreshold:10,resetTimerDuration:1500};for(x=0;x<o.length;x++)e.event.special[o[x]]=U(o[x]);y&&n.addEventListener("click",function(t){var n=v.length,r=t.target,i,o,u,a,f,l;if(n){i=t.clientX,o=t.clientY,S=e.vmouse.clickDistanceThreshold,u=r;while(u){for(a=0;a<n;a++){f=v[a],l=0;if(u===r&&Math.abs(f.x-i)<S&&Math.abs(f.y-o)<S||e.data(u,s)===f.touchID){t.preventDefault(),t.stopPropagation();return}}u=u.parentNode}}},!0)})(e,t,n),function(e){e.mobile={}}(e),function(e,t){var r={touch:"ontouchend"in n};e.mobile.support=e.mobile.support||{},e.extend(e.support,r),e.extend(e.mobile.support,r)}(e),function(e,t,r){function l(t,n,i,s){var o=i.type;i.type=n,s?e.event.trigger(i,r,t):e.event.dispatch.call(t,i),i.type=o}var i=e(n),s=e.mobile.support.touch,o="touchmove scroll",u=s?"touchstart":"mousedown",a=s?"touchend":"mouseup",f=s?"touchmove":"mousemove";e.each("touchstart touchmove touchend tap taphold swipe swipeleft swiperight scrollstart scrollstop".split(" "),function(t,n){e.fn[n]=function(e){return e?this.bind(n,e):this.trigger(n)},e.attrFn&&(e.attrFn[n]=!0)}),e.event.special.scrollstart={enabled:!0,setup:function(){function s(e,n){r=n,l(t,r?"scrollstart":"scrollstop",e)}var t=this,n=e(t),r,i;n.bind(o,function(t){if(!e.event.special.scrollstart.enabled)return;r||s(t,!0),clearTimeout(i),i=setTimeout(function(){s(t,!1)},50)})},teardown:function(){e(this).unbind(o)}},e.event.special.tap={tapholdThreshold:750,emitTapOnTaphold:!0,setup:function(){var t=this,n=e(t),r=!1;n.bind("vmousedown",function(s){function a(){clearTimeout(u)}function f(){a(),n.unbind("vclick",c).unbind("vmouseup",a),i.unbind("vmousecancel",f)}function c(e){f(),!r&&o===e.target?l(t,"tap",e):r&&e.preventDefault()}r=!1;if(s.which&&s.which!==1)return!1;var o=s.target,u;n.bind("vmouseup",a).bind("vclick",c),i.bind("vmousecancel",f),u=setTimeout(function(){e.event.special.tap.emitTapOnTaphold||(r=!0),l(t,"taphold",e.Event("taphold",{target:o}))},e.event.special.tap.tapholdThreshold)})},teardown:function(){e(this).unbind("vmousedown").unbind("vclick").unbind("vmouseup"),i.unbind("vmousecancel")}},e.event.special.swipe={scrollSupressionThreshold:30,durationThreshold:1e3,horizontalDistanceThreshold:30,verticalDistanceThreshold:30,getLocation:function(e){var n=t.pageXOffset,r=t.pageYOffset,i=e.clientX,s=e.clientY;if(e.pageY===0&&Math.floor(s)>Math.floor(e.pageY)||e.pageX===0&&Math.floor(i)>Math.floor(e.pageX))i-=n,s-=r;else if(s<e.pageY-r||i<e.pageX-n)i=e.pageX-n,s=e.pageY-r;return{x:i,y:s}},start:function(t){var n=t.originalEvent.touches?t.originalEvent.touches[0]:t,r=e.event.special.swipe.getLocation(n);return{time:(new Date).getTime(),coords:[r.x,r.y],origin:e(t.target)}},stop:function(t){var n=t.originalEvent.touches?t.originalEvent.touches[0]:t,r=e.event.special.swipe.getLocation(n);return{time:(new Date).getTime(),coords:[r.x,r.y]}},handleSwipe:function(t,n,r,i){if(n.time-t.time<e.event.special.swipe.durationThreshold&&Math.abs(t.coords[0]-n.coords[0])>e.event.special.swipe.horizontalDistanceThreshold&&Math.abs(t.coords[1]-n.coords[1])<e.event.special.swipe.verticalDistanceThreshold){var s=t.coords[0]>n.coords[0]?"swipeleft":"swiperight";return l(r,"swipe",e.Event("swipe",{target:i,swipestart:t,swipestop:n}),!0),l(r,s,e.Event(s,{target:i,swipestart:t,swipestop:n}),!0),!0}return!1},eventInProgress:!1,setup:function(){var t,n=this,r=e(n),s={};t=e.data(this,"mobile-events"),t||(t={length:0},e.data(this,"mobile-events",t)),t.length++,t.swipe=s,s.start=function(t){if(e.event.special.swipe.eventInProgress)return;e.event.special.swipe.eventInProgress=!0;var r,o=e.event.special.swipe.start(t),u=t.target,l=!1;s.move=function(t){if(!o||t.isDefaultPrevented())return;r=e.event.special.swipe.stop(t),l||(l=e.event.special.swipe.handleSwipe(o,r,n,u),l&&(e.event.special.swipe.eventInProgress=!1)),Math.abs(o.coords[0]-r.coords[0])>e.event.special.swipe.scrollSupressionThreshold&&t.preventDefault()},s.stop=function(){l=!0,e.event.special.swipe.eventInProgress=!1,i.off(f,s.move),s.move=null},i.on(f,s.move).one(a,s.stop)},r.on(u,s.start)},teardown:function(){var t,n;t=e.data(this,"mobile-events"),t&&(n=t.swipe,delete t.swipe,t.length--,t.length===0&&e.removeData(this,"mobile-events")),n&&(n.start&&e(this).off(u,n.start),n.move&&i.off(f,n.move),n.stop&&i.off(a,n.stop))}},e.each({scrollstop:"scrollstart",taphold:"tap",swipeleft:"swipe.left",swiperight:"swipe.right"},function(t,n){e.event.special[t]={setup:function(){e(this).bind(n,e.noop)},teardown:function(){e(this).unbind(n)}}})}(e,this)});

/*! Lightcase v2.1.0 | Copyright 2015 Cornel Boppart <cornel@bopp-art.com> */
!function(t){window.lightcase={cache:{},support:{},init:function(e){return this.each(function(){t(this).unbind("click").click(function(i){i.preventDefault(),t(this).lightcase("start",e)})})},start:function(e){lightcase.settings=t.extend(!0,{idPrefix:"lightcase-",classPrefix:"lightcase-",labels:{errorMessage:"Source could not be found...","sequenceInfo.of":" of ",close:"Close","navigator.prev":"Prev","navigator.next":"Next","navigator.play":"Play","navigator.pause":"Pause"},transition:"elastic",transitionIn:null,transitionOut:null,cssTransitions:!0,speedIn:250,speedOut:250,maxWidth:800,maxHeight:500,forceWidth:!1,forceHeight:!1,liveResize:!0,fullScreenModeForMobile:!0,mobileMatchExpression:/(iphone|ipod|ipad|android|blackberry|symbian)/,disableShrink:!1,shrinkFactor:.75,overlayOpacity:.9,slideshow:!1,timeout:5e3,swipe:!0,useKeys:!0,navigateEndless:!0,closeOnOverlayClick:!0,title:null,caption:null,showTitle:!0,showCaption:!0,showSequenceInfo:!0,inline:{width:"auto",height:"auto"},ajax:{width:"auto",height:"auto",type:"get",dataType:"html",data:{}},iframe:{width:800,height:500,frameborder:0},flash:{width:400,height:205,wmode:"transparent"},video:{width:400,height:225,poster:"",preload:"auto",controls:!0,autobuffer:!0,autoplay:!0,loop:!1},attr:"data-rel",href:null,type:null,typeMapping:{image:"jpg,jpeg,gif,png,bmp",flash:"swf",video:"mp4,mov,ogv,ogg,webm",iframe:"html,php",ajax:"json,txt",inline:"#"},errorMessage:function(){return'<p class="'+lightcase.settings.classPrefix+'error">'+lightcase.settings.labels.errorMessage+"</p>"},markup:function(){t("body").append($overlay=t('<div id="'+lightcase.settings.idPrefix+'overlay"></div>'),$loading=t('<div id="'+lightcase.settings.idPrefix+'loading" class="'+lightcase.settings.classPrefix+'icon-spin"></div>'),$case=t('<div id="'+lightcase.settings.idPrefix+'case" aria-hidden="true" role="dialog"></div>')),$case.after($nav=t('<div id="'+lightcase.settings.idPrefix+'nav"></div>')),$nav.append($close=t('<a href="#" class="'+lightcase.settings.classPrefix+'icon-close"><span>'+lightcase.settings.labels.close+"</span></a>"),$prev=t('<a href="#" class="'+lightcase.settings.classPrefix+'icon-prev"><span>'+lightcase.settings.labels["navigator.prev"]+"</span></a>").hide(),$next=t('<a href="#" class="'+lightcase.settings.classPrefix+'icon-next"><span>'+lightcase.settings.labels["navigator.next"]+"</span></a>").hide(),$play=t('<a href="#" class="'+lightcase.settings.classPrefix+'icon-play"><span>'+lightcase.settings.labels["navigator.play"]+"</span></a>").hide(),$pause=t('<a href="#" class="'+lightcase.settings.classPrefix+'icon-pause"><span>'+lightcase.settings.labels["navigator.pause"]+"</span></a>").hide()),$case.append($content=t('<div class="'+lightcase.settings.classPrefix+'content"></div>'),$info=t('<div class="'+lightcase.settings.classPrefix+'info"></div>')),$content.append($contentInner=t('<div class="'+lightcase.settings.classPrefix+'contentInner"></div>')),$info.append($sequenceInfo=t('<div class="'+lightcase.settings.classPrefix+'sequenceInfo"></div>'),$title=t('<h4 class="'+lightcase.settings.classPrefix+'title"></h4>'),$caption=t('<p class="'+lightcase.settings.classPrefix+'caption"></p>'))},onInit:{},onStart:{},onFinish:{},onClose:{},onCleanup:{}},e),lightcase.callHooks(lightcase.settings.onInit),lightcase.objectData=lightcase.getObjectData(this),lightcase.cacheScrollPosition(),lightcase.watchScrollInteraction(),lightcase.addElements(),lightcase.lightcaseOpen(),lightcase.dimensions=lightcase.getDimensions()},getObjectData:function(e){var i={$link:e,title:lightcase.settings.title||e.attr("title"),caption:lightcase.settings.caption||e.children("img").attr("alt"),url:lightcase.verifyDataUrl(lightcase.settings.href||e.attr("data-href")||e.attr("href")),requestType:lightcase.settings.ajax.type,requestData:lightcase.settings.ajax.data,requestDataType:lightcase.settings.ajax.dataType,rel:e.attr(lightcase.settings.attr),type:lightcase.settings.type||lightcase.verifyDataType(e.attr("data-href")||e.attr("href")),isPartOfSequence:lightcase.isPartOfSequence(e.attr(lightcase.settings.attr),":"),isPartOfSequenceWithSlideshow:lightcase.isPartOfSequence(e.attr(lightcase.settings.attr),":slideshow"),currentIndex:t("["+lightcase.settings.attr+'="'+e.attr(lightcase.settings.attr)+'"]').index(e),sequenceLength:t("["+lightcase.settings.attr+'="'+e.attr(lightcase.settings.attr)+'"]').length};return i.sequenceInfo=i.currentIndex+1+lightcase.settings.labels["sequenceInfo.of"]+i.sequenceLength,i},isPartOfSequence:function(e,i){var s=t("["+lightcase.settings.attr+'="'+e+'"]'),a=new RegExp(i);return a.test(e)&&s.length>1?!0:!1},isSlideshowEnabled:function(){return!lightcase.objectData.isPartOfSequence||lightcase.settings.slideshow!==!0&&lightcase.objectData.isPartOfSequenceWithSlideshow!==!0?!1:!0},loadContent:function(){lightcase.cache.originalObject&&lightcase.restoreObject(),lightcase.createObject()},createObject:function(){var e;switch(lightcase.objectData.type){case"image":e=t(new Image),e.attr({src:lightcase.objectData.url,alt:lightcase.objectData.title});break;case"inline":e=t('<div class="'+lightcase.settings.classPrefix+'inlineWrap"></div>'),e.html(lightcase.cloneObject(t(lightcase.objectData.url))),t.each(lightcase.settings.inline,function(t,i){e.attr("data-"+t,i)});break;case"ajax":e=t('<div class="'+lightcase.settings.classPrefix+'inlineWrap"></div>'),t.each(lightcase.settings.ajax,function(t,i){"data"!==t&&e.attr("data-"+t,i)});break;case"flash":e=t('<embed src="'+lightcase.objectData.url+'" type="application/x-shockwave-flash"></embed>'),t.each(lightcase.settings.flash,function(t,i){e.attr(t,i)});break;case"video":e=t("<video></video>"),e.attr("src",lightcase.objectData.url),t.each(lightcase.settings.video,function(t,i){e.attr(t,i)});break;default:e=t("<iframe></iframe>"),e.attr({src:lightcase.objectData.url}),t.each(lightcase.settings.iframe,function(t,i){e.attr(t,i)})}lightcase.addObject(e),lightcase.loadObject(e)},addObject:function(t){$contentInner.html(t),lightcase.loading("start"),lightcase.callHooks(lightcase.settings.onStart),lightcase.settings.showSequenceInfo===!0&&lightcase.objectData.isPartOfSequence?($sequenceInfo.html(lightcase.objectData.sequenceInfo),$sequenceInfo.show()):($sequenceInfo.empty(),$sequenceInfo.hide()),lightcase.settings.showTitle===!0&&void 0!==lightcase.objectData.title&&""!==lightcase.objectData.title?($title.html(lightcase.objectData.title),$title.show()):($title.empty(),$title.hide()),lightcase.settings.showCaption===!0&&void 0!==lightcase.objectData.caption&&""!==lightcase.objectData.caption?($caption.html(lightcase.objectData.caption),$caption.show()):($caption.empty(),$caption.hide())},loadObject:function(e){switch(lightcase.objectData.type){case"inline":t(lightcase.objectData.url)?lightcase.showContent(e):lightcase.error();break;case"ajax":t.ajax(t.extend({},lightcase.settings.ajax,{url:lightcase.objectData.url,type:lightcase.objectData.requestType,dataType:lightcase.objectData.requestDataType,data:lightcase.objectData.requestData,success:function(t){"json"===lightcase.objectData.requestDataType?lightcase.objectData.data=t:e.html(t),lightcase.showContent(e)},error:function(){lightcase.error()}}));break;case"flash":lightcase.showContent(e);break;case"video":"function"==typeof e.get(0).canPlayType||0===$case.find("video").length?lightcase.showContent(e):lightcase.error();break;default:lightcase.objectData.url?(e.load(function(){lightcase.showContent(e)}),e.error(function(){lightcase.error()})):lightcase.error()}},error:function(){lightcase.objectData.type="error";var e=t('<div class="'+lightcase.settings.classPrefix+'inlineWrap"></div>');e.html(lightcase.settings.errorMessage),$contentInner.html(e),lightcase.showContent($contentInner)},calculateDimensions:function(t){lightcase.cleanupDimensions();var e={objectWidth:t.attr(t.attr("width")?"width":"data-width"),objectHeight:t.attr(t.attr("height")?"height":"data-height"),maxWidth:parseInt(lightcase.dimensions.windowWidth*lightcase.settings.shrinkFactor),maxHeight:parseInt(lightcase.dimensions.windowHeight*lightcase.settings.shrinkFactor)};if(!lightcase.settings.disableShrink)switch(e.maxWidth>lightcase.settings.maxWidth&&(e.maxWidth=lightcase.settings.maxWidth),e.maxHeight>lightcase.settings.maxHeight&&(e.maxHeight=lightcase.settings.maxHeight),e.differenceWidthAsPercent=parseInt(100/e.maxWidth*e.objectWidth),e.differenceHeightAsPercent=parseInt(100/e.maxHeight*e.objectHeight),lightcase.objectData.type){case"image":case"flash":case"video":e.differenceWidthAsPercent>100&&e.differenceWidthAsPercent>e.differenceHeightAsPercent&&(e.objectWidth=e.maxWidth,e.objectHeight=parseInt(e.objectHeight/e.differenceWidthAsPercent*100)),e.differenceHeightAsPercent>100&&e.differenceHeightAsPercent>e.differenceWidthAsPercent&&(e.objectWidth=parseInt(e.objectWidth/e.differenceHeightAsPercent*100),e.objectHeight=e.maxHeight),e.differenceHeightAsPercent>100&&e.differenceWidthAsPercent<e.differenceHeightAsPercent&&(e.objectWidth=parseInt(e.maxWidth/e.differenceHeightAsPercent*e.differenceWidthAsPercent),e.objectHeight=e.maxHeight);break;case"error":!isNaN(e.objectWidth)&&e.objectWidth>e.maxWidth&&(e.objectWidth=e.maxWidth);break;default:(isNaN(e.objectWidth)||e.objectWidth>e.maxWidth)&&!lightcase.settings.forceWidth&&(e.objectWidth=e.maxWidth),(isNaN(e.objectHeight)&&"auto"!==e.objectHeight||e.objectHeight>e.maxHeight)&&!lightcase.settings.forceHeight&&(e.objectHeight=e.maxHeight)}lightcase.adjustDimensions(t,e)},adjustDimensions:function(t,e){t.css({width:e.objectWidth,height:e.objectHeight,"max-width":t.attr("data-max-width")?t.attr("data-max-width"):e.maxWidth,"max-height":t.attr("data-max-height")?t.attr("data-max-height"):e.maxHeight}),$contentInner.css({width:t.outerWidth(),height:t.outerHeight(),"max-width":"100%"}),$case.css({width:$contentInner.outerWidth()}),$case.css({"margin-top":parseInt(-($case.outerHeight()/2)),"margin-left":parseInt(-($case.outerWidth()/2))})},loading:function(t){"start"===t?($case.addClass(lightcase.settings.classPrefix+"loading"),$loading.show()):"end"===t&&($case.removeClass(lightcase.settings.classPrefix+"loading"),$loading.hide())},getDimensions:function(){return{windowWidth:t(window).innerWidth(),windowHeight:t(window).innerHeight()}},verifyDataUrl:function(t){return t&&void 0!==t&&""!==t?(t.indexOf("#")>-1&&(t=t.split("#"),t="#"+t[t.length-1]),t.toString()):!1},verifyDataType:function(t){var t=lightcase.verifyDataUrl(t),e=lightcase.settings.typeMapping;if(t)for(var i in e)for(var s=e[i].split(","),a=0;a<s.length;a++){var c=s[a],n=new RegExp(".("+c+")$","i"),l=t.split("?")[0].substr(-5);if(n.test(l)===!0)return i;if("inline"===i&&(t.indexOf(c)>-1||!t))return i}return"iframe"},addElements:function(){"undefined"!=typeof $case&&t("#"+$case.attr("id")).length||lightcase.settings.markup()},showContent:function(t){switch($case.attr("data-type",lightcase.objectData.type),lightcase.cache.object=t,lightcase.calculateDimensions(t),lightcase.callHooks(lightcase.settings.onFinish),lightcase.settings.transitionIn){case"scrollTop":case"scrollRight":case"scrollBottom":case"scrollLeft":case"scrollHorizontal":case"scrollVertical":lightcase.transition.scroll($case,"in",lightcase.settings.speedIn),lightcase.transition.fade($contentInner,"in",lightcase.settings.speedIn);break;case"elastic":$case.css("opacity")<1&&(lightcase.transition.zoom($case,"in",lightcase.settings.speedIn),lightcase.transition.fade($contentInner,"in",lightcase.settings.speedIn));case"fade":case"fadeInline":lightcase.transition.fade($case,"in",lightcase.settings.speedIn),lightcase.transition.fade($contentInner,"in",lightcase.settings.speedIn);break;default:lightcase.transition.fade($case,"in",0)}lightcase.loading("end"),lightcase.busy=!1},processContent:function(){switch(lightcase.busy=!0,lightcase.settings.transitionOut){case"scrollTop":case"scrollRight":case"scrollBottom":case"scrollLeft":case"scrollVertical":case"scrollHorizontal":$case.is(":hidden")?(lightcase.transition.fade($case,"out",0,0,function(){lightcase.loadContent()}),lightcase.transition.fade($contentInner,"out",0)):lightcase.transition.scroll($case,"out",lightcase.settings.speedOut,function(){lightcase.loadContent()});break;case"fade":$case.is(":hidden")?lightcase.transition.fade($case,"out",0,0,function(){lightcase.loadContent()}):lightcase.transition.fade($case,"out",lightcase.settings.speedOut,0,function(){lightcase.loadContent()});break;case"fadeInline":case"elastic":$case.is(":hidden")?lightcase.transition.fade($case,"out",0,0,function(){lightcase.loadContent()}):lightcase.transition.fade($contentInner,"out",lightcase.settings.speedOut,0,function(){lightcase.loadContent()});break;default:lightcase.transition.fade($case,"out",0,0,function(){lightcase.loadContent()})}},handleEvents:function(){lightcase.unbindEvents(),$nav.children().not($close).hide(),lightcase.isSlideshowEnabled()&&($nav.hasClass(lightcase.settings.classPrefix+"paused")?lightcase.stopTimeout():lightcase.startTimeout()),lightcase.settings.liveResize&&lightcase.watchResizeInteraction(),$close.click(function(t){t.preventDefault(),lightcase.lightcaseClose()}),lightcase.settings.closeOnOverlayClick===!0&&$overlay.css("cursor","pointer").click(function(t){t.preventDefault(),lightcase.lightcaseClose()}),lightcase.settings.useKeys===!0&&lightcase.addKeyEvents(),lightcase.objectData.isPartOfSequence&&($nav.attr("data-ispartofsequence",!0),lightcase.nav=lightcase.setNavigation(),$prev.click(function(t){t.preventDefault(),$prev.unbind("click"),lightcase.cache.action="prev",lightcase.nav.$prevItem.click(),lightcase.isSlideshowEnabled()&&lightcase.stopTimeout()}),$next.click(function(t){t.preventDefault(),$next.unbind("click"),lightcase.cache.action="next",lightcase.nav.$nextItem.click(),lightcase.isSlideshowEnabled()&&lightcase.stopTimeout()}),lightcase.isSlideshowEnabled()&&($play.click(function(t){t.preventDefault(),lightcase.startTimeout()}),$pause.click(function(t){t.preventDefault(),lightcase.stopTimeout()})),lightcase.settings.swipe===!0&&(t.isPlainObject(t.event.special.swipeleft)&&$case.on("swipeleft",function(t){t.preventDefault(),$next.click(),lightcase.isSlideshowEnabled()&&lightcase.stopTimeout()}),t.isPlainObject(t.event.special.swiperight)&&$case.on("swiperight",function(t){t.preventDefault(),$prev.click(),lightcase.isSlideshowEnabled()&&lightcase.stopTimeout()})))},addKeyEvents:function(){t(document).keyup(function(t){if(!lightcase.busy)switch(t.keyCode){case 27:$close.click();break;case 37:lightcase.objectData.isPartOfSequence&&$prev.click();break;case 39:lightcase.objectData.isPartOfSequence&&$next.click()}})},startTimeout:function(){$play.hide(),$pause.show(),lightcase.cache.action="next",$nav.removeClass(lightcase.settings.classPrefix+"paused"),lightcase.timeout=setTimeout(function(){lightcase.nav.$nextItem.click()},lightcase.settings.timeout)},stopTimeout:function(){$play.show(),$pause.hide(),$nav.addClass(lightcase.settings.classPrefix+"paused"),clearTimeout(lightcase.timeout)},setNavigation:function(){var e=t("["+lightcase.settings.attr+'="'+lightcase.objectData.rel+'"]'),i=lightcase.objectData.currentIndex,s=i-1,a=i+1,c=lightcase.objectData.sequenceLength-1,n={$prevItem:e.eq(s),$nextItem:e.eq(a)};return i>0?$prev.show():n.$prevItem=e.eq(c),c>=a?$next.show():n.$nextItem=e.eq(0),lightcase.settings.navigateEndless===!0&&($prev.show(),$next.show()),n},cloneObject:function(t){var e=t.clone(),i=t.attr("id");return t.is(":hidden")?(lightcase.cacheObjectData(t),t.attr("id",lightcase.settings.idPrefix+"temp-"+i).empty()):e.removeAttr("id"),e.show()},isMobileDevice:function(){var t=navigator.userAgent.toLowerCase(),e=t.match(lightcase.settings.mobileMatchExpression);return e?!0:!1},isTransitionSupported:function(){var e=t("body").get(0),i=!1,s={transition:"",WebkitTransition:"-webkit-",MozTransition:"-moz-",OTransition:"-o-",MsTransition:"-ms-"};for(var a in s)s.hasOwnProperty(a)&&a in e.style&&(lightcase.support.transition=s[a],i=!0);return i},transition:{fade:function(t,e,i,s,a){var c="in"===e,n={},l=t.css("opacity"),o={},r=s?s:c?1:0;(lightcase.open||!c)&&(n.opacity=l,o.opacity=r,t.css(n).show(),lightcase.support.transitions?(o[lightcase.support.transition+"transition"]=i+"ms ease",setTimeout(function(){t.css(o),setTimeout(function(){t.css(lightcase.support.transition+"transition",""),!a||!lightcase.open&&c||a()},i)},15)):(t.stop(),t.animate(o,i,a)))},scroll:function(t,e,i,s){var a="in"===e,c=a?lightcase.settings.transitionIn:lightcase.settings.transitionOut,n="left",l={},o=a?0:1,r=a?"-50%":"50%",h={},g=a?1:0,d=a?"50%":"-50%";if(lightcase.open||!a){switch(c){case"scrollTop":n="top";break;case"scrollRight":r=a?"150%":"50%",d=a?"50%":"150%";break;case"scrollBottom":n="top",r=a?"150%":"50%",d=a?"50%":"150%";break;case"scrollHorizontal":r=a?"150%":"50%",d=a?"50%":"-50%";break;case"scrollVertical":n="top",r=a?"-50%":"50%",d=a?"50%":"150%"}if("prev"===lightcase.cache.action)switch(c){case"scrollHorizontal":r=a?"-50%":"50%",d=a?"50%":"150%";break;case"scrollVertical":r=a?"150%":"50%",d=a?"50%":"-50%"}l.opacity=o,l[n]=r,h.opacity=g,h[n]=d,t.css(l).show(),lightcase.support.transitions?(h[lightcase.support.transition+"transition"]=i+"ms ease",setTimeout(function(){t.css(h),setTimeout(function(){t.css(lightcase.support.transition+"transition",""),!s||!lightcase.open&&a||s()},i)},15)):(t.stop(),t.animate(h,i,s))}},zoom:function(t,e,i,s){var a="in"===e,c={},n=t.css("opacity"),l=a?"scale(0.75)":"scale(1)",o={},r=a?1:0,h=a?"scale(1)":"scale(0.75)";(lightcase.open||!a)&&(c.opacity=n,c[lightcase.support.transition+"transform"]=l,o.opacity=r,t.css(c).show(),lightcase.support.transitions?(o[lightcase.support.transition+"transform"]=h,o[lightcase.support.transition+"transition"]=i+"ms ease",setTimeout(function(){t.css(o),setTimeout(function(){t.css(lightcase.support.transition+"transform",""),t.css(lightcase.support.transition+"transition",""),!s||!lightcase.open&&a||s()},i)},15)):(t.stop(),t.animate(o,i,s)))}},callHooks:function(e){"object"==typeof e&&t.each(e,function(t,e){"function"==typeof e&&e()})},cacheObjectData:function(e){t.data(e,"cache",{id:e.attr("id"),content:e.html()}),lightcase.cache.originalObject=e},restoreObject:function(){var e=t('[id^="'+lightcase.settings.idPrefix+'temp-"]');e.attr("id",t.data(lightcase.cache.originalObject,"cache").id),e.html(t.data(lightcase.cache.originalObject,"cache").content)},resize:function(){lightcase.open&&(lightcase.isSlideshowEnabled()&&lightcase.stopTimeout(),lightcase.dimensions=lightcase.getDimensions(),lightcase.calculateDimensions(lightcase.cache.object))},cacheScrollPosition:function(){var e=t(window),i=t(document),s={top:e.scrollTop(),left:e.scrollLeft()};lightcase.cache.scrollPosition=lightcase.cache.scrollPosition||{},i.width()>e.width()&&(lightcase.cache.scrollPosition.left=s.left),i.height()>e.height()&&(lightcase.cache.scrollPosition.top=s.top)},watchResizeInteraction:function(){t(window).resize(lightcase.resize)},unwatchResizeInteraction:function(){t(window).off("resize",lightcase.resize)},watchScrollInteraction:function(){t(window).scroll(lightcase.cacheScrollPosition)},unwatchScrollInteraction:function(){t(window).off("scroll",lightcase.cacheScrollPosition)},restoreScrollPosition:function(){t(window).scrollTop(parseInt(lightcase.cache.scrollPosition.top)).scrollLeft(parseInt(lightcase.cache.scrollPosition.left)).resize()},switchToFullScreenMode:function(){lightcase.settings.shrinkFactor=1,lightcase.settings.overlayOpacity=1,t("html").addClass(lightcase.settings.classPrefix+"fullScreenMode")},lightcaseOpen:function(){switch(lightcase.open=!0,lightcase.support.transitions=lightcase.settings.cssTransitions?lightcase.isTransitionSupported():!1,lightcase.support.mobileDevice=lightcase.isMobileDevice(),lightcase.support.mobileDevice&&(t("html").addClass(lightcase.settings.classPrefix+"isMobileDevice"),lightcase.settings.fullScreenModeForMobile&&lightcase.switchToFullScreenMode()),lightcase.settings.transitionIn||(lightcase.settings.transitionIn=lightcase.settings.transition),lightcase.settings.transitionOut||(lightcase.settings.transitionOut=lightcase.settings.transition),lightcase.settings.transitionIn){case"fade":case"fadeInline":case"elastic":case"scrollTop":case"scrollRight":case"scrollBottom":case"scrollLeft":case"scrollVertical":case"scrollHorizontal":$case.is(":hidden")&&($close.css("opacity",0),$overlay.css("opacity",0),$case.css("opacity",0),$contentInner.css("opacity",0)),lightcase.transition.fade($overlay,"in",lightcase.settings.speedIn,lightcase.settings.overlayOpacity,function(){lightcase.transition.fade($close,"in",lightcase.settings.speedIn),lightcase.handleEvents(),lightcase.processContent()});break;default:lightcase.transition.fade($overlay,"in",0,lightcase.settings.overlayOpacity,function(){lightcase.transition.fade($close,"in",0),lightcase.handleEvents(),lightcase.processContent()})}t("html").addClass(lightcase.settings.classPrefix+"open"),$case.attr("aria-hidden","false")},lightcaseClose:function(){switch(lightcase.open=!1,$loading.hide(),lightcase.unbindEvents(),lightcase.unwatchResizeInteraction(),lightcase.unwatchScrollInteraction(),t("html").removeClass(lightcase.settings.classPrefix+"open"),$case.attr("aria-hidden","true"),$nav.children().hide(),lightcase.restoreScrollPosition(),lightcase.callHooks(lightcase.settings.onClose),lightcase.settings.transitionOut){case"fade":case"fadeInline":case"scrollTop":case"scrollRight":case"scrollBottom":case"scrollLeft":case"scrollHorizontal":case"scrollVertical":lightcase.transition.fade($case,"out",lightcase.settings.speedOut,0,function(){lightcase.transition.fade($overlay,"out",lightcase.settings.speedOut,0,function(){lightcase.cleanup()})});break;case"elastic":lightcase.transition.zoom($case,"out",lightcase.settings.speedOut,function(){lightcase.transition.fade($overlay,"out",lightcase.settings.speedOut,0,function(){lightcase.cleanup()})});break;default:lightcase.cleanup()}},unbindEvents:function(){$overlay.unbind("click"),t(document).unbind("keyup"),$case.unbind("swipeleft").unbind("swiperight"),$nav.children("a").unbind("click"),$close.unbind("click")},cleanupDimensions:function(){var t=$contentInner.css("opacity");$case.css({width:"",height:"",top:"",left:"","margin-top":"","margin-left":""}),$contentInner.removeAttr("style").css("opacity",t),$contentInner.children().removeAttr("style")},cleanup:function(){lightcase.cleanupDimensions(),lightcase.isSlideshowEnabled()&&(lightcase.stopTimeout(),$nav.removeClass(lightcase.settings.classPrefix+"paused")),$loading.hide(),$overlay.hide(),$case.hide(),$nav.children().hide(),$case.removeAttr("data-type"),$nav.removeAttr("data-ispartofsequence"),$contentInner.empty().hide(),$info.children().empty(),lightcase.cache.originalObject&&lightcase.restoreObject(),lightcase.callHooks(lightcase.settings.onCleanup),lightcase.cache={}}},t.fn.lightcase=function(e){return lightcase[e]?lightcase[e].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof e&&e?void t.error("Method "+e+" does not exist on jQuery.lightcase"):lightcase.init.apply(this,arguments)}}(jQuery);

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