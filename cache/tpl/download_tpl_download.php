		<?php echo isset($this->_var['JAVA']) ? $this->_var['JAVA'] : ''; ?>

		<?php if( !isset($this->_block['cat']) || !is_array($this->_block['cat']) ) $this->_block['cat'] = array();
foreach($this->_block['cat'] as $cat_key => $cat_value) {
$_tmpb_cat = &$this->_block['cat'][$cat_key]; ?>
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<?php echo isset($_tmpb_cat['L_CATEGORIE']) ? $_tmpb_cat['L_CATEGORIE'] : ''; echo ' '; echo isset($_tmpb_cat['EDIT']) ? $_tmpb_cat['EDIT'] : ''; ?>
				</div>
				<div style="float:right">
					<?php echo isset($_tmpb_cat['PAGINATION']) ? $_tmpb_cat['PAGINATION'] : ''; ?>
				</div>
			</div>
			<div class="module_contents">
				<?php if( !isset($_tmpb_cat['download']) || !is_array($_tmpb_cat['download']) ) $_tmpb_cat['download'] = array();
foreach($_tmpb_cat['download'] as $download_key => $download_value) {
$_tmpb_download = &$_tmpb_cat['download'][$download_key]; ?>
				<div style="float:left;text-align:center;width:<?php echo isset($_tmpb_download['WIDTH']) ? $_tmpb_download['WIDTH'] : ''; ?>%;height:80px;">
					<?php echo isset($_tmpb_download['U_IMG_CAT']) ? $_tmpb_download['U_IMG_CAT'] : ''; ?>
					<a href="../download/download<?php echo isset($_tmpb_download['U_DOWNLOAD_CAT']) ? $_tmpb_download['U_DOWNLOAD_CAT'] : ''; ?>"><?php echo isset($_tmpb_download['CAT']) ? $_tmpb_download['CAT'] : ''; ?></a> (<?php echo isset($_tmpb_download['TOTAL']) ? $_tmpb_download['TOTAL'] : ''; ?>)<br />
					<span class="text_small"><?php echo isset($_tmpb_download['CONTENTS']) ? $_tmpb_download['CONTENTS'] : ''; ?></span>
					<br /><br /><br />
				</div>	
				<?php } ?>
				
				<div class="text_small" style="text-align:center;clear:both">
					<?php echo isset($_tmpb_cat['TOTAL_FILE']) ? $_tmpb_cat['TOTAL_FILE'] : ''; echo ' '; echo isset($_tmpb_cat['L_HOW_DOWNLOAD']) ? $_tmpb_cat['L_HOW_DOWNLOAD'] : ''; ?>
				</div>
				<div class="spacer">&nbsp;</div>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:right">
					<?php echo isset($_tmpb_cat['PAGINATION']) ? $_tmpb_cat['PAGINATION'] : ''; ?>
				</div>
			</div>
		</div>
		<?php } ?>
		


		<?php if( !isset($this->_block['link']) || !is_array($this->_block['link']) ) $this->_block['link'] = array();
foreach($this->_block['link'] as $link_key => $link_value) {
$_tmpb_link = &$this->_block['link'][$link_key]; ?>
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<?php echo isset($_tmpb_link['CAT_NAME']) ? $_tmpb_link['CAT_NAME'] : ''; ?>
				</div>
				<div style="float:right">
					<?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>
				</div>
			</div>
			<div class="module_contents">
				<table class="module_table">
					<tr>
						<th style="text-align:center;">
							<a href="download<?php echo isset($this->_var['U_DOWNLOAD_ALPHA_TOP']) ? $this->_var['U_DOWNLOAD_ALPHA_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
							<?php echo isset($this->_var['L_FILE']) ? $this->_var['L_FILE'] : ''; ?>
							<a href="download<?php echo isset($this->_var['U_DOWNLOAD_ALPHA_BOTTOM']) ? $this->_var['U_DOWNLOAD_ALPHA_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="download<?php echo isset($this->_var['U_DOWNLOAD_SIZE_TOP']) ? $this->_var['U_DOWNLOAD_SIZE_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
							<?php echo isset($this->_var['L_SIZE']) ? $this->_var['L_SIZE'] : ''; ?>
							<a href="download<?php echo isset($this->_var['U_DOWNLOAD_SIZE_BOTTOM']) ? $this->_var['U_DOWNLOAD_SIZE_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="download<?php echo isset($this->_var['U_DOWNLOAD_DATE_TOP']) ? $this->_var['U_DOWNLOAD_DATE_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
							<?php echo isset($this->_var['L_DATE']) ? $this->_var['L_DATE'] : ''; ?>					
							<a href="download<?php echo isset($this->_var['U_DOWNLOAD_DATE_BOTTOM']) ? $this->_var['U_DOWNLOAD_DATE_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="download<?php echo isset($this->_var['U_DOWNLOAD_VIEW_TOP']) ? $this->_var['U_DOWNLOAD_VIEW_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
							<?php echo isset($this->_var['L_DOWNLOAD']) ? $this->_var['L_DOWNLOAD'] : ''; ?>					
							<a href="download<?php echo isset($this->_var['U_DOWNLOAD_VIEW_BOTTOM']) ? $this->_var['U_DOWNLOAD_VIEW_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="download<?php echo isset($this->_var['U_DOWNLOAD_NOTE_TOP']) ? $this->_var['U_DOWNLOAD_NOTE_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
							<?php echo isset($this->_var['L_NOTE']) ? $this->_var['L_NOTE'] : ''; ?>					
							<a href="download<?php echo isset($this->_var['U_DOWNLOAD_NOTE_BOTTOM']) ? $this->_var['U_DOWNLOAD_NOTE_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="download<?php echo isset($this->_var['U_DOWNLOAD_COM_TOP']) ? $this->_var['U_DOWNLOAD_COM_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
							<?php echo isset($this->_var['L_COM']) ? $this->_var['L_COM'] : ''; ?>
							<a href="download<?php echo isset($this->_var['U_DOWNLOAD_COM_BOTTOM']) ? $this->_var['U_DOWNLOAD_COM_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
						</th>
					</tr>
					<?php if( !isset($_tmpb_link['download']) || !is_array($_tmpb_link['download']) ) $_tmpb_link['download'] = array();
foreach($_tmpb_link['download'] as $download_key => $download_value) {
$_tmpb_download = &$_tmpb_link['download'][$download_key]; ?>
					<tr>	
						<td class="row2">
							&raquo; <a href="download<?php echo isset($_tmpb_download['U_DOWNLOAD_LINK']) ? $_tmpb_download['U_DOWNLOAD_LINK'] : ''; ?>"><?php echo isset($_tmpb_download['NAME']) ? $_tmpb_download['NAME'] : ''; ?></a>
						</td>
						<td class="row2" style="text-align: center;">
							<?php echo isset($_tmpb_download['SIZE']) ? $_tmpb_download['SIZE'] : ''; ?>
						</td>
						<td class="row2" style="text-align: center;">
							<?php echo isset($_tmpb_download['DATE']) ? $_tmpb_download['DATE'] : ''; ?>
						</td>
						<td class="row2" style="text-align: center;">
							<?php echo isset($_tmpb_download['COMPT']) ? $_tmpb_download['COMPT'] : ''; ?> 
						</td>
						<td class="row2" style="text-align: center;">
							<?php echo isset($_tmpb_download['NOTE']) ? $_tmpb_download['NOTE'] : ''; ?>
						</td>
						<td class="row2" style="text-align: center;">
							<?php echo isset($_tmpb_download['COM']) ? $_tmpb_download['COM'] : ''; ?> 
						</td>
					</tr>
					<?php } ?>
				</table>
				<p style="text-align:center;padding:6px;"><?php echo isset($_tmpb_link['NO_CAT']) ? $_tmpb_link['NO_CAT'] : ''; ?></p>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:left">
					<strong><?php echo isset($_tmpb_link['CAT_NAME']) ? $_tmpb_link['CAT_NAME'] : ''; ?></strong>
				</div>
				<div style="float:right">
					<?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>
				</div>
			</div>
		</div>
		<?php } ?>


		<?php if( !isset($this->_block['download']) || !is_array($this->_block['download']) ) $this->_block['download'] = array();
foreach($this->_block['download'] as $download_key => $download_value) {
$_tmpb_download = &$this->_block['download'][$download_key]; ?>
		
		<script type="text/javascript">
		<!--
		var note_max = <?php echo isset($_tmpb_download['NOTE_MAX']) ? $_tmpb_download['NOTE_MAX'] : ''; ?>;
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
			
			document.getElementById('download_loading').innerHTML = '<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/loading_mini.gif" alt="" class="valign_middle" />';
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
					document.getElementById('download_loading').innerHTML = '';
					if( xhr_object.responseText == '-1' )
						alert("<?php echo isset($this->_var['L_ALREADY_VOTED']) ? $this->_var['L_ALREADY_VOTED'] : ''; ?>");
					else
					{	
						eval(xhr_object.responseText);
						array_note[id_file] = get_note;
						select_stars(id_file, get_note);
						if( document.getElementById('download_note') )
							document.getElementById('download_note').innerHTML = '(' + get_nbrnote + ' ' + ((get_nbrnote > 1) ? '<?php echo isset($this->_var['L_VOTES']) ? $this->_var['L_VOTES'] : ''; ?>' : '<?php echo isset($this->_var['L_VOTE']) ? $this->_var['L_VOTE'] : ''; ?>') + ')';
					}				
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
					document.getElementById('download_loading').innerHTML = '';
			}

			xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr_object.send(data);
		}
		-->
		</script> 
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<?php echo isset($_tmpb_download['NAME']) ? $_tmpb_download['NAME'] : ''; echo ' '; echo isset($this->_var['EDIT']) ? $this->_var['EDIT'] : '';  echo isset($this->_var['DEL']) ? $this->_var['DEL'] : ''; ?>
				</div>
				<div style="float:right">
					<?php echo isset($_tmpb_download['COM']) ? $_tmpb_download['COM'] : ''; ?>
				</div>
			</div>
			<div class="module_contents">
				<p>					
					<strong><?php echo isset($_tmpb_download['L_DESC']) ? $_tmpb_download['L_DESC'] : ''; ?>:</strong> <?php echo isset($_tmpb_download['CONTENTS']) ? $_tmpb_download['CONTENTS'] : ''; ?>						
					<br /><br />						
					<strong><?php echo isset($_tmpb_download['L_CAT']) ? $_tmpb_download['L_CAT'] : ''; ?>:</strong> 
					<a href="../download/download<?php echo isset($_tmpb_download['U_DOWNLOAD_CAT']) ? $_tmpb_download['U_DOWNLOAD_CAT'] : ''; ?>" title="<?php echo isset($_tmpb_download['CAT']) ? $_tmpb_download['CAT'] : ''; ?>"><?php echo isset($_tmpb_download['CAT']) ? $_tmpb_download['CAT'] : ''; ?></a><br />						
					<strong><?php echo isset($_tmpb_download['L_DATE']) ? $_tmpb_download['L_DATE'] : ''; ?>:</strong> <?php echo isset($_tmpb_download['DATE']) ? $_tmpb_download['DATE'] : ''; ?><br />						
					<strong><?php echo isset($_tmpb_download['L_SIZE']) ? $_tmpb_download['L_SIZE'] : ''; ?>:</strong> <?php echo isset($_tmpb_download['SIZE']) ? $_tmpb_download['SIZE'] : ''; ?><br />						
					<strong><?php echo isset($_tmpb_download['L_DOWNLOAD']) ? $_tmpb_download['L_DOWNLOAD'] : ''; ?>:</strong> <?php echo isset($_tmpb_download['COMPT']) ? $_tmpb_download['COMPT'] : ''; echo ' '; echo isset($_tmpb_download['L_TIMES']) ? $_tmpb_download['L_TIMES'] : ''; ?>
					<div class="spacer">&nbsp;</div>
				</p>
				<p style="text-align: center;">					
					<a href="../download/count.php?id=<?php echo isset($_tmpb_download['IDURL']) ? $_tmpb_download['IDURL'] : ''; ?>"><img src="<?php echo isset($_tmpb_download['MODULE_DATA_PATH']) ? $_tmpb_download['MODULE_DATA_PATH'] : ''; ?>/images/<?php echo isset($_tmpb_download['LANG']) ? $_tmpb_download['LANG'] : ''; ?>/bouton_dl.gif" alt="" /></a>
				</p>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<?php echo isset($_tmpb_download['NOTE']) ? $_tmpb_download['NOTE'] : ''; ?> <span id="download_loading"></span>
			</div>
		</div>
		
		<br /><br />
		<?php $this->tpl_include('handle_com'); ?>
		
		<?php } ?>


		<?php if( !isset($this->_block['note']) || !is_array($this->_block['note']) ) $this->_block['note'] = array();
foreach($this->_block['note'] as $note_key => $note_value) {
$_tmpb_note = &$this->_block['note'][$note_key]; ?>
		<form action="../download/download<?php echo isset($_tmpb_note['U_DOWNLOAD_ACTION_NOTE']) ? $_tmpb_note['U_DOWNLOAD_ACTION_NOTE'] : ''; ?>" method="post" class="fieldset_content">
			<span id="note"></span>
			<fieldset>
				<legend><?php echo isset($this->_var['L_NOTE']) ? $this->_var['L_NOTE'] : ''; ?></legend>
				<dl>
					<dt><label for="note_select"><?php echo isset($this->_var['L_NOTE']) ? $this->_var['L_NOTE'] : ''; ?></label></dt>
					<dd>
						<span class="text_small"><?php echo isset($this->_var['L_ACTUAL_NOTE']) ? $this->_var['L_ACTUAL_NOTE'] : ''; ?>: <?php echo isset($_tmpb_note['NOTE']) ? $_tmpb_note['NOTE'] : ''; ?></span>	
						<label>
							<select id="note_select" name="note">
								<?php echo isset($_tmpb_note['SELECT']) ? $_tmpb_note['SELECT'] : ''; ?>
							</select>
						</label>
					</dd>					
				</dl>
			</fieldset>
			<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_VOTE']) ? $this->_var['L_VOTE'] : ''; ?></legend>
				<input type="submit" name="valid_note" value="<?php echo isset($this->_var['L_VOTE']) ? $this->_var['L_VOTE'] : ''; ?>" class="submit" />
			</fieldset>
		</form>
		<?php } ?>
