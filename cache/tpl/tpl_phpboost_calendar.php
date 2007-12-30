<table class="module_table" style="width: 205px;"> 
	<tr style="text-align:center;">
		<td>
			<a href="javascript:xmlhttprequest_calendar('<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', '?<?php echo isset($this->_var['U_PREVIOUS']) ? $this->_var['U_PREVIOUS'] : ''; ?>');">&laquo;</a>
		</td>
		<td colspan="5">
			<select name="m" onchange="xmlhttprequest_calendar('<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', '?input_field=<?php echo isset($this->_var['INPUT_FIELD']) ? $this->_var['INPUT_FIELD'] : ''; ?>&amp;field=<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : '';  echo isset($this->_var['LYEAR']) ? $this->_var['LYEAR'] : ''; ?>&amp;d=1&amp;m=' + this.options[this.selectedIndex].value + '&amp;y=<?php echo isset($this->_var['YEAR']) ? $this->_var['YEAR'] : '';  echo isset($this->_var['TYPE']) ? $this->_var['TYPE'] : ''; ?>');">
				<?php if( !isset($this->_block['month']) || !is_array($this->_block['month']) ) $this->_block['month'] = array();
foreach($this->_block['month'] as $month_key => $month_value) {
$_tmpb_month = &$this->_block['month'][$month_key]; ?>
				<?php echo isset($_tmpb_month['MONTH']) ? $_tmpb_month['MONTH'] : ''; ?>
				<?php } ?>	
			</select>
			<select name="y" onchange="xmlhttprequest_calendar('<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', '?input_field=<?php echo isset($this->_var['INPUT_FIELD']) ? $this->_var['INPUT_FIELD'] : ''; ?>&amp;field=<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : '';  echo isset($this->_var['LYEAR']) ? $this->_var['LYEAR'] : ''; ?>&amp;d=1&amp;m=<?php echo isset($this->_var['MONTH']) ? $this->_var['MONTH'] : '';  echo isset($this->_var['TYPE']) ? $this->_var['TYPE'] : ''; ?>&amp;y=' + this.options[this.selectedIndex].value);">
				<?php if( !isset($this->_block['year']) || !is_array($this->_block['year']) ) $this->_block['year'] = array();
foreach($this->_block['year'] as $year_key => $year_value) {
$_tmpb_year = &$this->_block['year'][$year_key]; ?>
					<?php echo isset($_tmpb_year['YEAR']) ? $_tmpb_year['YEAR'] : ''; ?>
				<?php } ?>
			</select>
		</td> 
		<td>
			<a href="javascript:xmlhttprequest_calendar('<?php echo isset($this->_var['FIELD']) ? $this->_var['FIELD'] : ''; ?>', '?<?php echo isset($this->_var['U_NEXT']) ? $this->_var['U_NEXT'] : ''; ?>');">&raquo;</a>
		</td>
	</tr>	
	<tr style="text-align:center;">
		<?php if( !isset($this->_block['day']) || !is_array($this->_block['day']) ) $this->_block['day'] = array();
foreach($this->_block['day'] as $day_key => $day_value) {
$_tmpb_day = &$this->_block['day'][$day_key]; ?>
		<?php echo isset($_tmpb_day['L_DAY']) ? $_tmpb_day['L_DAY'] : ''; ?>
		<?php } ?>
	</tr>
	<tr style="text-align:center;">			
		<?php if( !isset($this->_block['calendar']) || !is_array($this->_block['calendar']) ) $this->_block['calendar'] = array();
foreach($this->_block['calendar'] as $calendar_key => $calendar_value) {
$_tmpb_calendar = &$this->_block['calendar'][$calendar_key]; ?>					
		<?php echo isset($_tmpb_calendar['DAY']) ? $_tmpb_calendar['DAY'] : ''; ?>
		<?php echo isset($_tmpb_calendar['TR']) ? $_tmpb_calendar['TR'] : ''; ?>
		<?php } ?>
	</tr>
</table>