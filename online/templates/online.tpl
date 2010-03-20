
# INCLUDE online_table #

<script type="text/javascript">

if(typeof(Prototype) == "undefined") {
    throw "This snippet requires Prototype to be loaded."; }

var Cookie = Class.create({
	initialize: function(options)
	{
		this.options = {
			dataKey: 'online_data'
		};
		
		Object.extend(this.options, options || {});
	},
	
	readData: function()
	{
		if (Object.isUndefined(this.options.dataKey)) {
			return undefined; }
		var tmp = document.cookie || '';
		if (tmp.length > 0)
		{
			var t = unescape(tmp);
			var data = t.split('; ');
			var keyEQ = this.options.dataKey + '=';
			max = data.length;
			for(i=0; i < max; i++)
			{
				var len = data[i].indexOf(keyEQ);
				if( len >= 0)
				{
					var x = data[i].substring(len+keyEQ.length, data[i].length);
					x = x.evalJSON();
					if (x)
					{
						return $H(x);
					}
					else
					{
						return new Hash();
					}
				}
			}
		}
		return new Hash();
	},
	
	writeData: function(value)
	{
		if (Object.isUndefined(this.options.dataKey)) {
			return undefined; }
		if(!Object.isUndefined(value))
		{
			var tmp = value.toJSON();
			document.cookie = this.options.dataKey + '=' + escape(tmp);
		}
	},
	
	deleteData: function(key)
	{
	}
});

function online_callback()
{
	url = '../online/xmlhttprequest.php?page=1';

	new Ajax.Request(url,
	{
		method:'get',
		onSuccess: function(transport)
		{
			$('online_table').update(transport.responseText);
			update_display();
		},
		onFailure: function()
		{
			alert('Error in online_callback');
		}
	});
	
}

function update_display()
{
	var prefix = 'online-tr';		
	var tr_blocks = $$('div#online_table tr.'+prefix);
	var displays = new Hash();
	tr_blocks.each( function(tr)
	{
		var entete = prefix + '-';
		var value = tr.readAttribute('id');
		var y = value.substring(entete.length, value.length);
		displays.set(y, tr.getStyle('display'));
	});

	var cookie = new Cookie();
	var v = cookie.readData();
	
	var keys = displays.keys();
	keys.each( function(x)
	{
		if(v.get(x))
			displays.set(x, v.get(x));
			
		var d = $(prefix+'-'+x);
		if (d)
		{
			d.setStyle({display:displays.get(x)});
		}
	});
	
	cookie.writeData(displays);
}

new PeriodicalExecuter(online_callback, {FREQUENCY});
		
function ajax_display_desc(id)
{
	var url = '../online/xmlhttprequest.php?display_desc='+id;

	$('l' + id).update('<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />');

	var elt = $('online-tr-'+id);
	if (elt)
	{
		var tmp = elt.getStyle('display');
		if(tmp!='none') tmp = 'none'; else tmp = 'block';
		elt.setStyle({display:tmp});
		
		var cookie = new Cookie({});
		var v = cookie.readData();
		v.set(id, tmp);
		cookie.writeData(v);
	}
	$('l'+id).update('');
}

</script>