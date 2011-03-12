var Textarea_Core = Class.create(
{
	//Insertion dans le champ.
	simple_insert: function(target, open_balise, close_balise)
	{
		var textarea = target;
		var scroll = textarea.scrollTop;
		
		if( close_balise != "" && close_balise != "smile" )
			textarea.value += open_balise + close_balise;
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
		
		var selection = document.selection.createRange().text;
		
		alert(selection);

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
		if (type != 'height' && type != 'width') return false;
		
		if (type == 'height')
		{
			var current_height = parseInt(textarea.style.height) ? parseInt(textarea.style.height) : 300;
			var new_height = current_height + px;
			
			if (new_height > 40)
				textarea.style.height = new_height + "px";
		}
		else
		{
			var current_width = parseInt(textarea.style.width) ? parseInt(textarea.style.width) : 150;
			var new_width = current_width + px;
			
			if (new_width > 40)
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
		this.open_element = null;
		
		$('editorbar' + element).insert({before: this.get_menu(element)});
		this.display(this.block1, 'bbcode');
		this.display(this.block2, 'bbcode2');
		if (this.upload.type == 1)
			this.display_upload(this.upload);
	},
	
	get_menu: function(element)
	{
		var xtd = new Element('td');
		var xtr = new Element('tr');
		xtr.insert(xtd);
		var xtbody = new Element('tbody');
		xtbody.insert(xtr);
		var xtable1 = new Element('table',{'class':'bbcode'});
		xtable1.setStyle({'width':'100%'});
		xtable1.insert(xtbody);
		
		var xtd2 = new Element('td');
		var xtr2 = new Element('tr');
		xtr2.insert(xtd2);
		var xtbody2 = new Element('tbody');
		xtbody2.insert(xtr2);
		var xtable2 = new Element('table',{'class': 'bbcode2', 'id':'bbcode_more'+element});
		displayCookie = this.getCookie();
		if (displayCookie == 0)
			xtable2.setStyle({display:'none', 'width':'100%'});
		else
			xtable2.setStyle({'width':'100%'});
		xtable2.insert(xtbody2);
		
		var xtd10 = new Element('td');
		xtd10.insert(xtable1);
		xtd10.insert(xtable2);
		var xtd11 = new Element('td', {'id': 'bbcode-td-'+element});
		xtd11.setStyle({'verticalAlign':'top', 'padding':'5px 5px'});
		xtd11.update('&nbsp;');
		var xtr10 = new Element('tr');
		xtr10.insert(xtd10);
		xtr10.insert(xtd11);
		var xtbody10 = new Element('tbody');
		xtbody10.insert(xtr10);
		var xtable10 = new Element('table', {'id': 'table_'+element});
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
			'onclick':''
		}, attrs || {});
		
		var img = new Element('img',
		{
			'src': this.path+'images/form/'+values.fname,
			'alt': '',
			'class': values.classe,
			'title': values.label
		});
		
		img.setStyle({'margin':'0px 2px'});
		
		if(values.id)
		{
			img.writeAttribute('id', values.id + '_' + this.element);
		}

		if(Object.isFunction(values.onclick))
			img.observe('click', values.onclick);
		return img;
	},

	separator: function()
	{
		var sep = {'fname':'separate.png'};
		var elt = this.balise(sep);
		return elt;
	},
	
	balise2: function(attrs)
	{
		var elt = this.balise(attrs);
		
		if(attrs.disabled != '')
		{
			elt.setStyle({'opacity':0.3, 'cursor': 'default'});
			elt.stopObserving('click', fn);
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
			elt.observe('click', fn);
		}
		return elt;
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
			if (tmp != 'none') {
				tmp = 'none'; 
				elt.fade({duration: 0.3});
				this.open_element = null;
			}
			else {
				tmp = 'block';
				elt.appear({duration: 0.5});
				if (this.open_element != null && this.open_element != name)
				{
					$(this.open_element).fade({duration: 0.3});
				}
				this.open_element = name;
			}
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
			var elt = $('bbcode_more'+this.element);
			var display = elt.getStyle('display');
			if (display != 'none') {
				elt.fade({duration: 0.3});
				this.cookieRequest(0);
			}
			else {
				elt.appear({duration: 0.5});
				this.cookieRequest(1);
			}
		
		}.bind(this);
	},

	cookieRequest : function(value)
	{
		date = new Date();
		date.setMonth(date.getMonth() + 1);
		document.cookie = 'hide_bbcode'+this.element + '=' + value + '; expires = ' + date.toGMTString() + ';';
	},
	
	getCookie : function()
	{
		name = 'hide_bbcode'+this.element;
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
	
	resize: function(item)
	{
		var preview = 'xmlhttprequest_preview'+this.element;
		if (item.height)
		{
			this.textarea.resize(this.element, item.height, 'height');
			this.textarea.resize(preview, item.height, 'height');
		}
		else if (item.width)
		{
			this.textarea.resize(this.element, item.width, 'width');
			this.textarea.resize(preview, item.width, 'width');
		}
	},
	
	callbackResize: function(item)
	{
		return function()
		{
			this.resize(item);
		}.bind(this);
	},

	display_upload: function(item)
	{
		var a = new Element('a', {'title':item.title, 'href':'#'});
		var root = this.root;
		var field = this.element;
		var fn = function()	{
			window.open(root+'/member/upload.php?popup=1&amp;fd='+field+'&amp;edt='+item.editor,
				'',
				'height=500,width=720,resizable=yes,scrollbars=yes');
			return false;
		};
		a.observe('click', fn);
		var img = new Element('img', {'src':this.path+'images/upload/files_add.png', 'alt':''});
		a.insert(img);
		
		var elt = $$('td#bbcode-td-'+this.element);
		if(elt.length > 0) {
			elt = elt.first();
			elt.update(a);
		}
	},

	display: function(bloc, classe)
	{
		var str = 'table#table_'+this.element+' table.'+classe+' td';
		var elt = $$(str);
		if(!elt.length)
			throw('Error - tables non found');
			  
		elt = elt.first();
		
		var item = undefined;
		var menu = undefined;
		
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
				item = this.balise(x);
				$(elt).insert(item);
				menu = this.menuSmileys(x, item);
				item.insert({'before':menu});
			}
			else if (x.type == 'menu_title')
			{
				x.onclick = this.callbackToggleMenu(x.id, this.element);
				item = this.balise(x);
				$(elt).insert(item);
				menu = this.menuTitles(x);
				item.insert({'before':menu});
			}
			else if (x.type == 'menu_subtitle')
			{
				x.onclick = this.callbackToggleMenu(x.id, this.element);
				item = this.balise(x);
				$(elt).insert(item);
				menu = this.menuSubTitles(x);
				item.insert({'before':menu});
			}
			else if (x.type == 'menu_style')
			{
				x.onclick = this.callbackToggleMenu(x.id, this.element);
				item = this.balise(x);
				$(elt).insert(item);
				menu = this.menuStyles(x);
				item.insert({'before':menu});
			}
			else if (x.type == 'menu_size')
			{
				x.onclick = this.callbackToggleMenu(x.id, this.element);
				item = this.balise(x);
				$(elt).insert(item);
				menu = this.menuSizes(x);
				item.insert({'before':menu});
			}
			else if (x.type == 'menu_color')
			{
				x.onclick = this.callbackToggleMenu(x.id, this.element);
				item = this.balise(x);
				$(elt).insert(item);
				menu = this.menuColors(x);
				item.insert({'before':menu});
			}
			else if (x.type == 'menu_code')
			{
				x.onclick = this.callbackToggleMenu(x.id, this.element);
				item = this.balise(x);
				$(elt).insert(item);
				menu = this.menuCodes(x);
				item.insert({'before':menu});
			}
			else if (x.type == 'menu_table')
			{
				x.onclick = this.callbackToggleMenu(x.id, this.element);
				item = this.balise(x);
				$(elt).insert(item);
				menu = this.menuTables(x);
				item.insert({'before':menu});
			}
			else if (x.type == 'menu_list')
			{
				x.onclick = this.callbackToggleMenu(x.id, this.element);
				item = this.balise(x);
				$(elt).insert(item);
				menu = this.menuLists(x);
				item.insert({'before':menu});
			}
			else if (x.type == 'action_resize')
			{
				x.onclick = this.callbackResize(x);
				$(elt).insert(this.balise(x));
			}
			else if (x.type == 'action_more')
			{
				x.onclick = this.callbackToggleDiv();
				$(elt).insert(this.balise(x));
			}
			else if (x.type == 'action_prompt_url')
			{
				x.onclick = this.callbackPrompt(x.prompt, 'url');
				$(elt).insert(this.balise(x));
			}
			else if (x.type == 'action_prompt_picture')
			{
				x.onclick = this.callbackPrompt(x.prompt, 'picture');
				$(elt).insert(this.balise(x));
			}
			else if (x.type == 'action_help')
			{
				var img = this.balise(x);
				var link = new Element('a', {'href': 'http://www.phpboost.com/wiki/bbcode', 'target':'_blank'});
				link.insert(img);
				$(elt).insert(link);
			}
			if(menu)
			{
				menu.observe('mouseleave', function(e) { 
					if(this.id)
						Element.fade.delay(3, this);
				});
			}
		}.bind(this));
	},
	
	action_prompt_url: function(question)
	{
		var url = prompt(question, '');
		if( url != null && url != '' )
			this.textarea.insert('[url=' + url + ']', '[/url]', this.element);
		else
			this.textarea.insert('[url]', '[/url]', this.element);
	},
	
	action_prompt_picture: function(question)
	{
		var url = prompt(question, '');
		if( url != null && url != '' )
			this.textarea.insert('[img]' + url, '[/img]', this.element);
		else
			this.textarea.insert('[img]', '[/img]', this.element);
	},

	callbackPrompt: function(question, type)
	{
		return function()
		{
			if (type == 'picture') {
				this.action_prompt_picture(question);
			}
			else if (type == 'url') {
				this.action_prompt_url(question);
			}
		}.bind(this);
	},
	
	menuSmileys: function(x, item)
	{
		var index = x.id;
		var id = 'bb_block_'+index+'_'+this.element;
		var div = new Element('div', {'id': id});
		div.setStyle({
			'position':'relative',
			'zIndex':100,
			'float':'left',
			'display':'none',
			'marginLeft':'-50px'
		});
				
		var elt = new Element('div', {'class':'bbcode_block'});
		elt.setStyle({width: '130px'});
		var i = 0;
		this.smileys.each( function(x) {
			if(x.url)
			{
				var img = new Element('img', {
					'src': this.path_img+x.url,
					'height': x.height,
					'width': x.width
				});
				img.setStyle({'padding':'1px', 'cursor':'pointer'});
				if(x.code)
				{
					var fn = this.callbackInsertBBcode(x.code, 'smile');
					img.observe('click', fn);
				}

				elt.insert(img);
				i++;
				if ((i % 5) == 0) elt.insert('<br />');
			}
		}.bind(this));
		
		div.update(elt);
		return div;
	},
	
	menuTitles: function(x)
	{
		var index = x.id;
		var id = 'bb_block_'+index+'_'+this.element;
		var div = new Element('div', {'id': id});
		div.setStyle({
			'position':'relative',
			'zIndex':100,
			'float':'left',
			'display':'none'
		});
				
		var elt = new Element('div', {'class':'bbcode_block'});
		elt.setStyle({'marginLeft':'120px'});
		var sel = new Element('select', {'id': index+this.element});
		var fn = this.callbackChangeSelect(index, index);
		sel.observe('change', fn);
		
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
		return div;
	},
	
	menuSubTitles: function(x)
	{
		var index = x.id;
		var id = 'bb_block_'+index+'_'+this.element;
		var div = new Element('div', {'id': id});
		div.setStyle({
			'position':'relative',
			'zIndex':100,
			'float':'left',
			'display':'none'
		});
				
		var elt = new Element('div', {'class': 'bbcode_block'});
		elt.setStyle({'marginLeft':'130px'});
		var sel = new Element('select', {'id': index+this.element});
		var fn = this.callbackChangeSelect2(index);
		sel.observe('change', fn);
		
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
		return div;
	},
	
	menuStyles: function(x)
	{
		var index = x.id;
		var id = 'bb_block_'+index+'_'+this.element;
		var div = new Element('div', {'id': id});
		div.setStyle({
			'position':'relative',
			'zIndex':100,
			'float':'left',
			'display':'none'
		});
				
		var elt = new Element('div', {'class':'bbcode_block'});
		elt.setStyle({'marginLeft':'160px'});
		var sel = new Element('select', {'id': index+this.element});
		var fn = this.callbackChangeSelect(index, index);
		sel.observe('change', fn);
		
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
		return div;
	},
	
	menuSizes: function(x)
	{
		var index = x.id;
		var id = 'bb_block_'+index+'_'+this.element;
		var div = new Element('div', {'id': id});
		div.setStyle({
			'position':'relative',
			'zIndex':100,
			'float':'left',
			'display':'none'
		});
				
		var elt = new Element('div', {'class':'bbcode_block'});
		elt.setStyle({'marginLeft':'360px'});
		var sel = new Element('select', {'id': index+this.element});
		var fn = this.callbackChangeSelect(index, index);
		sel.observe('change', fn);
		
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
		return div;
	},
	
	menuColors: function(x)
	{
		var index = x.id;
		var id = 'bb_block_'+index+'_'+this.element;
		var div = new Element('div', {'id': id});
		div.setStyle({
			'position':'relative',
			'zIndex':100,
			'float':'left',
			'display':'none'
		});
				
		var elt = new Element('div', {'class':'bbcode_block'});
		elt.setStyle({'marginLeft':'310px'});
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
				xspan.setStyle({'background':this.colors[i], 'padding':'0px 4px', 'border':'1px solid #ACA899', 'cursor':'pointer'});
				xspan.update('&nbsp;');
				var fn = this.callbackInsertBBcode('[color='+this.colors[i]+']', '[/color]');
				xspan.observe('click', fn);
				xtd.insert(xspan);
				xtr.insert(xtd);
			}
		}

		elt.insert(xtable);                     
		div.update(elt);
		return div;
	},
	
	menuCodes: function(item)
	{
		var index = item.id;
		var id = 'bb_block_'+index+'_'+this.element;
		var div = new Element('div', {'id': id});
		div.setStyle({
			'position':'relative',
			'float':'left',
			'display':'none'
		});
				
		var elt = new Element('div', {'class':'bbcode_block'});
		elt.setStyle({'marginLeft':'310px'});
		var sel = new Element('select', {'id': item.id+this.element});
		var fn = this.callbackChangeSelect(index, item.tag);
		sel.observe('change', fn);
		
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
		return div;
	},

	menuTables: function(item)
	{
		var index = item.id;
		var id = 'bb_block_'+index+'_'+this.element;
		var div = new Element('div', {'id': id});
		div.setStyle({
			'position':'relative',
			'zIndex':100,
			'float':'left',
			'display':'none'
		});

		var elt = new Element('div', {'class':'bbcode_block'});
		elt.setStyle({
			'width':'180px',
			'marginLeft':'160px'
		});
		
		this.tables.each(function(x)
		{
			if(x.type == 'text')
			{
				var para = new Element('p');
				var label = new Element('label', {'for':x.id+this.element});
				label.setStyle({'fontSize':'10px', 'fontWeight':'normal'});
				label.update(x.text);
				para.insert(label);
				var input = new Element('input', {
					'type':x.type,
					'size':x.size,
					'name':x.id+this.element,
					'id':x.id+this.element,
					'maxlength':x.maxlength,
					'value':x.value
				});
				para.insert(input);
			}
			else if (x.type == 'checkbox')
			{
				var para = new Element('p');
				var label = new Element('label', {'for':x.id+this.element});
				label.setStyle({'fontSize':'10px', 'fontWeight':'normal'});
				label.update(x.text);
				para.insert(label);
				var checkbox = new Element('input',	{
					'name':x.id+this.element,
					'id':x.id+this.element,
					'type':x.type
				});
				para.insert(checkbox);
			}
			else if (x.type == 'submit')
			{
				var para = new Element('p');
				para.setStyle({'fontSize':'10px', 'fontWeight':'normal', 'textAlign':'center', 'cursor':'pointer'});
				var img = this.balise(x);
				para.insert(img);
				para.insert(x.text);
				var fn = this.callbackBBcode_table(x.head);
				para.observe('click', fn);
			};
			elt.insert(para);
		}.bind(this));
		
		div.update(elt);
		return div;
	},

	menuLists: function(item)
	{
		var index = item.id;
		var id = 'bb_block_'+index+'_'+this.element;
		var div = new Element('div', {'id': id});
		div.setStyle({
				'position':'relative',
				'zIndex':100,
				'float':'left',
				'display':'none'
		});

		var elt = new Element('div', {'class':'bbcode_block'});
		elt.setStyle({
				'width':'180px',
				'marginLeft':'250px'
		});
		
		this.lists.each(function(x)
		{
			if(x.type == 'text')
			{
				var para = new Element('p');
				var label = new Element('label', {'for':x.id+this.element});
				label.setStyle({'fontSize':'10px', 'fontWeight':'normal'});
				label.update(x.text);
				para.insert(label);
				var input = new Element('input', {
					'type':x.type,
					'size':x.size,
					'name':x.id+this.element,
					'id':x.id+this.element,
					'maxlength':x.maxlength,
					'value':x.value
				});
				para.insert(input);
			}
			else if (x.type == 'checkbox')
			{
				var para = new Element('p');
				var label = new Element('label', {'for':x.id+this.element});
				label.setStyle({'fontSize':'10px', 'fontWeight':'normal'});
				label.update(x.text);
				para.insert(label);
				var checkbox = new Element('input', {
					'name':x.id+this.element,
					'id':x.id+this.element,
					'type':x.type
				});
				para.insert(checkbox);
			}
			else if (x.type == 'submit')
			{
				var para = new Element('p');
				para.setStyle({'fontSize':'10px', 'fontWeight':'normal', 'textAlign':'center', 'cursor':'pointer'});
				var img = this.balise(x);
				para.insert(img);
				para.insert(x.text);
				var fn = this.callbackBBcode_list();
				para.observe('click', fn);
			};
			elt.insert(para);
		}.bind(this));
		
		div.update(elt);
		return div;
	},
	
	BBcode_table: function(element, head_text)
	{
		var cols = $('bb_cols'+element).value;
		var lines = $('bb_lines'+element).value;
		var head = $('bb_head'+element).checked;
		var code = '';
		
		if( cols >= 0 && lines >= 0 )
		{
			var colspan = cols > 1 ? ' colspan="' + cols + '"' : '';
			var pointor = head ? (59 + colspan.length) : 22;
			code = head ? '[table]\n\t[row]\n\t\t[head' + colspan + ']' + head_text + '[/head]\n\t[/row]\n' : '[table]\n';
			
			for(var i = 0; i < lines; i++)
			{
				code += '\t[row]\n';
				for(var j = 0; j < cols; j++)
						code += '\t\t[col][/col]\n';
				code += '\t[/row]\n';
			}                               
			code += '[/table]';
			
			this.textarea.insert(code.substring(0, pointor), code.substring(pointor, code.length), element);
		}
	},

	callbackBBcode_table: function(head)
	{
		return function()
		{
			this.BBcode_table(this.element, head);
		}.bind(this);
	},
	
	BBcode_list: function(element)
	{
		var elements = $('bb_list'+element).value;
		var ordered_list = $('bb_ordered_list'+element).checked;
		if( elements <= 0 )
			elements = 1;

		var pointor = ordered_list ? 19 : 11;
		
		var code = '[list' + (ordered_list ? '=ordered' : '') + ']\n';
		for(var j = 0; j < elements; j++)
			code += '\t[*]\n';
		code += '[/list]';
		
		this.textarea.insert(code.substring(0, pointor), code.substring(pointor, code.length), element);
	},
	
	callbackBBcode_list: function()
	{
		return function()
		{
			this.BBcode_list(this.element);
		}.bind(this);;
	}
});
