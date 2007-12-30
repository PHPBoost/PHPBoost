		<table class="module_table">
			<tr>
				<th id="stats" colspan="5">
					<?php echo isset($this->_var['L_STATS']) ? $this->_var['L_STATS'] : ''; ?>
				</th>
			</tr>
			<tr style="text-align:center;">
				<td style="width:20%;padding:15px;" class="row2">
					<a href="stats<?php echo isset($this->_var['U_STATS_SITE']) ? $this->_var['U_STATS_SITE'] : ''; ?>#stats"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/stats/site.png" alt="" /></a>
					<br /><a href="stats<?php echo isset($this->_var['U_STATS_SITE']) ? $this->_var['U_STATS_SITE'] : ''; ?>#stats"><?php echo isset($this->_var['L_SITE']) ? $this->_var['L_SITE'] : ''; ?></a>
				</td>
				<td style="width:20%;padding:15px;" class="row2">
					<a href="stats<?php echo isset($this->_var['U_STATS_MEMBERS']) ? $this->_var['U_STATS_MEMBERS'] : ''; ?>#stats"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/member.png" alt="" /></a>
					<br /><a href="stats<?php echo isset($this->_var['U_STATS_MEMBERS']) ? $this->_var['U_STATS_MEMBERS'] : ''; ?>#stats"><?php echo isset($this->_var['L_MEMBERS']) ? $this->_var['L_MEMBERS'] : ''; ?></a>
				</td>
				<td style="width:20%;" class="row2">
					<a href="stats<?php echo isset($this->_var['U_STATS_VISIT']) ? $this->_var['U_STATS_VISIT'] : ''; ?>#stats"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/stats/visitors.png" alt="" /></a>
					<br /><a href="stats<?php echo isset($this->_var['U_STATS_VISIT']) ? $this->_var['U_STATS_VISIT'] : ''; ?>#stats"><?php echo isset($this->_var['L_VISITS']) ? $this->_var['L_VISITS'] : ''; ?></a>
				</td>
				<td style="width:20%;padding:15px;" class="row2">
					<a href="stats<?php echo isset($this->_var['U_STATS_PAGES']) ? $this->_var['U_STATS_PAGES'] : ''; ?>#stats"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/stats/pages.png" alt="" /></a>
					<br /><a href="stats<?php echo isset($this->_var['U_STATS_PAGES']) ? $this->_var['U_STATS_PAGES'] : ''; ?>#stats"><?php echo isset($this->_var['L_PAGES']) ? $this->_var['L_PAGES'] : ''; ?></a>
				</td>
				<td style="width:20%;padding:15px;" class="row2">
					<a href="stats<?php echo isset($this->_var['U_STATS_BROWSER']) ? $this->_var['U_STATS_BROWSER'] : ''; ?>#stats"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/stats/browsers.png" alt="" /></a>
					<br /><a href="stats<?php echo isset($this->_var['U_STATS_BROWSER']) ? $this->_var['U_STATS_BROWSER'] : ''; ?>#stats"><?php echo isset($this->_var['L_BROWSERS']) ? $this->_var['L_BROWSERS'] : ''; ?></a>
				</td>
			</tr>
			<tr style="text-align:center;">				
				<td style="width:20%;" class="row2">	
					<a href="stats<?php echo isset($this->_var['U_STATS_OS']) ? $this->_var['U_STATS_OS'] : ''; ?>#stats"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/stats/os.png" alt="" /></a>
					<br /><a href="stats<?php echo isset($this->_var['U_STATS_OS']) ? $this->_var['U_STATS_OS'] : ''; ?>#stats"><?php echo isset($this->_var['L_OS']) ? $this->_var['L_OS'] : ''; ?></a>		
				</td>		
				<td style="width:20%;padding:15px;" class="row2">
					<a href="stats<?php echo isset($this->_var['U_STATS_LANG']) ? $this->_var['U_STATS_LANG'] : ''; ?>#stats"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/stats/countries.png" alt="" /></a>
					<br /><a href="stats<?php echo isset($this->_var['U_STATS_LANG']) ? $this->_var['U_STATS_LANG'] : ''; ?>#stats"><?php echo isset($this->_var['L_LANG']) ? $this->_var['L_LANG'] : ''; ?></a>
				</td>
				<td style="width:20%;padding:15px;" class="row2">
					<a href="stats<?php echo isset($this->_var['U_STATS_REFERER']) ? $this->_var['U_STATS_REFERER'] : ''; ?>#stats"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/stats/referer.png" alt="" /></a>
					<br /><a href="stats<?php echo isset($this->_var['U_STATS_REFERER']) ? $this->_var['U_STATS_REFERER'] : ''; ?>#stats"><?php echo isset($this->_var['L_REFERER']) ? $this->_var['L_REFERER'] : ''; ?></a>
				</td>
				<td style="width:20%;padding:15px;" class="row2">
					<a href="stats<?php echo isset($this->_var['U_STATS_KEYWORD']) ? $this->_var['U_STATS_KEYWORD'] : ''; ?>#stats"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/stats/keyword.png" alt="" /></a>
					<br /><a href="stats<?php echo isset($this->_var['U_STATS_KEYWORD']) ? $this->_var['U_STATS_KEYWORD'] : ''; ?>#stats"><?php echo isset($this->_var['L_KEYWORD']) ? $this->_var['L_KEYWORD'] : ''; ?></a>
				</td>
				<td style="width:20%;padding:15px;" class="row2">
					&nbsp;
				</td>
			</tr>
		</table>
		
		<br /><br />
		

		<?php if( !isset($this->_block['visit']) || !is_array($this->_block['visit']) ) $this->_block['visit'] = array();
foreach($this->_block['visit'] as $visit_key => $visit_value) {
$_tmpb_visit = &$this->_block['visit'][$visit_key]; ?>
		<form action="stats.php#stats" method="get">
			<table class="module_table">
				<tr>
					<th>
						<?php echo isset($this->_var['L_VISITORS']) ? $this->_var['L_VISITORS'] : ''; echo ' '; echo isset($_tmpb_visit['MONTH']) ? $_tmpb_visit['MONTH'] : ''; echo ' '; echo isset($_tmpb_visit['U_YEAR']) ? $_tmpb_visit['U_YEAR'] : ''; ?>
					</th>
				</tr>
				<tr>
					<td class="row2" style="text-align:center;">
						<div style="width:50%;text-align:center;margin:auto">
							<p class="text_strong"><?php echo isset($this->_var['L_TOTAL']) ? $this->_var['L_TOTAL'] : ''; ?>: <?php echo isset($_tmpb_visit['VISIT_TOTAL']) ? $_tmpb_visit['VISIT_TOTAL'] : ''; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo isset($this->_var['L_TODAY']) ? $this->_var['L_TODAY'] : ''; ?>: <?php echo isset($_tmpb_visit['VISIT_DAY']) ? $_tmpb_visit['VISIT_DAY'] : ''; ?></p>
							<a href="stats<?php echo isset($_tmpb_visit['U_PREVIOUS_LINK']) ? $_tmpb_visit['U_PREVIOUS_LINK'] : ''; ?>#stats">&laquo;</a>&nbsp;&nbsp;&nbsp;&nbsp;
							<?php if( !isset($_tmpb_visit['days']) || !is_array($_tmpb_visit['days']) ) $_tmpb_visit['days'] = array();
foreach($_tmpb_visit['days'] as $days_key => $days_value) {
$_tmpb_days = &$_tmpb_visit['days'][$days_key]; ?>
							<select name="d">
								<?php echo isset($_tmpb_days['DAY']) ? $_tmpb_days['DAY'] : ''; ?>
							</select>
							<?php } ?>
							<?php if( !isset($_tmpb_visit['months']) || !is_array($_tmpb_visit['months']) ) $_tmpb_visit['months'] = array();
foreach($_tmpb_visit['months'] as $months_key => $months_value) {
$_tmpb_months = &$_tmpb_visit['months'][$months_key]; ?>
							<select name="m">
								<?php echo isset($_tmpb_months['MONTH']) ? $_tmpb_months['MONTH'] : ''; ?>
							</select>
							<?php } ?>
							<?php if( !isset($_tmpb_visit['years']) || !is_array($_tmpb_visit['years']) ) $_tmpb_visit['years'] = array();
foreach($_tmpb_visit['years'] as $years_key => $years_value) {
$_tmpb_years = &$_tmpb_visit['years'][$years_key]; ?>
							<select name="y">
								<?php echo isset($_tmpb_years['YEAR']) ? $_tmpb_years['YEAR'] : ''; ?>
							</select>
							 <?php } ?>
							<input type="hidden" name="<?php echo isset($_tmpb_visit['TYPE']) ? $_tmpb_visit['TYPE'] : ''; ?>" value="1" />
							<input type="submit" name="date" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
							&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="stats<?php echo isset($_tmpb_visit['U_NEXT_LINK']) ? $_tmpb_visit['U_NEXT_LINK'] : ''; ?>#stats">&raquo;</a>				
						</div>
						<br />
						<?php if( !isset($_tmpb_visit['no_gd']) || !is_array($_tmpb_visit['no_gd']) ) $_tmpb_visit['no_gd'] = array();
foreach($_tmpb_visit['no_gd'] as $no_gd_key => $no_gd_value) {
$_tmpb_no_gd = &$_tmpb_visit['no_gd'][$no_gd_key]; ?>
						<br />
						<table class="module_table" style="width:400px;margin:auto;">
							<tr>
								<td style="background-color: #000000;width:1px;"></td>
								<td style="height:200px;width:10px;vertical-align:top;text-align:center;font-size:9px;">
									<?php echo isset($_tmpb_visit['MAX_NBR']) ? $_tmpb_visit['MAX_NBR'] : ''; ?>
								</td>
									
								<?php if( !isset($_tmpb_no_gd['values']) || !is_array($_tmpb_no_gd['values']) ) $_tmpb_no_gd['values'] = array();
foreach($_tmpb_no_gd['values'] as $values_key => $values_value) {
$_tmpb_values = &$_tmpb_no_gd['values'][$values_key]; ?>								
								<td style="height:200px;width:10px;vertical-align:bottom;">
									<table class="module_table" style="width:14px;margin:auto;">
										<?php if( !isset($_tmpb_values['head']) || !is_array($_tmpb_values['head']) ) $_tmpb_values['head'] = array();
foreach($_tmpb_values['head'] as $head_key => $head_value) {
$_tmpb_head = &$_tmpb_values['head'][$head_key]; ?>
										<tr>
											<td style="margin-left:2px;width:10px;height:4px;background-image: url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/stats2.png); background-repeat:no-repeat;">
											</td>
										</tr>
										<?php } ?>
										<tr>
											<td style="margin-left:2px;width:10px;height:<?php echo isset($_tmpb_values['HEIGHT']) ? $_tmpb_values['HEIGHT'] : ''; ?>px;background-image: url(../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/stats.png);background-repeat:repeat-y;padding:0px">
											</td>
										</tr>
									</table>
								</td>	
								<?php } ?>
								
								<?php if( !isset($_tmpb_no_gd['end_td']) || !is_array($_tmpb_no_gd['end_td']) ) $_tmpb_no_gd['end_td'] = array();
foreach($_tmpb_no_gd['end_td'] as $end_td_key => $end_td_value) {
$_tmpb_end_td = &$_tmpb_no_gd['end_td'][$end_td_key]; ?>							
									<?php echo isset($_tmpb_end_td['END_TD']) ? $_tmpb_end_td['END_TD'] : ''; ?>							
								<?php } ?>
							</tr>
							<tr>
								<td style="background-color: #000000;width:1px"></td>
								<td style="width:10px;font-size:9px;">
									0
								</td>								
								<?php if( !isset($_tmpb_no_gd['legend']) || !is_array($_tmpb_no_gd['legend']) ) $_tmpb_no_gd['legend'] = array();
foreach($_tmpb_no_gd['legend'] as $legend_key => $legend_value) {
$_tmpb_legend = &$_tmpb_no_gd['legend'][$legend_key]; ?>								
								<td style="text-align:center;width:13px;font-size:9px;">
									<?php echo isset($_tmpb_legend['LEGEND']) ? $_tmpb_legend['LEGEND'] : ''; ?>
								</td>								
								<?php } ?>								
							</tr>
							<tr>
								<td style="height:1px;background-color: #000000;" colspan="<?php echo isset($_tmpb_visit['COLSPAN']) ? $_tmpb_visit['COLSPAN'] : ''; ?>"></td>
							</tr>
						</table>
						<br />
						<?php } ?>
						
						<?php echo isset($this->_var['GRAPH_RESULT']) ? $this->_var['GRAPH_RESULT'] : ''; ?>
					</td>
				</tr>
				<tr>
					<td class="row2" style="text-align:center;" colspan="<?php echo isset($_tmpb_visit['COLSPAN']) ? $_tmpb_visit['COLSPAN'] : ''; ?>">
						<?php echo isset($this->_var['L_TOTAL']) ? $this->_var['L_TOTAL'] : ''; ?>: <?php echo isset($_tmpb_visit['SUM_NBR']) ? $_tmpb_visit['SUM_NBR'] : ''; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo isset($this->_var['L_AVERAGE']) ? $this->_var['L_AVERAGE'] : ''; ?>: <?php echo isset($_tmpb_visit['MOY_NBR']) ? $_tmpb_visit['MOY_NBR'] : ''; ?>
					</td>
				</tr>
				<tr>
					<td class="row2" style="text-align:center;">
						<?php echo isset($_tmpb_visit['U_VISITS_MORE']) ? $_tmpb_visit['U_VISITS_MORE'] : ''; ?>
					</td>
				</tr>
			</table>
		</form>	
		<br /><br />
		<table class="module_table" style="width:300px;">
			<tr>
				<th style="width:50%">
					<?php echo isset($this->_var['L_DAY']) ? $this->_var['L_DAY'] : ''; ?>
				</th>
				<th style="width:50%">
					<?php echo isset($this->_var['L_VISITS_DAY']) ? $this->_var['L_VISITS_DAY'] : ''; ?>
				</th>
			</tr>			
			<?php if( !isset($_tmpb_visit['value']) || !is_array($_tmpb_visit['value']) ) $_tmpb_visit['value'] = array();
foreach($_tmpb_visit['value'] as $value_key => $value_value) {
$_tmpb_value = &$_tmpb_visit['value'][$value_key]; ?>
			<tr>
				<td class="row3" style="font-size:10px;width:50%">
					<?php echo isset($_tmpb_value['U_DETAILS']) ? $_tmpb_value['U_DETAILS'] : ''; ?>
				</td>
				<td class="row3" style="font-size:10px;width:50%">
					<?php echo isset($_tmpb_value['NBR']) ? $_tmpb_value['NBR'] : ''; ?>
				</td>
			</tr>		
			<?php } ?>
		</table>
		<?php } ?>


		<?php if( !isset($this->_block['browsers']) || !is_array($this->_block['browsers']) ) $this->_block['browsers'] = array();
foreach($this->_block['browsers'] as $browsers_key => $browsers_value) {
$_tmpb_browsers = &$this->_block['browsers'][$browsers_key]; ?>
		<table class="module_table">
			<tr>
				<th colspan="3">
					<?php echo isset($this->_var['L_BROWSERS']) ? $this->_var['L_BROWSERS'] : ''; ?>
				</th>
			</tr>
			<tr>
				<td class="row2" style="width:50%;padding-top:30px;vertical-align:top">
					<table class="module_table">
						<?php if( !isset($_tmpb_browsers['browsers_list']) || !is_array($_tmpb_browsers['browsers_list']) ) $_tmpb_browsers['browsers_list'] = array();
foreach($_tmpb_browsers['browsers_list'] as $browsers_list_key => $browsers_list_value) {
$_tmpb_browsers_list = &$_tmpb_browsers['browsers_list'][$browsers_list_key]; ?>			
						<tr style="height:35px;">
							<td style="width:35px;text-align:center;" class="row2">			
								<?php echo isset($_tmpb_browsers_list['IMG']) ? $_tmpb_browsers_list['IMG'] : ''; ?>
							</td>
							<td style="width:10px;text-align:center;" class="row2">			
								<div style="width:10px;height:10px;margin:auto;background:<?php echo isset($_tmpb_browsers_list['COLOR']) ? $_tmpb_browsers_list['COLOR'] : ''; ?>;border:1px solid black;"></div>
							</td>
							<td style="width:50px;" class="row2">
								 <?php echo isset($_tmpb_browsers_list['L_NAME']) ? $_tmpb_browsers_list['L_NAME'] : ''; ?> <span class="text_small">(<?php echo isset($_tmpb_browsers_list['PERCENT']) ? $_tmpb_browsers_list['PERCENT'] : ''; ?>%)</span>
							</td>				
						</tr>
						<?php } ?>
					</table>
				</td>
				<td class="row2" style="text-align:center;padding-top:30px;vertical-align:top">
					<?php echo isset($_tmpb_browsers['GRAPH_RESULT']) ? $_tmpb_browsers['GRAPH_RESULT'] : ''; ?>
				</td>
			</tr>
		</table>
		<?php } ?>


		<?php if( !isset($this->_block['os']) || !is_array($this->_block['os']) ) $this->_block['os'] = array();
foreach($this->_block['os'] as $os_key => $os_value) {
$_tmpb_os = &$this->_block['os'][$os_key]; ?>
		<table class="module_table">
			<tr>
				<th colspan="3">
					<?php echo isset($this->_var['L_OS']) ? $this->_var['L_OS'] : ''; ?>
				</th>
			</tr>
			<tr style="height:35px;">
				<td class="row2" style="width:50%;padding-top:30px;vertical-align:top">
					<table class="module_table">
						<?php if( !isset($_tmpb_os['os_list']) || !is_array($_tmpb_os['os_list']) ) $_tmpb_os['os_list'] = array();
foreach($_tmpb_os['os_list'] as $os_list_key => $os_list_value) {
$_tmpb_os_list = &$_tmpb_os['os_list'][$os_list_key]; ?>			
						<tr style="height:35px;">
							<td style="width:35px; text-align:center;" class="row2">			
								<?php echo isset($_tmpb_os_list['IMG']) ? $_tmpb_os_list['IMG'] : ''; ?>
							</td>
							<td style="width:10px; text-align:center;" class="row2">			
								<div style="width:10px;height:10px;margin:auto;background:<?php echo isset($_tmpb_os_list['COLOR']) ? $_tmpb_os_list['COLOR'] : ''; ?>;border:1px solid black;"></div>
							</td>
							<td style="width:50px;" class="row2">
								<?php echo isset($_tmpb_os_list['L_NAME']) ? $_tmpb_os_list['L_NAME'] : ''; ?> <span class="text_small">(<?php echo isset($_tmpb_os_list['PERCENT']) ? $_tmpb_os_list['PERCENT'] : ''; ?>%)</span>
							</td>				
						</tr>
						<?php } ?>
					</table>
				</td>
				<td class="row2" style="text-align:center;padding-top:30px;vertical-align:top">
					<?php echo isset($_tmpb_os['GRAPH_RESULT']) ? $_tmpb_os['GRAPH_RESULT'] : ''; ?>
				</td>
			</tr>
		</table>
		<?php } ?>

				
		<?php if( !isset($this->_block['lang']) || !is_array($this->_block['lang']) ) $this->_block['lang'] = array();
foreach($this->_block['lang'] as $lang_key => $lang_value) {
$_tmpb_lang = &$this->_block['lang'][$lang_key]; ?>
		<table class="module_table">
			<tr>
				<th colspan="3">
					<?php echo isset($this->_var['L_LANG']) ? $this->_var['L_LANG'] : ''; ?>
				</th>
			</tr>
			<tr style="height:35px;">
				<td class="row2" style="width:50%;padding-top:30px;vertical-align:top">
					<table class="module_table">
						<?php if( !isset($_tmpb_lang['lang_list']) || !is_array($_tmpb_lang['lang_list']) ) $_tmpb_lang['lang_list'] = array();
foreach($_tmpb_lang['lang_list'] as $lang_list_key => $lang_list_value) {
$_tmpb_lang_list = &$_tmpb_lang['lang_list'][$lang_list_key]; ?>			
						<tr style="height:35px;">
							<td style="width:35px;text-align:center;" class="row2">			
								<?php echo isset($_tmpb_lang_list['IMG']) ? $_tmpb_lang_list['IMG'] : ''; ?>
							</td>
							<td style="width:10px;text-align:center;" class="row2">			
								<div style="width:10px;margin:auto;height:10px;background:<?php echo isset($_tmpb_lang_list['COLOR']) ? $_tmpb_lang_list['COLOR'] : ''; ?>;border:1px solid black;"></div>
							</td>
							<td style="width:50px;" class="row2">
								<?php echo isset($_tmpb_lang_list['L_NAME']) ? $_tmpb_lang_list['L_NAME'] : ''; ?> <span class="text_small">(<?php echo isset($_tmpb_lang_list['PERCENT']) ? $_tmpb_lang_list['PERCENT'] : ''; ?>%)</span>
							</td>				
						</tr>
						<?php } ?>
					</table>
				</td>
				<td class="row2" style="text-align:center;padding-top:30px;vertical-align:top">
					<?php echo isset($_tmpb_lang['GRAPH_RESULT']) ? $_tmpb_lang['GRAPH_RESULT'] : ''; ?>
				</td>
			</tr>
			<tr>
				<td class="row2" colspan="3" style="text-align:center;">
					<?php echo isset($this->_var['L_LANG_ALL']) ? $this->_var['L_LANG_ALL'] : ''; ?>
				</td>
			</tr>
		</table>
		<?php } ?>

		
		<?php if( !isset($this->_block['site']) || !is_array($this->_block['site']) ) $this->_block['site'] = array();
foreach($this->_block['site'] as $site_key => $site_value) {
$_tmpb_site = &$this->_block['site'][$site_key]; ?>
		<table class="module_table">
			<tr>
				<th colspan="3">
					<?php echo isset($this->_var['L_SITE']) ? $this->_var['L_SITE'] : ''; ?>
				</th>
			</tr>
			<tr>
				<td class="row2">
					<?php echo isset($this->_var['L_START']) ? $this->_var['L_START'] : ''; ?>: <strong><?php echo isset($_tmpb_site['START']) ? $_tmpb_site['START'] : ''; ?></strong>
				</td>		
			</tr>
			<tr>
				<td class="row2">
					<?php echo isset($this->_var['L_VERSION']) ? $this->_var['L_VERSION'] : ''; ?> PHPBoost: <strong><?php echo isset($_tmpb_site['VERSION']) ? $_tmpb_site['VERSION'] : ''; ?></strong>
				</td>		
			</tr>	
		</table>
		<?php } ?>
		
		
		<?php if( !isset($this->_block['referer']) || !is_array($this->_block['referer']) ) $this->_block['referer'] = array();
foreach($this->_block['referer'] as $referer_key => $referer_value) {
$_tmpb_referer = &$this->_block['referer'][$referer_key]; ?>
		<script type="text/javascript">
		<!--
		function XMLHttpRequest_referer(divid)
		{
			if( document.getElementById('url' + divid).style.display == 'table' )
			{
				display_div_auto('url' + divid, 'table');
				document.getElementById('img_url' + divid).src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/plus.png';
			}
			else
			{
				var xhr_object = null;
				var filename = '../includes/xmlhttprequest.php?stats_referer=1&id=' + divid;
				var data = null;
				
				if(window.XMLHttpRequest) // Firefox
				   xhr_object = new XMLHttpRequest();
				else if(window.ActiveXObject) // Internet Explorer
				   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
				else // XMLHttpRequest non supporté par le navigateur
					return;
				
				document.getElementById('load' + divid).innerHTML = '<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/loading_mini.gif" alt="" class="valign_middle" />';
				
				xhr_object.open("POST", filename, true);
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
					{	
						display_div_auto('url' + divid, 'table');
						document.getElementById('url' + divid).innerHTML = xhr_object.responseText;
						document.getElementById('load' + divid).innerHTML = '';
						document.getElementById('img_url' + divid).src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/minus.png';
					}
					else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
						document.getElementById('load' + divid).innerHTML = '';
				}
				xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr_object.send(null);
			}
		}
		-->
		</script>
		
		<table class="module_table">
			<tr>
				<th>			
					<?php echo isset($this->_var['L_REFERER']) ? $this->_var['L_REFERER'] : ''; ?>
				</th>
				<th style="width:100px;">
					<?php echo isset($this->_var['L_TOTAL_VISIT']) ? $this->_var['L_TOTAL_VISIT'] : ''; ?>
				</th>
				<th style="width:100px;">
					<?php echo isset($this->_var['L_AVERAGE_VISIT']) ? $this->_var['L_AVERAGE_VISIT'] : ''; ?>
				</th>
				<th style="width:90px;">
					<?php echo isset($this->_var['L_LAST_UPDATE']) ? $this->_var['L_LAST_UPDATE'] : ''; ?>
				</th>	
				<th style="width:93px;">
					<?php echo isset($this->_var['L_TREND']) ? $this->_var['L_TREND'] : ''; ?>
				</th>
			</tr>
			<?php if( !isset($_tmpb_referer['referer_list']) || !is_array($_tmpb_referer['referer_list']) ) $_tmpb_referer['referer_list'] = array();
foreach($_tmpb_referer['referer_list'] as $referer_list_key => $referer_list_value) {
$_tmpb_referer_list = &$_tmpb_referer['referer_list'][$referer_list_key]; ?>	
			<tr>
				<td class="row2" style="padding:0px;margin:0px;" colspan="5">			
					<table style="width:100%;border:none;border-collapse:collapse;">
						<tr>
							<td style="text-align:center;">		
								<?php echo isset($_tmpb_referer_list['IMG_MORE']) ? $_tmpb_referer_list['IMG_MORE'] : ''; ?> <span class="text_small">(<?php echo isset($_tmpb_referer_list['NBR_LINKS']) ? $_tmpb_referer_list['NBR_LINKS'] : ''; ?>)</span> <a href="<?php echo isset($_tmpb_referer_list['URL']) ? $_tmpb_referer_list['URL'] : ''; ?>"><?php echo isset($_tmpb_referer_list['URL']) ? $_tmpb_referer_list['URL'] : ''; ?></a>	<span id="load<?php echo isset($_tmpb_referer_list['ID']) ? $_tmpb_referer_list['ID'] : ''; ?>"></span>	 			
							</td>
							<td style="width:112px;text-align:center;">
								<?php echo isset($_tmpb_referer_list['TOTAL_VISIT']) ? $_tmpb_referer_list['TOTAL_VISIT'] : ''; ?>
							</td>
							<td style="width:112px;text-align:center;">
								<?php echo isset($_tmpb_referer_list['AVERAGE_VISIT']) ? $_tmpb_referer_list['AVERAGE_VISIT'] : ''; ?>
							</td>
							<td style="width:102px;text-align:center;">
								<?php echo isset($_tmpb_referer_list['LAST_UPDATE']) ? $_tmpb_referer_list['LAST_UPDATE'] : ''; ?>
							</td>
							<td style="width:105px;">
								<?php echo isset($_tmpb_referer_list['TREND']) ? $_tmpb_referer_list['TREND'] : ''; ?>
							</td>
						</tr>
					</table>
					<div id="url<?php echo isset($_tmpb_referer_list['ID']) ? $_tmpb_referer_list['ID'] : ''; ?>" style="display:none;width:100%;"></div>					
				</td>	
			</tr>
			<?php } ?>
			<tr>
				<td class="row3" colspan="5" style="text-align:center;">
					<?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>&nbsp;
				</td>
			</tr>
		</table>
		<?php } ?>
		
		
		<?php if( !isset($this->_block['keyword']) || !is_array($this->_block['keyword']) ) $this->_block['keyword'] = array();
foreach($this->_block['keyword'] as $keyword_key => $keyword_value) {
$_tmpb_keyword = &$this->_block['keyword'][$keyword_key]; ?>
		<script type="text/javascript">
		<!--
		function XMLHttpRequest_referer(divid)
		{
			if( document.getElementById('url' + divid).style.display == 'table' )
			{
				display_div_auto('url' + divid, 'table');
				document.getElementById('img_url' + divid).src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/plus.png';
			}
			else
			{
				var xhr_object = null;
				var filename = '../includes/xmlhttprequest.php?stats_keyword=1&id=' + divid;
				var data = null;
				
				if(window.XMLHttpRequest) // Firefox
				   xhr_object = new XMLHttpRequest();
				else if(window.ActiveXObject) // Internet Explorer
				   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
				else // XMLHttpRequest non supporté par le navigateur
					return;
				
				document.getElementById('load' + divid).innerHTML = '<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/loading_mini.gif" alt="" class="valign_middle" />';
				
				xhr_object.open("POST", filename, true);
				xhr_object.onreadystatechange = function() 
				{
					if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
					{	
						display_div_auto('url' + divid, 'table');
						document.getElementById('url' + divid).innerHTML = xhr_object.responseText;
						document.getElementById('load' + divid).innerHTML = '';
						document.getElementById('img_url' + divid).src = '../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/upload/minus.png';
					}
					else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
						document.getElementById('load' + divid).innerHTML = '';
				}
				xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr_object.send(null);
			}
		}
		-->
		</script>
		
		<table class="module_table">
			<tr>
				<th>			
					<?php echo isset($this->_var['L_KEYWORD']) ? $this->_var['L_KEYWORD'] : ''; ?>
				</th>
				<th style="width:100px;">
					<?php echo isset($this->_var['L_TOTAL_VISIT']) ? $this->_var['L_TOTAL_VISIT'] : ''; ?>
				</th>
				<th style="width:100px;">
					<?php echo isset($this->_var['L_AVERAGE_VISIT']) ? $this->_var['L_AVERAGE_VISIT'] : ''; ?>
				</th>
				<th style="width:90px;">
					<?php echo isset($this->_var['L_LAST_UPDATE']) ? $this->_var['L_LAST_UPDATE'] : ''; ?>
				</th>	
				<th style="width:93px;">
					<?php echo isset($this->_var['L_TREND']) ? $this->_var['L_TREND'] : ''; ?>
				</th>
			</tr>
			<?php if( !isset($_tmpb_keyword['keyword_list']) || !is_array($_tmpb_keyword['keyword_list']) ) $_tmpb_keyword['keyword_list'] = array();
foreach($_tmpb_keyword['keyword_list'] as $keyword_list_key => $keyword_list_value) {
$_tmpb_keyword_list = &$_tmpb_keyword['keyword_list'][$keyword_list_key]; ?>	
			<tr>
				<td class="row2" style="padding:0px;margin:0px;" colspan="5">			
					<table style="width:100%;border:none;border-collapse:collapse;">
						<tr>
							<td style="text-align:center;">		
								<?php echo isset($_tmpb_keyword_list['IMG_MORE']) ? $_tmpb_keyword_list['IMG_MORE'] : ''; ?> <span class="text_small">(<?php echo isset($_tmpb_keyword_list['NBR_LINKS']) ? $_tmpb_keyword_list['NBR_LINKS'] : ''; ?>)</span> <?php echo isset($_tmpb_keyword_list['KEYWORD']) ? $_tmpb_keyword_list['KEYWORD'] : ''; ?>	<span id="load<?php echo isset($_tmpb_keyword_list['ID']) ? $_tmpb_keyword_list['ID'] : ''; ?>"></span>	 			
							</td>
							<td style="width:112px;text-align:center;">
								<?php echo isset($_tmpb_keyword_list['TOTAL_VISIT']) ? $_tmpb_keyword_list['TOTAL_VISIT'] : ''; ?>
							</td>
							<td style="width:112px;text-align:center;">
								<?php echo isset($_tmpb_keyword_list['AVERAGE_VISIT']) ? $_tmpb_keyword_list['AVERAGE_VISIT'] : ''; ?>
							</td>
							<td style="width:102px;text-align:center;">
								<?php echo isset($_tmpb_keyword_list['LAST_UPDATE']) ? $_tmpb_keyword_list['LAST_UPDATE'] : ''; ?>
							</td>
							<td style="width:105px;">
								<?php echo isset($_tmpb_keyword_list['TREND']) ? $_tmpb_keyword_list['TREND'] : ''; ?>
							</td>
						</tr>
					</table>
					<div id="url<?php echo isset($_tmpb_keyword_list['ID']) ? $_tmpb_keyword_list['ID'] : ''; ?>" style="display:none;width:100%;"></div>					
				</td>	
			</tr>
			<?php } ?>
			<tr>
				<td class="row3" colspan="5" style="text-align:center;">
					<?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>&nbsp;
				</td>
			</tr>
		</table>
		<?php } ?>
		
		
		<?php if( !isset($this->_block['members']) || !is_array($this->_block['members']) ) $this->_block['members'] = array();
foreach($this->_block['members'] as $members_key => $members_value) {
$_tmpb_members = &$this->_block['members'][$members_key]; ?>
		<table class="module_table">
			<tr>
				<th colspan="2">	
					<?php echo isset($this->_var['L_MEMBERS']) ? $this->_var['L_MEMBERS'] : ''; ?>
				</th>
			</tr>
			<tr>
				<td class="row1" style="text-align:center;width:25%;">
					<?php echo isset($this->_var['L_MEMBERS']) ? $this->_var['L_MEMBERS'] : ''; ?>
				</td>
				<td class="row2">
					<?php echo isset($_tmpb_members['MEMBERS']) ? $_tmpb_members['MEMBERS'] : ''; ?>
				</td>
			 </tr>
			<tr>
				<td class="row1" style="text-align:center;width:50%;">
					<?php echo isset($this->_var['L_LAST_MEMBER']) ? $this->_var['L_LAST_MEMBER'] : ''; ?>
				</td>
				<td class="row2">
					<a href="../member/member<?php echo isset($_tmpb_members['U_LAST_USER_ID']) ? $_tmpb_members['U_LAST_USER_ID'] : ''; ?>"><?php echo isset($_tmpb_members['LAST_USER']) ? $_tmpb_members['LAST_USER'] : ''; ?></a>
				</td>
			</tr>
		</table>

		<br /><br />

		<table class="module_table">
			<tr>
				<th colspan="2">	
					<?php echo isset($this->_var['L_TEMPLATES']) ? $this->_var['L_TEMPLATES'] : ''; ?>
				</th>
			</tr>
			<tr>
				<td style="width:50%;padding-top:30px;vertical-align:top" class="row1">
					<table class="module_table">						
						<tr>
							<td style="text-align:center;" class="row1">			
								<?php echo isset($this->_var['L_TEMPLATES']) ? $this->_var['L_TEMPLATES'] : ''; ?> 
							</td>
							<td style="width:10px;" class="row1">			
								<?php echo isset($this->_var['L_COLORS']) ? $this->_var['L_COLORS'] : ''; ?>
							</td>
							<td style="text-align:center;" class="row1">
								<?php echo isset($this->_var['L_MEMBERS']) ? $this->_var['L_MEMBERS'] : ''; ?>
							</td>				
						</tr>						
						<?php if( !isset($_tmpb_members['templates']) || !is_array($_tmpb_members['templates']) ) $_tmpb_members['templates'] = array();
foreach($_tmpb_members['templates'] as $templates_key => $templates_value) {
$_tmpb_templates = &$_tmpb_members['templates'][$templates_key]; ?>	
						<tr>
							<td style="text-align:center;" class="row2">			
								<?php echo isset($_tmpb_templates['THEME']) ? $_tmpb_templates['THEME'] : ''; ?> <span class="text_small">(<?php echo isset($_tmpb_templates['PERCENT']) ? $_tmpb_templates['PERCENT'] : ''; ?>%)</span>
							</td>							
							<td style="text-align:center;" class="row2">			
								<div style="margin:auto;width:10px;margin:auto;height:10px;background:<?php echo isset($_tmpb_templates['COLOR']) ? $_tmpb_templates['COLOR'] : ''; ?>;border:1px solid black;"></div>
							</td>
							<td style="text-align:center;" class="row2">
								<?php echo isset($_tmpb_templates['NBR_THEME']) ? $_tmpb_templates['NBR_THEME'] : ''; ?>
							</td>				
						</tr>
						<?php } ?>		
					</table>
				</td>
				<td style="text-align:center;padding-top:30px;vertical-align:top" class="row1">
					<?php echo isset($_tmpb_members['GRAPH_RESULT_THEME']) ? $_tmpb_members['GRAPH_RESULT_THEME'] : ''; ?>
				</td>
			</tr>
		</table>

		<br /><br />
		
		<table class="module_table">
			<tr>
				<th colspan="2">	
					<?php echo isset($this->_var['L_SEX']) ? $this->_var['L_SEX'] : ''; ?>
				</th>
			</tr>
			<tr>
				<td style="width:50%;padding-top:30px;vertical-align:top" class="row1">
					<table class="module_table">						
						<tr>
							<td style="text-align:center;" class="row1">			
								<?php echo isset($this->_var['L_SEX']) ? $this->_var['L_SEX'] : ''; ?> 
							</td>
							<td style="width:10px;" class="row1">			
								<?php echo isset($this->_var['L_COLORS']) ? $this->_var['L_COLORS'] : ''; ?>
							</td>
							<td style="text-align:center;" class="row1">
								<?php echo isset($this->_var['L_MEMBERS']) ? $this->_var['L_MEMBERS'] : ''; ?>
							</td>				
						</tr>						
						<?php if( !isset($_tmpb_members['sex']) || !is_array($_tmpb_members['sex']) ) $_tmpb_members['sex'] = array();
foreach($_tmpb_members['sex'] as $sex_key => $sex_value) {
$_tmpb_sex = &$_tmpb_members['sex'][$sex_key]; ?>	
						<tr>
							<td style="text-align:center;" class="row2">			
								<?php echo isset($_tmpb_sex['SEX']) ? $_tmpb_sex['SEX'] : ''; ?> <span class="text_small">(<?php echo isset($_tmpb_sex['PERCENT']) ? $_tmpb_sex['PERCENT'] : ''; ?>%)</span>
							</td>							
							<td style="text-align:center;" class="row2">			
								<div style="margin:auto;width:10px;margin:auto;height:10px;background:<?php echo isset($_tmpb_sex['COLOR']) ? $_tmpb_sex['COLOR'] : ''; ?>;border:1px solid black;"></div>
							</td>
							<td style="text-align:center;" class="row2">
								<?php echo isset($_tmpb_sex['NBR_MBR']) ? $_tmpb_sex['NBR_MBR'] : ''; ?>
							</td>				
						</tr>
						<?php } ?>		
					</table>
				</td>
				<td style="text-align:center;padding-top:30px;vertical-align:top" class="row1">
					<?php echo isset($_tmpb_members['GRAPH_RESULT_SEX']) ? $_tmpb_members['GRAPH_RESULT_SEX'] : ''; ?>
				</td>
			</tr>
		</table>

		<br /><br />
		
		<table class="module_table">
			<tr>
				<th colspan="3">	
					<?php echo isset($this->_var['L_TOP_TEN_POSTERS']) ? $this->_var['L_TOP_TEN_POSTERS'] : ''; ?>
				</th>
			</tr>
			<tr>
				<td class="row3" style="text-align:center;">
					N°
				</td>
				<td class="row3" style="text-align:center;">
					<?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?>
				</td>
				<td class="row3" style="text-align:center;">
					<?php echo isset($this->_var['L_MSG']) ? $this->_var['L_MSG'] : ''; ?>
				</td>
			</tr>			
			<?php if( !isset($_tmpb_members['top_poster']) || !is_array($_tmpb_members['top_poster']) ) $_tmpb_members['top_poster'] = array();
foreach($_tmpb_members['top_poster'] as $top_poster_key => $top_poster_value) {
$_tmpb_top_poster = &$_tmpb_members['top_poster'][$top_poster_key]; ?>			
			<tr>
				<td class="row2" style="text-align:center;">
					<?php echo isset($_tmpb_top_poster['ID']) ? $_tmpb_top_poster['ID'] : ''; ?>
				</td>
				<td class="row2" style="text-align:center;">
					<a href="../member/member<?php echo isset($_tmpb_top_poster['U_MEMBER_ID']) ? $_tmpb_top_poster['U_MEMBER_ID'] : ''; ?>"><?php echo isset($_tmpb_top_poster['LOGIN']) ? $_tmpb_top_poster['LOGIN'] : ''; ?></a>
				</td>
				<td class="row2" style="text-align:center;">
					<?php echo isset($_tmpb_top_poster['USER_POST']) ? $_tmpb_top_poster['USER_POST'] : ''; ?>
				</td>
			</tr>			
			<?php } ?>
		</table>
		<?php } ?>
