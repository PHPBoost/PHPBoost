		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title">{L_SWITCH_THEME}</h5>
			</div>
			<form action="" method="get">
				<div class="module_mini_contents">
					<p style="padding-top: 5px;">
						<select name="switchtheme" onchange="document.location = '?token={TOKEN}&amp;switchtheme=' + this.options[this.selectedIndex].value;">
							# START themes #
								<option value="{themes.IDNAME}"{themes.SELECTED}>{themes.NAME}</option>
							# END themes #
						</select>
						<input style="display: none;" value="{L_SUBMIT}" name="valid" id="switchtheme_valid" class="submit" type="submit" />
						<script type="text/javascript">
						<!--
						document.getElementById('switchtheme_valid').style.display = 'none';
						-->
						</script>
					</p>
					<a href="?token={TOKEN}&amp;switchtheme={DEFAULT_THEME}">{L_DEFAULT_THEME}</a>
				</div>
			</form>
			<div class="module_mini_bottom">
			</div>
		</div>