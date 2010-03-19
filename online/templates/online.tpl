
# INCLUDE online_table #

<script type="text/javascript">

var Cookie = Class.create({
	initialize: function(options)
	{
		this.options = {
			key: 'online_data'
		};
		
		Object.extend(this.options, options || {});
	},
	
	readData: function()
	{
		if (Object.isUndefined(this.options.key))
			return undefined;
		var tmp = document.cookie || '';
		if (tmp.length > 0)
		{
			var t = unescape(tmp);
			var data = t.split('; ');
			var keyEQ = this.options.key + '=';
			max = data.length;
			for(i=0; i < max; i++)
			{
				var len = data[i].indexOf(keyEQ);
				if( len >= 0)
				{
					var x = data[i].substring(len+keyEQ.length, data[i].length);
					x = x.evalJSON();
					if (Object.isArray(x))
					{
						return x;
					}
					else
					{
						return new Array();
					}
				}
			}
		}
		return new Array();
	},
	
	writeData: function(value)
	{
		if (Object.isUndefined(this.options.key))
			return undefined;
		if(!Object.isUndefined(value))
		{
			var tmp = value.toJSON();
			document.cookie = this.options.key+ '=' + escape(tmp);
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
				var t = $$('div#online_table tr.online-tr');
				/*
				t.each( function(x)
				{
					alert(x.readAttribute('id'));
				});
				*/
				var cookie = new Cookie();
				var v = cookie.readData();
				for(i=0; i<v.length; i++)
				{
					var d = $('online-td-'+i);
					if (Object.isElement(d))
					{
						d.setStyle({display:v[i]});
					}
				}
				
			},
			onFailure: function()
			{
				alert('Error in online_callback');
			}
		});
}

new PeriodicalExecuter(online_callback, {FREQUENCY});
		
function ajax_display_desc(id)
{
	var url = '../online/xmlhttprequest.php?display_desc='+id;
	var elt = $('online-td-'+id);
	
	$('l' + id).update('<img src="{PATH_TO_ROOT}/templates/{THEME}/images/loading_mini.gif" alt="" class="valign_middle" />');
	
	var tmp = elt.getStyle('display');
	if(tmp!='none') tmp = 'none'; else tmp = 'block';
	elt.setStyle({display:tmp});
	
	var cookie = new Cookie({});
	var v = cookie.readData();
	v[id] = tmp;
	cookie.writeData(v);
	$('l'+id).update('');
}



</script>