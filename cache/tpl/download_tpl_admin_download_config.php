		<script type="text/javascript">
		<!--
		function check_form_conf()
		{
			if(document.getElementById('nbr_download_max').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?>");
				return false;
			}
			if(document.getElementById('nbr_cat_max').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?>");
				return false;
			}
			if(document.getElementById('nbr_column').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?>");
				return false;
			}
			if(document.getElementById('note_max').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?>");
				return false;
			}
			return true;
		}
		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_DOWNLOAD_MANAGEMENT']) ? $this->_var['L_DOWNLOAD_MANAGEMENT'] : ''; ?></li>
				<li>
					<a href="admin_download.php"><img src="download.png" alt="" /></a>
					<br />
					<a href="admin_download.php" class="quick_link"><?php echo isset($this->_var['L_DOWNLOAD_MANAGEMENT']) ? $this->_var['L_DOWNLOAD_MANAGEMENT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_download_add.php"><img src="download.png" alt="" /></a>
					<br />
					<a href="admin_download_add.php" class="quick_link"><?php echo isset($this->_var['L_DOWNLOAD_ADD']) ? $this->_var['L_DOWNLOAD_ADD'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_download_cat.php"><img src="download.png" alt="" /></a>
					<br />
					<a href="admin_download_cat.php" class="quick_link"><?php echo isset($this->_var['L_DOWNLOAD_CAT']) ? $this->_var['L_DOWNLOAD_CAT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_download_config.php"><img src="download.png" alt="" /></a>
					<br />
					<a href="admin_download_config.php" class="quick_link"><?php echo isset($this->_var['L_DOWNLOAD_CONFIG']) ? $this->_var['L_DOWNLOAD_CONFIG'] : ''; ?></a>
				</li>
			</ul>
		</div>
		
		<div id="admin_contents">							
			<form action="admin_download_config.php" method="post" onsubmit="return check_form_conf();" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_DOWNLOAD_CONFIG']) ? $this->_var['L_DOWNLOAD_CONFIG'] : ''; ?></legend>
					<dl>
						<dt><label for="nbr_file_max">* <?php echo isset($this->_var['L_NBR_FILE_MAX']) ? $this->_var['L_NBR_FILE_MAX'] : ''; ?></label></dt>
						<dd><label><input type="text" size="3" maxlength="3" id="nbr_file_max" name="nbr_file_max" value="<?php echo isset($this->_var['NBR_FILE_MAX']) ? $this->_var['NBR_FILE_MAX'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="nbr_cat_max">* <?php echo isset($this->_var['L_NBR_CAT_MAX']) ? $this->_var['L_NBR_CAT_MAX'] : ''; ?></label></dt>
						<dd><label><input type="text" size="3" maxlength="3" id="nbr_cat_max" name="nbr_cat_max" value="<?php echo isset($this->_var['NBR_CAT_MAX']) ? $this->_var['NBR_CAT_MAX'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="nbr_column">* <?php echo isset($this->_var['L_NBR_COLUMN_MAX']) ? $this->_var['L_NBR_COLUMN_MAX'] : ''; ?></label></dt>
						<dd><label><input type="text" size="3" maxlength="3" id="nbr_column" name="nbr_column" value="<?php echo isset($this->_var['NBR_COLUMN']) ? $this->_var['NBR_COLUMN'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="note_max">* <?php echo isset($this->_var['L_NOTE_MAX']) ? $this->_var['L_NOTE_MAX'] : ''; ?></label></dt>
						<dd><label><input type="text" size="2" maxlength="2" id="note_max" name="note_max" value="<?php echo isset($this->_var['NOTE_MAX']) ? $this->_var['NOTE_MAX'] : ''; ?>" class="text" /></label></dd>
					</dl>
				</fieldset>			
				
				<fieldset class="fieldset_submit">
					<legend><?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?></legend>
					<input type="submit" name="valid" value="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" class="submit" />
					&nbsp;&nbsp; 
					<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />				
				</fieldset>	
			</form>
		</div>	
		