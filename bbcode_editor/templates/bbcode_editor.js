var Textarea_Core = Class.create(
{
	//Insertion dans le champ.
	simple_insert: function(target, open_balise, close_balise)
	{
		var textarea = target;
		var scroll = textarea.scrollTop;
		
		if( close_balise != "" && close_balise != "smile" )
			textarea.value += '[' + open_balise + '][/' + close_balise + ']';
		else if( close_balise == "smile" )
			textarea.value += ' ' + open_balise + ' ';
			
		textarea.focus();
		textarea.scrollTop = scroll;
		return false;
	},

	//Récupération de la sélection sur netscape, ajout des balises autour.
	netscape_sel: function(target, open_balise, close_balise)
	{
		var sel_length = target.textLength;
		var sel_start = target.selectionStart;
		var sel_end = target.selectionEnd;
		//Position verticale.
		var scroll = target.scrollTop;
		
		if( sel_end == 1 || sel_end == 2 )
		{
			sel_end = sel_length;
		}

		var string_start = (target.value).substring(0, sel_start);
		var selection = (target.value).substring(sel_start, sel_end);
		var string_end = (target.value).substring(sel_end, sel_length);

		if ( close_balise != "" && selection == "" && close_balise != "smile" )
		{
			target.value = string_start + open_balise + close_balise + string_end;
			target.setSelectionRange(string_start.length + open_balise.length,
									target.value.length - string_end.length - close_balise.length);
			target.focus();
		}
		else if ( close_balise == "smile" )
		{
			target.value = string_start + selection + ' ' + open_balise + ' ' + string_end;	
			target.setSelectionRange(string_start.length + open_balise.length + 2,
									target.value.length - string_end.length);	
			target.focus();		
		}
		else
		{
			target.value = string_start + open_balise + selection + close_balise + string_end;
			target.setSelectionRange(string_start.length + open_balise.length,
									target.value.length - string_end.length - close_balise.length);
			target.focus();
		}
		
		//Remet à la bonne position le textarea.
		target.scrollTop = scroll;
		return false;
	},

	//Récupération de la sélection sur IE, ajout des balises autour.
	ie_sel: function(target, open_balise, close_balise)
	{
		// Position verticale
		var scroll = target.scrollTop;
		
		selection = document.selection.createRange().text;

		if ( close_balise != "" && selection == "" && close_balise != "smile" )
			document.selection.createRange().text = open_balise + close_balise;
		else if ( close_balise == "smile" )
			document.selection.createRange().text = selection + ' ' + open_balise + ' ';
		else
			document.selection.createRange().text = open_balise + selection + close_balise;		
		
		//Remet à la bonne position le textarea.
		target.scrollTop = scroll;

		return false;
	},
	
	insert: function(open_balise, close_balise, field)
	{
		var area = $(field);
		area.focus();

		if (Prototype.Browser.IE) // Internet Explorer
			this.ie_sel(area, open_balise, close_balise);
		else if (Prototype.Browser.Gecko || Prototype.Browser.Opera) //Netscape ou opera
			this.netscape_sel(area, open_balise, close_balise);
		else //insertion normale (autres navigateurs)
			this.simple_insert(area, open_balise, close_balise);

		return false;
	},
	
	resize: function(id, px, type)
	{
		var textarea = $(id);
		if( type == 'height' )
		{
			var current_height = parseInt(textarea.style.height) ? parseInt(textarea.style.height) : 300;
			var new_height = current_height + px;
			
			if( new_height > 40 )
				textarea.style.height = new_height + "px";
		}
		else
		{
			var current_width = parseInt(textarea.style.width) ? parseInt(textarea.style.width) : 150;
			var new_width = current_width + px;
			
			if( new_width > 40 )
				textarea.style.width = new_width + "px";
		}
		
		return false;
	}
});

var BBcodeEditor_Core = Class.create(
{
	initialize: function(element, options)
	{
		this.element = element;
		this.options = options;
		this.textarea = new Textarea_Core();
		
		$(element).insert({before: this.get_menu(element)});
		this.display(this.block1, 'bbcode');
		this.display(this.block2, 'bbcode2');
	},
	
	get_menu: function(element)
	{
		var xtd = new Element('td');
		xtd.setStyle({padding:'0px 2px', width:'100%'});
		var xtr = new Element('tr');
		xtr.insert(xtd);
		var xtbody = new Element('tbody');
		xtbody.insert(xtr);
		var xtable1 = new Element('table',{'class':'bbcode'});
		xtable1.insert(xtbody);
		
		var xtd2 = new Element('td');
		xtd2.setStyle({padding:'0px 2px'});
		var xtr2 = new Element('tr');
		xtr2.insert(xtd2);
		var xtbody2 = new Element('tbody');
		xtbody2.insert(xtr2);
		var xtable2 = new Element('table',{'class': 'bbcode2', 'id':'bbcode_more'+element});
		xtable2.setStyle({display:'none'});
		xtable2.insert(xtbody2);
		
		var xtd10 = new Element('td');
		xtd10.insert(xtable1);
		xtd10.insert(xtable2);
		var xtr10 = new Element('tr');
		xtr10.insert(xtd10);
		var xtbody10 = new Element('tbody');
		xtbody10.insert(xtr10);
		var xtable10 = new Element('table', {'id': 'table-'+element});
		xtable10.setStyle({'margin':'4px', 'marginLeft':'auto', 'marginRight':'auto'});
		xtable10.insert(xtbody10);
		
		menu = xtable10;
		return menu;
	},
	
	balise: function(attrs)
	{
		var values = Object.extend(
		{
			'id': '',
			'fname': '',
			'label':'',
			'classe':'bbcode_hover',
			'onclick':'',
			'margin':''
		}, attrs || {});
		
		var span = new Element('span', {'margin':'0px 5px'});
		var img = new Element('img',
		{
			'src': this.path+'images/form/'+values.fname,
			'alt': '',
			'class': values.classe,
			'title': values.label
		});
		span.insert(img);
		
		if(values.id)
		{
			img.setAttribute('id', values.id + '_' + this.element);
		}

		if(Object.isFunction(values.onclick))
			Event.observe(img, 'click', values.onclick);

		return span;
	},

	separator: function()
	{
		var sep = {'fname':'separate.png'};
		return this.balise(sep);
	},
	
	balise2: function(attrs)
	{
		var t = this.balise(attrs);
		
		if(attrs.disabled != '')
		{
			t.setStyle({'opacity':0.3, 'cursor': 'default'});
			Event.stopObserving(t, 'click', fn);
		}
		else
		{
			if (attrs.tag)
				var tags = this.getTags(attrs.tag, attrs.tag);
			else if ((attrs.begintag) && (attrs.endtag))
				var tags = this.getTags(attrs.begintag, attrs.endtag);
			else
				var tags = {begin: '', end: ''};
			
			var fn = this.callbackInsertBBcode(tags.begin, tags.end);
			Event.observe(t, 'click', fn);
		}
		return t;
	},
	
	getTags: function(begin, end)
	{
		return {'begin': "["+begin+"]", 'end': "[/"+end+"]"};
	},
	
	toggleElement: function(name)
	{
		var elt = $(name);
		if (elt)
		{
			var tmp = elt.getStyle('display');
			if (tmp != 'none') tmp = 'none'; else tmp = 'block';
			elt.setStyle({'display': tmp});
		}
	},
	
	callbackToggleMenu: function(index)
	{
		return function()
		{
			this.toggleElement('bb_block_'+index+'_'+this.element);
		}.bind(this);
	},
	
	callbackToggleDiv: function()
	{
		return function()
		{
			this.toggleElement('bbcode_more'+this.element);
		}.bind(this);
	},
	
	callbackInsertBBcode: function(begin, end)
	{
		return function()
		{
			this.textarea.insert(begin, end, this.element);
		}.bind(this);
	},

	changeSelect: function(name, code)
	{
		var elt = $(name);
		if (elt)
		{
			var value = elt.value;
			var tags = this.getTags(code+'='+value, code);
			alert(tags.begin);
			this.textarea.insert(tags.begin, tags.end, this.element);					
		}
	},
	
	callbackChangeSelect: function(index, code)
	{
		return function()
		{
			this.changeSelect(index+this.element, code);
		}.bind(this);
	},
	
	changeSelect2: function(name)
	{
		var elt = $(name);
		if (elt)
		{
			var value = elt.value;
			var tags = this.getTags(value, value);
			alert(tags.begin);
			this.textarea.insert(tags.begin, tags.end, this.element);					
		}
	},
	
	callbackChangeSelect2: function(index)
	{
		return function()
		{
			this.changeSelect2(index+this.element);
		}.bind(this);
	},



	display: function(bloc, classe)
	{
		var elt = $$('table#table-'+this.element+' table.'+classe+' td');
		if(!elt.length)
			throw('Error - tables non found');
			
		elt = elt.first();
		
		bloc.each( function(x)
		{
			if (x.type == 'separator')
				$(elt).insert(this.separator());
			else if (x.type == 'balise')
				$(elt).insert(this.balise(x));
			else if (x.type == 'balise2')
				$(elt).insert(this.balise2(x));
			else if (x.type == 'menu_smileys')
			{
				x.onclick = this.callbackToggleMenu(x.id, this.element);
				$(elt).insert(this.balise(x));
				this.menuSmileys(x.id);
			}
			else if (x.type == 'menu_title')
			{
				x.onclick = this.callbackToggleMenu(x.id, this.element);
				$(elt).insert(this.balise(x));
				this.menuTitles(x.id);
			}
			else if (x.type == 'menu_subtitle')
			{
				x.onclick = this.callbackToggleMenu(x.id, this.element);
				$(elt).insert(this.balise(x));
				this.menuSubTitles(x.id);
			}
			else if (x.type == 'menu_style')
			{
				x.onclick = this.callbackToggleMenu(x.id, this.element);
				$(elt).insert(this.balise(x));
				this.menuStyles(x.id);
			}
			else if (x.type == 'menu_size')
			{
				x.onclick = this.callbackToggleMenu(x.id, this.element);
				$(elt).insert(this.balise(x));
				this.menuSizes(x.id);
			}
			else if (x.type == 'menu_color')
			{
				x.onclick = this.callbackToggleMenu(x.id, this.element);
				$(elt).insert(this.balise(x));
				this.menuColors(x.id);
			}
			else if (x.type == 'menu_code')
			{
				x.onclick = this.callbackToggleMenu(x.id, this.element);
				$(elt).insert(this.balise(x));
				this.menuCodes(x.id);
			}
			else if (x.type == 'action_more')
			{
				x.onclick = this.callbackToggleDiv();
				$(elt).insert(this.balise(x));
			}
			
		}.bind(this));
	},
	
	menuSmileys: function(index)
	{
		var id = 'bb_block_'+index+'_'+this.element;
		var div = new Element('div', {'id': id});
		div.setStyle({
			'position':'relative',
			'zIndex':100,
			'marginLeft':'-50px',
			'float':'left',
			'display':'none'
		});
			
		var elt = new Element('div', {'class':'bbcode_block'});
		elt.setStyle({width: '130px'});
		var i = 0;
		this.smileys.each( function(x) {
			if(x.url)
			{
				var img = new Element('img', {
							src: this.path_img+x.url,
							height: x.height,
							width: x.width
				});
				if(x.code)
				{
					var fn = this.callbackInsertBBcode(x.code, 'smile');
					Event.observe(img, 'click', fn);
				}

				elt.insert(img);
				i++;
				if ((i % 5) == 0) elt.insert('<br />');
			}
		}.bind(this));
		
		div.update(elt);
		$(index+'_'+this.element).insert({before:div});
	},
	
	menuTitles: function(index)
	{
		var id = 'bb_block_'+index+'_'+this.element;
		var div = new Element('div', {'id': id});
		div.setStyle({
			'position':'relative',
			'zIndex':100,
			'marginLeft':'0px',
			'float':'left',
			'display':'none'
		});
			
		var elt = new Element('div', {'class':'bbcode_block'});
		var sel = new Element('select', {'id': index+this.element});
		var fn = this.callbackChangeSelect(index, index);
		Event.observe(sel, 'change', fn);
		
		this.titles.each(function(x)
		{
			var opt = new Element('option', {'value':x.value});
			if (!x.value)
			{
				opt.writeAttribute('selected', 'selected');
				opt.writeAttribute('disabled', 'disabled');
			}
			opt.update(x.label);
			sel.insert(opt);
		});			
		
		elt.insert(sel);
		div.update(elt);
		$(index+'_'+this.element).insert({before:div});
	},
	
	menuSubTitles: function(index)
	{
		var id = 'bb_block_'+index+'_'+this.element;
		var div = new Element('div', {'id': id});
		div.setStyle({
			'position':'relative',
			'zIndex':100,
			'marginLeft':'0px',
			'float':'left',
			'display':'none'
		});
			
		var elt = new Element('div', {'class': 'bbcode_block'});
		var sel = new Element('select', {'id': index+this.element});
		var fn = this.callbackChangeSelect2(index);
		Event.observe(sel, 'change', fn);
		
		this.subtitles.each(function(x)
		{
			var opt = new Element('option', {'value':x.value});
			if (!x.value)
			{
				opt.writeAttribute('selected', 'selected');
				opt.writeAttribute('disabled', 'disabled');
			}
			opt.update(x.label);
			sel.insert(opt);
		});			
		
		elt.insert(sel);
		div.update(elt);
		$(index+'_'+this.element).insert({before:div});
	},
	
	menuStyles: function(index)
	{
		var id = 'bb_block_'+index+'_'+this.element;
		var div = new Element('div', {'id': id});
		div.setStyle({
			'position':'relative',
			'zIndex':100,
			'marginLeft':'0px',
			'float':'left',
			'display':'none'
		});
			
		var elt = new Element('div', {'class':'bbcode_block'});
		var sel = new Element('select', {'id': index+this.element});
		var fn = this.callbackChangeSelect(index, index);
		Event.observe(sel, 'change', fn);
		
		this.styles.each(function(x)
		{
			var opt = new Element('option', {'value':x.value});
			if (!x.value)
			{
				opt.writeAttribute('selected', 'selected');
				opt.writeAttribute('disabled', 'disabled');
			}
			opt.update(x.label);
			sel.insert(opt);
		});								
		
		elt.insert(sel);
		div.update(elt);
		$(index+'_'+this.element).insert({before:div});
	},
	
	menuSizes: function(index)
	{
		var id = 'bb_block_'+index+'_'+this.element;
		var div = new Element('div', {'id': id});
		div.setStyle({
			'position':'relative',
			'zIndex':100,
			'marginLeft':'0px',
			'float':'left',
			'display':'none'
		});
			
		var elt = new Element('div', {'class':'bbcode_block'});
		var sel = new Element('select', {'id': index+this.element});
		var fn = this.callbackChangeSelect(index, index);
		Event.observe(sel, 'change', fn);
		
		this.sizes.each(function(x)
		{
			var opt = new Element('option', {'value':x.value});
			if (!x.value)
			{
				opt.writeAttribute('selected', 'selected');
				opt.writeAttribute('disabled', 'disabled');
			}
			opt.update(x.label);
			sel.insert(opt);
		});								
		
		elt.insert(sel);
		div.update(elt);
		$(index+'_'+this.element).insert({before:div});
	},
	
	menuColors: function(index)
	{
		var id = 'bb_block_'+index+'_'+this.element;
		var div = new Element('div', {'id': id});
		div.setStyle({
			'position':'relative',
			'zIndex':100,
			'marginLeft':'0px',
			'float':'left',
			'display':'none'
		});
			
		var elt = new Element('div', {'class':'bbcode_block'});

		var xtable = new Element('table');
		xtable.style.width = '100px';
		var xtbody = new Element('tbody');
		xtable.insert(xtbody);
		var xtr = new Element('tr');
		xtbody.insert(xtr);
		for(i = 0; i < 40; i++)
		{
			br = (i+1) % 8;
			if (br == 0)
			{
				var xtr = new Element('tr');
				xtbody.insert(xtr);
			}
			else
			{
				var xtd = new Element('td');
				xtd.setStyle({padding:'2px'});
				var xspan = new Element('span');
				xspan.setStyle({'background':this.colors[i], 'padding':'0px 4px', 'border':'1px solid #ACA899'});
				xspan.update('&nbsp;');
				xtd.insert(xspan);
				xtr.insert(xtd);
			}
		}

		elt.insert(xtable);			
		div.update(elt);
		$(index+'_'+this.element).insert({before:div});
	},
	
	menuCodes: function(index)
	{
		var id = 'bb_block_'+index+'_'+this.element;
		var div = new Element('div', {'id': id});
		div.setStyle({
			'position':'relative',
			'zIndex':100,
			'marginLeft':'0px',
			'float':'left',
			'display':'none'
		});
			
		var elt = new Element('div', {'class':'bbcode_block'});
		var sel = new Element('select', {'id': index+this.element});
		var fn = this.callbackChangeSelect(index, index);
		Event.observe(sel, 'change', fn);
		
		this.codes.each(function(x)
		{
			if (!x.group)
			{
				var opt = new Element('option', {'value':x.value, 'selected':'selected', 'disabled':'disabled'});
			}
			else
			{
				var optgrp = new Element('optgroup', {'label': x.group});
				x.data.each( function(y)
				{
					var opt = new Element('option', {'value':y.value});
					opt.update(y.label);
					optgrp.insert(opt);
				});
				var opt = optgrp;
			}
			if (x.label)
				opt.update(x.label);
			sel.insert(opt);
		});		
		
		elt.insert(sel);
		div.update(elt);
		$(index+'_'+this.element).insert({before:div});
	}
	
});