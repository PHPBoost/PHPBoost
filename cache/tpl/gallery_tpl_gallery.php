		<script type="text/javascript" src="<?php echo isset($this->_var['MODULE_DATA_PATH']) ? $this->_var['MODULE_DATA_PATH'] : ''; ?>/images/js/prototype.js"></script>
		<script type="text/javascript" src="<?php echo isset($this->_var['MODULE_DATA_PATH']) ? $this->_var['MODULE_DATA_PATH'] : ''; ?>/images/js/scriptaculous.js?load=effects"></script>
		<script type="text/javascript" src="<?php echo isset($this->_var['MODULE_DATA_PATH']) ? $this->_var['MODULE_DATA_PATH'] : ''; ?>/images/js/lightbox.js"></script>

		<script type="text/javascript">
		<!--		
		function Confirm_file() {
			return confirm("<?php echo isset($this->_var['L_CONFIRM_DEL_FILE']) ? $this->_var['L_CONFIRM_DEL_FILE'] : ''; ?>");
		}			
		var previous_path_pics = '';
		function display_pics(id, path, type)
		{
			document.getElementById('pics_max').innerHTML = '';					
			if( previous_path_pics != path )
			{	
				document.getElementById('pics_max').innerHTML = '<img src="' + path + '" alt="" /></a>';	
				previous_path_pics = path;
			}
		}
		function display_pics_popup(path, width, height)
		{
			width = parseInt(width);
			height = parseInt(height);
			if( height == 0 )
				height = screen.height - 150;
			if( width == 0 )
				width = screen.width - 200;
			window.open(path, '', 'width='+(width+17)+', height='+(height+17)+', location=no, status=no, toolbar=no, scrollbars=1, resizable=yes');
		}
		function display_rename_file(id, previous_name, previous_cut_name)
		{
			if( document.getElementById('fi' + id) )
			{	
				document.getElementById('fi_' + id).style.display = 'none';
				document.getElementById('fi' + id).style.display = 'inline';
				document.getElementById('fi' + id).innerHTML = '<input size="27" type="text" name="fiinput' + id + '" id="fiinput' + id + '" class="text" value="' + previous_name + '" onblur="rename_file(\'' + id + '\', \'' + previous_cut_name.replace(/\'/g, "\\\'") + '\');" />';
				document.getElementById('fiinput' + id).focus();
			}
		}	
		function rename_file(id_file, previous_cut_name)
		{
			var xhr_object = null;
			var data = null;
			var name = document.getElementById("fiinput" + id_file).value;
			var filename = "xmlhttprequest.php?rename_pics=1";
			var regex = /\/|\\|\||\?|<|>|\"/;
			
			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non supporté par le navigateur
			    return;
			
			if( regex.test(name) ) //interdiction des caractères spéciaux dans la nom.
			{
				alert("<?php echo isset($this->_var['L_FILE_FORBIDDEN_CHARS']) ? $this->_var['L_FILE_FORBIDDEN_CHARS'] : ''; ?>");	
				document.getElementById('fi_' + id_file).style.display = 'inline';
				document.getElementById('fi' + id_file).style.display = 'none';
			}
			else
			{
				document.getElementById('img' + id_file).innerHTML = '<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/loading_mini.gif" alt="" class="valign_middle" />';

				data = "id_file=" + id_file + "&name=" + name + "&previous_name=" + previous_cut_name;
				xhr_object.open("POST", filename, true);

				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '0' )
					{
						document.getElementById('fi' + id_file).style.display = 'none';
						document.getElementById('fi_' + id_file).style.display = 'inline';
						document.getElementById('fi_' + id_file).innerHTML = xhr_object.responseText;
						document.getElementById('fihref' + id_file).innerHTML = '<a href="javascript:display_rename_file(\'' + id_file + '\', \'' + name.replace(/\'/g, "\\\'") + '\', \'' + xhr_object.responseText.replace(/\'/g, "\\\'") + '\');"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/edit.png" alt="" class="valign_middle" /></a>';
						document.getElementById('img' + id_file).innerHTML = '';
					}
					else if( xhr_object.readyState == 4 && xhr_object.responseText == '0' )
						document.getElementById('img' + id_file).innerHTML = '';
				}

				xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr_object.send(data);
			}
		}
		function pics_aprob(id_file, aprob)
		{
			var xhr_object = null;
			var data = null;
			var filename = "xmlhttprequest.php?aprob_pics=1";

			document.getElementById('img' + id_file).innerHTML = '<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/loading_mini.gif" alt="" class="valign_middle" />';
			
			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non supporté par le navigateur
			    return;
			
			data = "id_file=" + id_file;
			xhr_object.open("POST", filename, true);

			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '-1' )
				{	
					var img_aprob;
					if( xhr_object.responseText == 0 )
						img_aprob = 'unaprob.png';
					else
						img_aprob = 'aprob.png';
					
					document.getElementById('img' + id_file).innerHTML = '';
					if( document.getElementById('img_aprob' + id_file) )
						document.getElementById('img_aprob' + id_file).src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/' + img_aprob;
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '-1' )
					document.getElementById('img' + id_file).innerHTML = '';
			}

			xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr_object.send(data);
		}
		
		var delay = 2000; //Délai après lequel le bloc est automatiquement masqué, après le départ de la souris.
		var timeout;
		var displayed = false;
		var previous = '';
		var started = false;
		
		//Affiche le bloc.
		function pics_display_block(divID)
		{
			if( timeout )
				clearTimeout(timeout);
			
			if( document.getElementById(previous) )
			{		
				document.getElementById(previous).style.display = 'none';
				started = false
			}	

			if( document.getElementById('move' + divID) )
			{			
				document.getElementById('move' + divID).style.display = 'block';
				previous = 'move' + divID;
				started = true;
			}
		}
		//Cache le bloc.
		function pics_hide_block(idfield, stop)
		{
			if( stop && timeout )
				clearTimeout(timeout);
			else if( started )
				timeout = setTimeout('pics_display_block()', delay);
		}
		
		<?php echo isset($this->_var['ARRAY_JS']) ? $this->_var['ARRAY_JS'] : ''; ?>
		var start_thumb = <?php echo isset($this->_var['START_THUMB']) ? $this->_var['START_THUMB'] : ''; ?>;
		//Miniatures défilantes.
		function display_thumbnails(direction)
		{			
			if( direction == 'left' )
			{	
				if( start_thumb > 0 )
				{
					start_thumb--;
					if( start_thumb == 0 )
						document.getElementById('display_left').innerHTML = '';
					else
						document.getElementById('display_left').innerHTML = '<a href="javascript:display_thumbnails(\'left\')"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/left.png" class="valign_middle" alt="" /></a>';
					document.getElementById('display_right').innerHTML = '<a href="javascript:display_thumbnails(\'right\')"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/right.png" class="valign_middle" alt="" /></a>';
				}
				else
					return;
			}
			else if( direction == 'right' )
			{
				if( start_thumb <= <?php echo isset($this->_var['MAX_START']) ? $this->_var['MAX_START'] : ''; ?> )
				{
					start_thumb++;
					if( start_thumb == (<?php echo isset($this->_var['MAX_START']) ? $this->_var['MAX_START'] : ''; ?> + 1) )
						document.getElementById('display_right').innerHTML = '';
					else
						document.getElementById('display_right').innerHTML = '<a href="javascript:display_thumbnails(\'right\')"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/right.png" class="valign_middle" alt="" /></a>';
					document.getElementById('display_left').innerHTML = '<a href="javascript:display_thumbnails(\'left\')"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/left.png" class="valign_middle" alt="" /></a>';
				}
				else
					return;
			}	
			
			var j = 0;
			for(var i = 0; i <= <?php echo isset($this->_var['NBR_PICS']) ? $this->_var['NBR_PICS'] : ''; ?>; i++)
			{
				if( document.getElementById('thumb' + i) ) 
				{
					var key_left = start_thumb + j;
					var key_right = start_thumb + j;
					if( direction == 'left' && array_pics[key_left] )							
					{	
						document.getElementById('thumb' + i).innerHTML = '<a href="gallery' + array_pics[key_left]['link'] + '"><img src="pics/thumbnails/' + array_pics[key_left]['path'] + '" alt="" /></a>';
						j++;
					}
					else if( direction == 'right' && array_pics[key_right] ) 
					{
						document.getElementById('thumb' + i).innerHTML = '<a href="gallery' + array_pics[key_right]['link'] + '"><img 	src="pics/thumbnails/' + array_pics[key_right]['path'] + '" alt="" /></a>';				
						j++;
					}
				}
			}
		}
		
		var note_max = <?php echo isset($this->_var['NOTE_MAX']) ? $this->_var['NOTE_MAX'] : ''; ?>;
		var array_note = new Array();		
		var timeout = null;
		var on_img = 0;
		function select_stars(divid, note)
		{
			var star_img;
			var decimal;
			for(var i = 1; i <= note_max; i++)
			{
				star_img = 'stars.png';
				if( note < i )
				{							
					decimal = i - note;
					if( decimal >= 1 )
						star_img = 'stars0.png';
					else if( decimal >= 0.75 )
						star_img = 'stars1.png';
					else if( decimal >= 0.50 )
						star_img = 'stars2.png';
					else
						star_img = 'stars3.png';
				}
							
				if( document.getElementById(divid + '_stars' + i) )
					document.getElementById(divid + '_stars' + i).src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/' + star_img;
			}
		}
		function out_div(divid, note)
		{
			if( timeout == null )
				timeout = setTimeout('select_stars(' + divid + ', ' + note + ');on_img = 0;', '50');
		}		
		function over_div()
		{
			if( on_img == 0 )
				on_img = 1;
			clearTimeout(timeout);
			timeout = null;
		}
		function send_note(id_file, idcat, note)
		{
			var xhr_object = null;
			var data = null;
			var filename = "xmlhttprequest.php?note_pics=1";
			var regex = /\/|\\|\||\?|<|>|\"/;
			var get_nbrnote;
			var get_note;
			
			document.getElementById('img' + id_file).innerHTML = '<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/loading_mini.gif" alt="" class="valign_middle" />';
			if(window.XMLHttpRequest) // Firefox
			   xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
			   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else // XMLHttpRequest non supporté par le navigateur
			    return;
			
			data = "id_file=" + id_file + "&note=" + note + "&idcat=" + idcat;
			xhr_object.open("POST", filename, true);
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
				{	
					document.getElementById('img' + id_file).innerHTML = '';
					if( xhr_object.responseText == '-1' )
						alert("<?php echo isset($this->_var['L_ALREADY_VOTED']) ? $this->_var['L_ALREADY_VOTED'] : ''; ?>");
					else
					{	
						eval(xhr_object.responseText);
						array_note[id_file] = get_note;
						select_stars(id_file, get_note);
						if( document.getElementById(id_file + '_note') )
							document.getElementById(id_file + '_note').innerHTML = '(' + get_nbrnote + ' ' + ((get_nbrnote > 1) ? '<?php echo isset($this->_var['L_VOTES']) ? $this->_var['L_VOTES'] : ''; ?>' : '<?php echo isset($this->_var['L_VOTE']) ? $this->_var['L_VOTE'] : ''; ?>') + ')';
					}				
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
					document.getElementById('img' + id_file).innerHTML = '';
			}

			xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr_object.send(data);
		}
		-->
		</script> 

		<?php if( !isset($this->_block['error_handler']) || !is_array($this->_block['error_handler']) ) $this->_block['error_handler'] = array();
foreach($this->_block['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$this->_block['error_handler'][$error_handler_key]; ?>
		<span id="errorh"></span>
		<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
			<br />	
		</div>
		<br />	
		<?php } ?>

		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<a href="gallery.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_GALLERY']) ? $this->_var['L_GALLERY'] : ''; ?></a> &raquo; <?php echo isset($this->_var['U_GALLERY_CAT_LINKS']) ? $this->_var['U_GALLERY_CAT_LINKS'] : ''; echo ' '; echo isset($this->_var['ADD_PICS']) ? $this->_var['ADD_PICS'] : ''; ?>
				</div>
				<div style="float:right">
					<?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>
				</div>
			</div>
			<div class="module_contents">
				<div id="dynamic_menu">
					<div style="float:right;">
						<div style="float:left;" onmouseover="show_menu(1);" onmouseout="hide_menu();">
							<h5 onclick="temporise_menu(1)" style="margin-right:20px;" class="horizontal"><img src="../wiki/templates/images/contribuate.png" class="valign_middle" alt="" />&nbsp;<?php echo isset($this->_var['L_DISPLAY']) ? $this->_var['L_DISPLAY'] : ''; ?>&nbsp;</h5>
							<div id="smenu1" class="horizontal_block">
								<ul>
									<li><?php echo isset($this->_var['U_BEST_VIEWS']) ? $this->_var['U_BEST_VIEWS'] : ''; ?></li>
									<li><?php echo isset($this->_var['U_BEST_NOTES']) ? $this->_var['U_BEST_NOTES'] : ''; ?></li>
								</ul>
								<span class="dm_bottom"></span>
							</div>
						</div>
						<div style="float:left;" onmouseover="show_menu(2);" onmouseout="hide_menu();">
							<h5 onclick="temporise_menu(2)" style="margin-right:5px;" class="horizontal"><img src="../wiki/templates/images/tools.png" class="valign_middle" alt="" />&nbsp;<?php echo isset($this->_var['L_ORDER_BY']) ? $this->_var['L_ORDER_BY'] : ''; ?>&nbsp;</h5>
							<div id="smenu2" class="horizontal_block">

								<ul>
									<?php if( !isset($this->_block['order']) || !is_array($this->_block['order']) ) $this->_block['order'] = array();
foreach($this->_block['order'] as $order_key => $order_value) {
$_tmpb_order = &$this->_block['order'][$order_key]; ?>
									<li><?php echo isset($_tmpb_order['ORDER_BY']) ? $_tmpb_order['ORDER_BY'] : ''; ?></li>
									<?php } ?>
								</ul>
								<span class="dm_bottom"></span>
							</div>
						</div>
						<div style="float:left;" onmouseover="show_menu(3);" onmouseout="hide_menu();">
							<h5 onclick="temporise_menu(3)" style="margin-right:5px;" class="horizontal"><img src="../wiki/templates/images/tools.png" class="valign_middle" alt="" />&nbsp;<?php echo isset($this->_var['L_DIRECTION']) ? $this->_var['L_DIRECTION'] : ''; ?>&nbsp;</h5>
							<div id="smenu3" class="horizontal_block">
								<ul>
									<li><?php echo isset($this->_var['U_ASC']) ? $this->_var['U_ASC'] : ''; ?></li>
									<li><?php echo isset($this->_var['U_DESC']) ? $this->_var['U_DESC'] : ''; ?></li>
								</ul>
								<span class="dm_bottom"></span>
							</div>
						</div>
					</div>
				</div>
				
				<br /><br />
				
				<?php if( !isset($this->_block['cat']) || !is_array($this->_block['cat']) ) $this->_block['cat'] = array();
foreach($this->_block['cat'] as $cat_key => $cat_value) {
$_tmpb_cat = &$this->_block['cat'][$cat_key]; ?>
				<table class="module_table" style="width:100%">
					<tr>
						<th colspan="<?php echo isset($this->_var['COLSPAN']) ? $this->_var['COLSPAN'] : ''; ?>">
							<?php echo isset($this->_var['L_CATEGORIES']) ? $this->_var['L_CATEGORIES'] : ''; echo ' '; echo isset($_tmpb_cat['EDIT']) ? $_tmpb_cat['EDIT'] : ''; ?>
						</th>
					</tr>
					
					<?php if( !isset($_tmpb_cat['list']) || !is_array($_tmpb_cat['list']) ) $_tmpb_cat['list'] = array();
foreach($_tmpb_cat['list'] as $list_key => $list_value) {
$_tmpb_list = &$_tmpb_cat['list'][$list_key]; ?>
					<?php echo isset($_tmpb_list['TR_START']) ? $_tmpb_list['TR_START'] : ''; ?>								
						<td class="row2" style="vertical-align:bottom;text-align:center;width:<?php echo isset($this->_var['COLUMN_WIDTH_CATS']) ? $this->_var['COLUMN_WIDTH_CATS'] : ''; ?>%">
							<a href="gallery<?php echo isset($_tmpb_list['U_CAT']) ? $_tmpb_list['U_CAT'] : ''; ?>"><?php echo isset($_tmpb_list['IMG']) ? $_tmpb_list['IMG'] : ''; ?></a>
							
							<br />
							<a href="gallery<?php echo isset($_tmpb_list['U_CAT']) ? $_tmpb_list['U_CAT'] : ''; ?>"><?php echo isset($_tmpb_list['CAT']) ? $_tmpb_list['CAT'] : ''; ?></a> <?php echo isset($_tmpb_list['EDIT']) ? $_tmpb_list['EDIT'] : ''; ?>
							<br />
							<span class="text_small"><?php echo isset($_tmpb_list['DESC']) ? $_tmpb_list['DESC'] : ''; ?></span> 
							<br />
							<?php echo isset($_tmpb_list['LOCK']) ? $_tmpb_list['LOCK'] : ''; ?> <span class="text_small"><?php echo isset($_tmpb_list['L_NBR_PICS']) ? $_tmpb_list['L_NBR_PICS'] : ''; ?></span> 
						</td>	
					<?php echo isset($_tmpb_list['TR_END']) ? $_tmpb_list['TR_END'] : ''; ?>
					<?php } ?>						
				
					<?php if( !isset($_tmpb_cat['end_td']) || !is_array($_tmpb_cat['end_td']) ) $_tmpb_cat['end_td'] = array();
foreach($_tmpb_cat['end_td'] as $end_td_key => $end_td_value) {
$_tmpb_end_td = &$_tmpb_cat['end_td'][$end_td_key]; ?>
						<?php echo isset($_tmpb_end_td['TD_END']) ? $_tmpb_end_td['TD_END'] : ''; ?>
					<?php echo isset($_tmpb_end_td['TR_END']) ? $_tmpb_end_td['TR_END'] : ''; ?>
					<?php } ?>					
				</table>	
				<?php } ?>
				
				
				<?php if( !isset($this->_block['pics']) || !is_array($this->_block['pics']) ) $this->_block['pics'] = array();
foreach($this->_block['pics'] as $pics_key => $pics_value) {
$_tmpb_pics = &$this->_block['pics'][$pics_key]; ?>
				<p style="text-align:center">		
					<span id="pics_max"></span>
					<br />
					<?php echo isset($this->_var['PAGINATION_PICS']) ? $this->_var['PAGINATION_PICS'] : ''; ?>
				</p>				
				<table class="module_table" style="width:100%">				
					<tr>
						<th colspan="<?php echo isset($this->_var['COLSPAN']) ? $this->_var['COLSPAN'] : ''; ?>">
							<?php echo isset($this->_var['GALLERY']) ? $this->_var['GALLERY'] : ''; echo ' '; echo isset($_tmpb_pics['EDIT']) ? $_tmpb_pics['EDIT'] : ''; ?>
						</th>
					</tr>
					
					<?php if( !isset($_tmpb_pics['pics_max']) || !is_array($_tmpb_pics['pics_max']) ) $_tmpb_pics['pics_max'] = array();
foreach($_tmpb_pics['pics_max'] as $pics_max_key => $pics_max_value) {
$_tmpb_pics_max = &$_tmpb_pics['pics_max'][$pics_max_key]; ?>
					<tr>
						<td colspan="<?php echo isset($this->_var['COLSPAN']) ? $this->_var['COLSPAN'] : ''; ?>" class="row1">
							<p style="text-align:center">
								<?php echo isset($_tmpb_pics_max['IMG']) ? $_tmpb_pics_max['IMG'] : ''; ?>	
							</p>
							<table style="border-collapse:collapse;margin:auto;width:400px" class="row2">
								<tr>
									<td>
										&nbsp;&nbsp;&nbsp;<?php echo isset($_tmpb_pics_max['U_PREVIOUS']) ? $_tmpb_pics_max['U_PREVIOUS'] : ''; ?> 
									</td>	
									<td style="text-align:right;">
										<?php echo isset($_tmpb_pics_max['U_NEXT']) ? $_tmpb_pics_max['U_NEXT'] : ''; ?>&nbsp;&nbsp;&nbsp;
									</td>
								</tr>
							</table>
							<br />
							<table style="border-collapse:collapse;margin:auto;width:100%" class="row2">
								<tr>
									<th colspan="2">
										<?php echo isset($this->_var['L_INFORMATIONS']) ? $this->_var['L_INFORMATIONS'] : ''; ?>
									</th>
								</tr>
								<tr>
									<td class="row2 text_small" style="width:50%;border:none;padding:4px;">
										<strong><?php echo isset($this->_var['L_NAME']) ? $this->_var['L_NAME'] : ''; ?>:</strong> <?php echo isset($_tmpb_pics_max['NAME']) ? $_tmpb_pics_max['NAME'] : ''; ?>
									</td>
									<td class="row2 text_small" style="border:none;padding:4px;">
										<strong><?php echo isset($this->_var['L_POSTOR']) ? $this->_var['L_POSTOR'] : ''; ?>:</strong> <?php echo isset($_tmpb_pics_max['POSTOR']) ? $_tmpb_pics_max['POSTOR'] : ''; ?>
									</td>
								</tr>
								<tr>										
									<td class="row2 text_small" style="border:none;padding:4px;">
										<strong><?php echo isset($this->_var['L_VIEWS']) ? $this->_var['L_VIEWS'] : ''; ?>:</strong> <?php echo isset($_tmpb_pics_max['VIEWS']) ? $_tmpb_pics_max['VIEWS'] : ''; ?>
									</td>
									<td class="row2 text_small" style="border:none;padding:4px;">
										<strong><?php echo isset($this->_var['L_ADD_ON']) ? $this->_var['L_ADD_ON'] : ''; ?>:</strong> <?php echo isset($_tmpb_pics_max['DATE']) ? $_tmpb_pics_max['DATE'] : ''; ?>
									</td>
								</tr>
								<tr>										
									<td class="row2 text_small" style="border:none;padding:4px;">
										<strong><?php echo isset($this->_var['L_DIMENSION']) ? $this->_var['L_DIMENSION'] : ''; ?>:</strong> <?php echo isset($_tmpb_pics_max['DIMENSION']) ? $_tmpb_pics_max['DIMENSION'] : ''; ?>
									</td>
									<td class="row2 text_small" style="border:none;padding:4px;">
										<strong><?php echo isset($this->_var['L_SIZE']) ? $this->_var['L_SIZE'] : ''; ?>:</strong> <?php echo isset($_tmpb_pics_max['SIZE']) ? $_tmpb_pics_max['SIZE'] : ''; ?> Ko
									</td>
								</tr>
								<tr>										
									<td class="row2 text_small" style="border:none;padding:4px;">
										<strong><?php echo isset($this->_var['L_NOTE']) ? $this->_var['L_NOTE'] : ''; ?>:</strong> <?php echo isset($_tmpb_pics_max['NOTE']) ? $_tmpb_pics_max['NOTE'] : ''; ?>
									</td>
									<td class="row2 text_small" style="border:none;padding:4px;vertical-align:top">
										<strong><?php echo isset($this->_var['L_COM']) ? $this->_var['L_COM'] : ''; ?>:</strong> <?php echo isset($_tmpb_pics_max['COM']) ? $_tmpb_pics_max['COM'] : ''; ?>
									</td>
								</tr>
								
								<?php if( !isset($_tmpb_pics_max['modo']) || !is_array($_tmpb_pics_max['modo']) ) $_tmpb_pics_max['modo'] = array();
foreach($_tmpb_pics_max['modo'] as $modo_key => $modo_value) {
$_tmpb_modo = &$_tmpb_pics_max['modo'][$modo_key]; ?>
								<tr>										
									<td colspan="2" class="row2 text_small" style="border:none;padding:4px;">
										&nbsp;&nbsp;&nbsp;<span id="fihref<?php echo isset($_tmpb_pics_max['ID']) ? $_tmpb_pics_max['ID'] : ''; ?>"><a href="javascript:display_rename_file('<?php echo isset($_tmpb_pics_max['ID']) ? $_tmpb_pics_max['ID'] : ''; ?>', '<?php echo isset($_tmpb_modo['RENAME']) ? $_tmpb_modo['RENAME'] : ''; ?>', '<?php echo isset($_tmpb_modo['RENAME_CUT']) ? $_tmpb_modo['RENAME_CUT'] : ''; ?>');"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/edit.png" alt="<?php echo isset($this->_var['L_EDIT']) ? $this->_var['L_EDIT'] : ''; ?>" class="valign_middle" /></a></span>
										
										<a href="gallery<?php echo isset($_tmpb_modo['U_DEL']) ? $_tmpb_modo['U_DEL'] : ''; ?>" onClick="javascript:return Confirm_file();" title="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/delete.png" alt="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" class="valign_middle" /></a> 
							
										<div style="position:absolute;z-index:100;margin-top:110px;float:left;display:none;" id="move<?php echo isset($_tmpb_pics_max['ID']) ? $_tmpb_pics_max['ID'] : ''; ?>">
											<div class="bbcode_block" style="width:190px;overflow:auto;" onmouseover="pics_hide_block(<?php echo isset($_tmpb_pics_max['ID']) ? $_tmpb_pics_max['ID'] : ''; ?>, 1);" onmouseout="pics_hide_block(<?php echo isset($_tmpb_pics_max['ID']) ? $_tmpb_pics_max['ID'] : ''; ?>, 0);">
												<div style="margin-bottom:4px;"><strong><?php echo isset($this->_var['L_MOVETO']) ? $this->_var['L_MOVETO'] : ''; ?></strong>:</div>
												<select class="valign_middle" name="<?php echo isset($_tmpb_pics_max['ID']) ? $_tmpb_pics_max['ID'] : ''; ?>cat" onchange="document.location = 'gallery<?php echo isset($_tmpb_modo['U_MOVE']) ? $_tmpb_modo['U_MOVE'] : ''; ?>">
													<?php echo isset($_tmpb_pics_max['CAT']) ? $_tmpb_pics_max['CAT'] : ''; ?>
												</select>
												<br /><br />
											</div>
										</div>
										<a href="javascript:pics_display_block(<?php echo isset($_tmpb_pics_max['ID']) ? $_tmpb_pics_max['ID'] : ''; ?>);" onmouseover="pics_hide_block(<?php echo isset($_tmpb_pics_max['ID']) ? $_tmpb_pics_max['ID'] : ''; ?>, 1);" onmouseout="pics_hide_block(<?php echo isset($_tmpb_pics_max['ID']) ? $_tmpb_pics_max['ID'] : ''; ?>, 0);" class="bbcode_hover" title="<?php echo isset($this->_var['L_MOVETO']) ? $this->_var['L_MOVETO'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/move.png" alt="" class="valign_middle" /></a>
										
										
										<a href="javascript:pics_aprob(<?php echo isset($_tmpb_pics_max['ID']) ? $_tmpb_pics_max['ID'] : ''; ?>);" title="<?php echo isset($this->_var['L_APROB_IMG']) ? $this->_var['L_APROB_IMG'] : ''; ?>"><img id="img_aprob<?php echo isset($_tmpb_pics_max['ID']) ? $_tmpb_pics_max['ID'] : ''; ?>" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_modo['IMG_APROB']) ? $_tmpb_modo['IMG_APROB'] : ''; ?>" alt="<?php echo isset($this->_var['L_APROB_IMG']) ? $this->_var['L_APROB_IMG'] : ''; ?>" title="<?php echo isset($this->_var['L_APROB_IMG']) ? $this->_var['L_APROB_IMG'] : ''; ?>" class="valign_middle" /></a>
										&nbsp;<span id="img<?php echo isset($_tmpb_pics_max['ID']) ? $_tmpb_pics_max['ID'] : ''; ?>"></span>
									</td>
								</tr>
								<?php } ?>
								
							</table>
							
							<br /><br />
							
							<table class="module_table" style="width:100%;">
								<tr>
									<th colspan="<?php echo isset($_tmpb_pics_max['COLSPAN']) ? $_tmpb_pics_max['COLSPAN'] : ''; ?>">
										<?php echo isset($this->_var['L_THUMBNAILS']) ? $this->_var['L_THUMBNAILS'] : ''; ?>
									</th>
								</tr>
								<tr>
									<td class="row2" style="width:50px;text-align:center">
										<?php echo isset($_tmpb_pics_max['U_LEFT_THUMBNAILS']) ? $_tmpb_pics_max['U_LEFT_THUMBNAILS'] : ''; ?>
									</td>
									
									<?php if( !isset($_tmpb_pics_max['list_preview_pics']) || !is_array($_tmpb_pics_max['list_preview_pics']) ) $_tmpb_pics_max['list_preview_pics'] = array();
foreach($_tmpb_pics_max['list_preview_pics'] as $list_preview_pics_key => $list_preview_pics_value) {
$_tmpb_list_preview_pics = &$_tmpb_pics_max['list_preview_pics'][$list_preview_pics_key]; ?>
										<?php echo isset($_tmpb_list_preview_pics['PICS']) ? $_tmpb_list_preview_pics['PICS'] : ''; ?>
									<?php } ?>
									
									<td class="row2" style="width:50px;text-align:center">
										<?php echo isset($_tmpb_pics_max['U_RIGHT_THUMBNAILS']) ? $_tmpb_pics_max['U_RIGHT_THUMBNAILS'] : ''; ?>
									</td>
								</tr>
							</table>
							
							<br />
							<?php $this->tpl_include('handle_com'); ?>
						</td>
					</tr>
					<?php } ?>
					
					<?php if( !isset($_tmpb_pics['list']) || !is_array($_tmpb_pics['list']) ) $_tmpb_pics['list'] = array();
foreach($_tmpb_pics['list'] as $list_key => $list_value) {
$_tmpb_list = &$_tmpb_pics['list'][$list_key]; ?>
					<?php echo isset($_tmpb_list['TR_START']) ? $_tmpb_list['TR_START'] : ''; ?>
						<td class="row2" style="padding:0;padding-top:2px;width:<?php echo isset($this->_var['COLUMN_WIDTH_PICS']) ? $this->_var['COLUMN_WIDTH_PICS'] : ''; ?>%;vertical-align:top">
							<table style="border:none;border-collapse:collapse;width:100%">
								<tr>
									<td style="height:<?php echo isset($this->_var['HEIGHT_MAX']) ? $this->_var['HEIGHT_MAX'] : ''; ?>px;padding:0;text-align:center;">
										<span id="pics<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>"><?php echo isset($_tmpb_list['U_DISPLAY']) ? $_tmpb_list['U_DISPLAY'] : '';  echo isset($_tmpb_list['IMG']) ? $_tmpb_list['IMG'] : ''; ?></a></span>
									</td>
								</tr>
								<tr>
									<td class="row1 text_small" style="height:90px;text-align:center;vertical-align:top;">
										<?php echo isset($_tmpb_list['NAME']) ? $_tmpb_list['NAME'] : ''; ?>
										<?php echo isset($_tmpb_list['POSTOR']) ? $_tmpb_list['POSTOR'] : ''; ?>
										<?php echo isset($_tmpb_list['VIEWS']) ? $_tmpb_list['VIEWS'] : ''; ?>
										<?php echo isset($_tmpb_list['COM']) ? $_tmpb_list['COM'] : ''; ?>
										<?php echo isset($_tmpb_list['NOTE']) ? $_tmpb_list['NOTE'] : ''; ?>
										
										<div>
										
										<?php if( !isset($_tmpb_list['modo']) || !is_array($_tmpb_list['modo']) ) $_tmpb_list['modo'] = array();
foreach($_tmpb_list['modo'] as $modo_key => $modo_value) {
$_tmpb_modo = &$_tmpb_list['modo'][$modo_key]; ?>
											<span id="fihref<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>"><a href="javascript:display_rename_file('<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>', '<?php echo isset($_tmpb_modo['RENAME']) ? $_tmpb_modo['RENAME'] : ''; ?>', '<?php echo isset($_tmpb_modo['RENAME_CUT']) ? $_tmpb_modo['RENAME_CUT'] : ''; ?>');" title="<?php echo isset($this->_var['L_EDIT']) ? $this->_var['L_EDIT'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/edit.png" alt="<?php echo isset($this->_var['L_EDIT']) ? $this->_var['L_EDIT'] : ''; ?>" class="valign_middle" /></a></span>
											
											<a href="gallery<?php echo isset($_tmpb_modo['U_DEL']) ? $_tmpb_modo['U_DEL'] : ''; ?>" onClick="javascript:return Confirm_file();" title="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/delete.png" alt="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" class="valign_middle" /></a> 
								
											<div style="position:absolute;z-index:100;margin-top:110px;float:left;display:none;" id="move<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>">
												<div class="bbcode_block" style="width:190px;overflow:auto;" onmouseover="pics_hide_block(<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>, 1);" onmouseout="pics_hide_block(<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>, 0);">
													<div style="margin-bottom:4px;"><strong><?php echo isset($this->_var['L_MOVETO']) ? $this->_var['L_MOVETO'] : ''; ?></strong>:</div>
													<select class="valign_middle" name="<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>cat" onchange="document.location = 'gallery<?php echo isset($_tmpb_modo['U_MOVE']) ? $_tmpb_modo['U_MOVE'] : ''; ?>">
														<?php echo isset($_tmpb_list['CAT']) ? $_tmpb_list['CAT'] : ''; ?>
													</select>
													<br /><br />
												</div>
											</div>
											<a href="javascript:pics_display_block(<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>);" onmouseover="pics_hide_block(<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>, 1);" onmouseout="pics_hide_block(<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>, 0);" class="bbcode_hover" title="<?php echo isset($this->_var['L_MOVETO']) ? $this->_var['L_MOVETO'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/move.png" alt="" class="valign_middle" /></a>
											
											
											<a href="javascript:pics_aprob(<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>);" title="<?php echo isset($_tmpb_list['L_APROB_IMG']) ? $_tmpb_list['L_APROB_IMG'] : ''; ?>"><img id="img_aprob<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_modo['IMG_APROB']) ? $_tmpb_modo['IMG_APROB'] : ''; ?>" alt="<?php echo isset($_tmpb_list['L_APROB_IMG']) ? $_tmpb_list['L_APROB_IMG'] : ''; ?>" title="<?php echo isset($_tmpb_list['L_APROB_IMG']) ? $_tmpb_list['L_APROB_IMG'] : ''; ?>" class="valign_middle" /></a>
										
										<?php } ?>											
										&nbsp;<span id="img<?php echo isset($_tmpb_list['ID']) ? $_tmpb_list['ID'] : ''; ?>"></span>
										
										</div>
									</td>
								</tr>
							</table>
						</td>
					<?php echo isset($_tmpb_list['TR_END']) ? $_tmpb_list['TR_END'] : ''; ?>
					<?php } ?>				
				
					<?php if( !isset($_tmpb_pics['end_td_pics']) || !is_array($_tmpb_pics['end_td_pics']) ) $_tmpb_pics['end_td_pics'] = array();
foreach($_tmpb_pics['end_td_pics'] as $end_td_pics_key => $end_td_pics_value) {
$_tmpb_end_td_pics = &$_tmpb_pics['end_td_pics'][$end_td_pics_key]; ?>
						<?php echo isset($_tmpb_end_td_pics['TD_END']) ? $_tmpb_end_td_pics['TD_END'] : ''; ?>
					<?php echo isset($_tmpb_end_td_pics['TR_END']) ? $_tmpb_end_td_pics['TR_END'] : ''; ?>
					<?php } ?>					
				</table>	
				
				<p style="text-align:center">		
					<?php echo isset($this->_var['PAGINATION_PICS']) ? $this->_var['PAGINATION_PICS'] : ''; ?>
				</p>			
				<?php } ?>
				<br />
				<p style="text-align:center" class="text_small">
					<?php echo isset($this->_var['L_TOTAL_IMG']) ? $this->_var['L_TOTAL_IMG'] : ''; ?>
				</p>	
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:left" class="text_strong">
					<a href="gallery.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_GALLERY']) ? $this->_var['L_GALLERY'] : ''; ?></a> &raquo; <?php echo isset($this->_var['U_GALLERY_CAT_LINKS']) ? $this->_var['U_GALLERY_CAT_LINKS'] : ''; echo ' '; echo isset($this->_var['ADD_PICS']) ? $this->_var['ADD_PICS'] : ''; ?>
				</div>
				<div style="float:right">
					<?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>
				</div>
			</div>
		</div>
		