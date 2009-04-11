/**
 * Marquee 1.0.0
 *
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Written by Kevin Armstrong <kevin@kevinandre.com>
 * Last updated: 2008.11.08
 *
 * Marquee is a Prototype JSFr based class that allows the creation of display marqueees.
 *
 */
var Marquee = (function(){
	//private members
	var options = {
		life: 5
		,animIn: null
		,animOut: null
		,className: ""
		,delay: .5
	};
	var messageOptions = {
		link: ""
		,className: ""
		,life: 0			//overrides the default life provided by Marquee
	};
	//private methods
	function _createMarquee(data, opts){
		$A(data).each(function(msg){
			if(typeof(msg) != "object"){ return; }
			msg = Object.extend(Object.clone(messageOptions), msg);
			var i = this.data.length;
			this.data[i] = new Element("div", {
				"marqueeindex": i
				,id: 'marqueeeMessage'+(new Date().getTime())+i
			}).setStyle({
				position: 'relative'
			});
			Object.extend(this.data[i], msg);
			if(msg.link!=""){
				this.data[i].insert(new Element('a', {href: msg.link}).update(msg.message||""));
			} else {
				this.data[i].update(msg.message||"");
			}
			if(msg.className!=""){
				this.data[i].addClassName(msg.className);
			} else if(opts.className!=""){
				this.data[i].addClassName(opts.className);
			}
			var anim = getAnimation(opts.animOut, opts.animOut, this.element);
			hide(this.data[i], anim.ao);
			opts.container.insert(this.data[i]);
		}.bind(this));
	}
	function hide(elm, x){
		elm.setStyle(x.style);
		elm.hide();
	}
	function show(elm, x){
		elm.show();
		elm.setStyle(x.style);
	}
	function getAnimation(ain, aout, elm){
		//change slide out size for slide effect messages
		if(aout.name && aout.name == "slide"){
			aout = Object.extend(Object.clone(aout), {
				style: "left: "+(-1 * Number(elm.getWidth()+10+parseInt(elm.getStyle("padding-left")))) + "px"
			});
			ain = Object.extend(Object.clone(ain));
		}
		//change slide out size for blinker effect messages
		if(aout.name && aout.name == "blinker"){
			aout = Object.extend(Object.clone(aout), {
				style: "margin-top: "+(-1 * Number(elm.getWidth()+10+parseInt(elm.getStyle("padding-left")))) + "px"
			});
			ain = Object.extend(Object.clone(ain));
		}
		//change slide out size for blind effect messages
		if(aout.name && aout.name == "blind"){
			aout = Object.extend(Object.clone(aout), {
				style: "top: "+(-1 * Number(elm.getWidth()+10+parseInt(elm.getStyle("padding-left")))) + "px"
			});
			ain = Object.extend(Object.clone(ain));
		}
		return { ai: ain, ao: aout };
	}
	function _beforeAnimation(e){
		e.memo.show();
	}
	function _afterAnimation(e){
		e.memo.hide();
	}
	/*
	 * Marquee Class
	 *   @events		load:success, load:failed
	 *   @methods		imports, includes, namespace
	 */
	var _Marquee = Class.create({
		initialize: function(opts){
			this.running = false;
			this.options = Object.extend(Object.clone(options), opts);
			this.data = [];
			this.element = (!opts.element) ? (new Element("div").wrap(document.body)) : $(opts.element);
			this.element.setStyle({"overflow": "hidden"});
			if(opts.start && Object.isFunction(opts.start)){
				this.element.observe("marquee:start", opts.start);
			}
			if(opts.stop && Object.isFunction(opts.stop)){
				this.element.observe("marquee:stop", opts.stop);
			}
			if(opts.change && Object.isFunction(opts.change)){
				this.element.observe("marquee:change", opts.change);
			}
			this.element.observe("marquee:before", (opts.before||_beforeAnimation));
			this.element.observe("marquee:after", (opts.after||_afterAnimation));
			if(opts.data && opts.data.length && opts.data.length>0){
				this.load(opts.data);
				delete opts.data;
			}
		}
		/*
		 * load - 		imports a new set of messages to display
		 *   @arguments		Array of string values to display
		 *   @returns		
		 */
		,load: function(data){
			if(this.running){ return; }
			this.empty();
			this.append(data);
			this.start(this.current);
		}
		,append: function(data){
			if(data && Object.isArray(data)){ data = data; } 
			else if(data) { data = [data]; } 
			else { return false; }
			_createMarquee.call(this, data, Object.extend(Object.clone(this.options), {
				container: this.element,
				animOut: (this.options.animOut || Marquee.fadeOut)
			}));
		}
		,clear: function(){
			this.stop(true);
			var ai = this.options.animIn || Marquee.fadeIn;
			var ao = this.options.animOut || Marquee.fadeOut;
			var anim = getAnimation(ai, ao, this.element);
			hide(this.data[this.current], anim.ao);
		}
		,empty: function(){
			this.stop();
			this.element.descendants().invoke("remove");
			this.data.clear();
			this.current = 0;
		}
		,start: function(s){
			if(this.data.length==0||this.running){ return; }
			var ai = this.options.animIn || Marquee.fadeIn;
			var ao = this.options.animOut || Marquee.fadeOut;
			var anim = getAnimation(ai, ao, this.element);
			if(typeof(s)=='number'){
				this.clear();
				this.current = s;
				show(this.data[this.current], anim.ai); 
			}
			var life = (this.data[this.current].life<=0)?this.options.life:this.data[this.current].life;
			try {
				this._marqueeOut = (function(t){
					t.running = true;
					new Effect.Morph(t.data[t.current], Object.extend(anim.ao, { 
						afterFinish: function(){
							t.element.fire("marquee:after", t.data[t.current]);
							t.current = (t.current >= t.data.length-1) ? 0 : t.current+1;
							try {
								t._marqueeIn = (function(){
									t.element.fire("marquee:before", t.data[t.current]);
									new Effect.Morph(t.data[t.current].id, Object.extend(anim.ai, {
										afterFinish: function(){
											t.running = false;
											t.element.fire("marquee:change");
											t.start();
										}							
									}));
								}.delay(t.options.delay));
							} catch(e){ }
						}
					}));
				}.delay(life, this));
				this.element.fire("marquee:start");
			} catch(e){ }
		}
		,stop: function(quiet){
			this.running = false;
			if(this._marqueeOut){ 
				window.clearTimeout(this._marqueeOut); 
			}
			if(this._marqueeIn){ 
				window.clearTimeout(this._marqueeIn); 
			}
			if(!quiet){	this.element.fire("marquee:stop"); }
		}
		,next: function(){
			if(this.running){ return; }
			this.start((this.current+1 > this.data.length-1)?0:this.current+1);
		}
		,previous: function(){
			if(this.running){ return; }
			this.start((this.current-1 < 0)?this.data.length-1:this.current-1);
		}
	});
	//static transitions
	_Marquee.fadeIn = { name: "fade", style: "opacity: 1", duration: .4 };
	_Marquee.fadeOut = { name: "fade", style: "opacity: 0", duration: .6 };
	_Marquee.slideIn = { name: "slide", style: "left: 0px", duration: .6 };
	_Marquee.slideOut = { name: "slide", style: "left: -100px", duration: .4 };
	_Marquee.blindIn = { name: "blind", style: "top: 0px", duration: .4 };
	_Marquee.blindOut = { name: "blind", style: "top: -20px", duration: .3 };

	_Marquee.testIn = { name: "test", style: "top: 0px", duration: .4 };
	_Marquee.testOut = { name: "test", style: "top: -20px", duration: .3 };
	
	return _Marquee;

})();
