		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_SWITCH_LANG}</h5>
			</div>
			<form action="" method="get">
				<div class="module_mini_contents">
					<p style="padding-top: 5px;">
						<select name="switchlang" onchange="document.location = '?token={TOKEN}&amp;switchlang=' + this.options[this.selectedIndex].value;">
							# START langs #
								<option value="{langs.IDNAME}"{langs.SELECTED}>{langs.NAME}</option>
							# END langs #
						</select>
						<img src="{IMG_LANG_IDENTIFIER}" alt="" class="valign_middle" />
						
						<input style="display: none;" value="{L_SUBMIT}" name="valid" id="switchlang_valid" class="submit" type="submit" />
						<script type="text/javascript">
						<!--
						document.getElementById('switchlang_valid').style.display = 'none';
						-->
						</script>
					</p>
					<p style="text-align: center;"><a href="?token={TOKEN}&amp;switchlang={DEFAULT_LANG}">{L_DEFAULT_LANG}</a></p>&nbsp;
				</div>
			</form>
			<div class="module_mini_bottom">
			</div>
		</div>