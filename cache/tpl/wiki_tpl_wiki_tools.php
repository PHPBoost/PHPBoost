		<noscript>
			<div class="row2" style="text-align:right;">
				&nbsp;
				<?php if( !isset($this->_block['tool']) || !is_array($this->_block['tool']) ) $this->_block['tool'] = array();
foreach($this->_block['tool'] as $tool_key => $tool_value) {
$_tmpb_tool = &$this->_block['tool'][$tool_key]; ?>
					<a href="<?php echo isset($_tmpb_tool['U_TOOL']) ? $_tmpb_tool['U_TOOL'] : ''; ?>"><?php echo isset($_tmpb_tool['L_TOOL']) ? $_tmpb_tool['L_TOOL'] : ''; ?></a>
					<?php if( !isset($_tmpb_tool['separation']) || !is_array($_tmpb_tool['separation']) ) $_tmpb_tool['separation'] = array();
foreach($_tmpb_tool['separation'] as $separation_key => $separation_value) {
$_tmpb_separation = &$_tmpb_tool['separation'][$separation_key]; ?>
						&bull;
					<?php } ?>
				<?php } ?>
				&nbsp;
				<br />
			</div>
		</noscript>

		<div id="dynamic_menu">
			<div style="float:right;">
				<div style="float:left;" onmouseover="show_menu(1);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(1)" style="margin-right:20px;" class="horizontal"><img src="<?php echo isset($this->_var['WIKI_PATH']) ? $this->_var['WIKI_PATH'] : ''; ?>/images/contribuate.png" style="vertical-align:middle;" alt="" />&nbsp;<?php echo isset($this->_var['L_OTHER_TOOLS']) ? $this->_var['L_OTHER_TOOLS'] : ''; ?>&nbsp;</h5>					
					<div id="smenu1" class="horizontal_block">
						<ul>
							<?php if( !isset($this->_block['contribution_tools']) || !is_array($this->_block['contribution_tools']) ) $this->_block['contribution_tools'] = array();
foreach($this->_block['contribution_tools'] as $contribution_tools_key => $contribution_tools_value) {
$_tmpb_contribution_tools = &$this->_block['contribution_tools'][$contribution_tools_key]; ?>
							<li><a href="<?php echo isset($_tmpb_contribution_tools['U_ACTION']) ? $_tmpb_contribution_tools['U_ACTION'] : ''; ?>" title="<?php echo isset($_tmpb_contribution_tools['L_ACTION']) ? $_tmpb_contribution_tools['L_ACTION'] : ''; ?>" onclick="<?php echo isset($_tmpb_contribution_tools['ONCLICK']) ? $_tmpb_contribution_tools['ONCLICK'] : ''; ?>" <?php echo isset($_tmpb_contribution_tools['DM_A_CLASS']) ? $_tmpb_contribution_tools['DM_A_CLASS'] : ''; ?>><?php echo isset($_tmpb_contribution_tools['L_ACTION']) ? $_tmpb_contribution_tools['L_ACTION'] : ''; ?></a></li>
							<?php } ?>							
						</ul>
						<span class="dm_bottom"></span>
					</div>						
				</div>
				<div style="float:left;" onmouseover="show_menu(2);" onmouseout="hide_menu();">
					<h5 onclick="temporise_menu(2)" style="margin-right:5px;" class="horizontal"><img src="<?php echo isset($this->_var['WIKI_PATH']) ? $this->_var['WIKI_PATH'] : ''; ?>/images/tools.png" style="vertical-align:middle;" alt="" />&nbsp;<?php echo isset($this->_var['L_CONTRIBUTION_TOOLS']) ? $this->_var['L_CONTRIBUTION_TOOLS'] : ''; ?>&nbsp;</h5>					
					<div id="smenu2" class="horizontal_block">
						<ul>
							<?php if( !isset($this->_block['other_tools']) || !is_array($this->_block['other_tools']) ) $this->_block['other_tools'] = array();
foreach($this->_block['other_tools'] as $other_tools_key => $other_tools_value) {
$_tmpb_other_tools = &$this->_block['other_tools'][$other_tools_key]; ?>
							<li><a href="<?php echo isset($_tmpb_other_tools['U_ACTION']) ? $_tmpb_other_tools['U_ACTION'] : ''; ?>" title="<?php echo isset($_tmpb_other_tools['L_ACTION']) ? $_tmpb_other_tools['L_ACTION'] : ''; ?>" onclick="<?php echo isset($_tmpb_other_tools['ONCLICK']) ? $_tmpb_other_tools['ONCLICK'] : ''; ?>" <?php echo isset($_tmpb_other_tools['DM_A_CLASS']) ? $_tmpb_other_tools['DM_A_CLASS'] : ''; ?>><?php echo isset($_tmpb_other_tools['L_ACTION']) ? $_tmpb_other_tools['L_ACTION'] : ''; ?></a></li>
							<?php } ?>							
						</ul>
						<span class="dm_bottom"></span>
					</div>						
				</div>
			</div>
		</div>
		