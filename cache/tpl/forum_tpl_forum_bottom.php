		<div class="module_position" style="margin-top:15px;">
			<div class="row2">
				<span style="float:left;">
					&bull; <a href="index.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_FORUM_INDEX']) ? $this->_var['L_FORUM_INDEX'] : ''; ?></a> &bull; <a href="stats.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_STATS']) ? $this->_var['L_STATS'] : ''; ?></a> <a href="stats.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><img src="<?php echo isset($this->_var['MODULE_DATA_PATH']) ? $this->_var['MODULE_DATA_PATH'] : ''; ?>/images/stats.png" alt="" class="valign_middle" /></a>
				</span>
				<span style="float:right;">
					<?php echo isset($this->_var['U_SEARCH']) ? $this->_var['U_SEARCH'] : ''; ?>
					<?php echo isset($this->_var['U_TOPIC_TRACK']) ? $this->_var['U_TOPIC_TRACK'] : ''; ?>
					<?php echo isset($this->_var['U_LAST_MSG_READ']) ? $this->_var['U_LAST_MSG_READ'] : ''; ?>
					<?php echo isset($this->_var['U_MSG_NOT_READ']) ? $this->_var['U_MSG_NOT_READ'] : ''; ?>
					<?php echo isset($this->_var['U_MSG_SET_VIEW']) ? $this->_var['U_MSG_SET_VIEW'] : ''; ?>
				</span>&nbsp;
			</div>
			<?php if( isset($this->_var['C_TOTAL_POST']) && $this->_var['C_TOTAL_POST'] ) { ?>
			<div class="forum_online">
				<?php echo isset($this->_var['L_TOTAL_POST']) ? $this->_var['L_TOTAL_POST'] : ''; ?>: <strong><?php echo isset($this->_var['NBR_MSG']) ? $this->_var['NBR_MSG'] : ''; ?></strong> <?php echo isset($this->_var['L_MESSAGE']) ? $this->_var['L_MESSAGE'] : ''; echo ' '; echo isset($this->_var['L_DISTRIBUTED']) ? $this->_var['L_DISTRIBUTED'] : ''; ?> <strong><?php echo isset($this->_var['NBR_TOPIC']) ? $this->_var['NBR_TOPIC'] : ''; ?></strong> <?php echo isset($this->_var['L_TOPIC']) ? $this->_var['L_TOPIC'] : ''; ?>.
			</div>
			<?php } ?>
			<?php if( isset($this->_var['USERS_ONLINE']) && $this->_var['USERS_ONLINE'] ) { ?>
			<div class="forum_online">
				<span style="float:left;">
					<?php echo isset($this->_var['TOTAL_ONLINE']) ? $this->_var['TOTAL_ONLINE'] : ''; echo ' '; echo isset($this->_var['L_USER']) ? $this->_var['L_USER'] : ''; echo ' '; echo isset($this->_var['L_ONLINE']) ? $this->_var['L_ONLINE'] : ''; ?> :: <?php echo isset($this->_var['ADMIN']) ? $this->_var['ADMIN'] : ''; echo ' '; echo isset($this->_var['L_ADMIN']) ? $this->_var['L_ADMIN'] : ''; ?>, <?php echo isset($this->_var['MODO']) ? $this->_var['MODO'] : ''; echo ' '; echo isset($this->_var['L_MODO']) ? $this->_var['L_MODO'] : ''; ?>, <?php echo isset($this->_var['MEMBER']) ? $this->_var['MEMBER'] : ''; echo ' '; echo isset($this->_var['L_MEMBER']) ? $this->_var['L_MEMBER'] : ''; echo ' '; echo isset($this->_var['L_AND']) ? $this->_var['L_AND'] : ''; echo ' '; echo isset($this->_var['GUEST']) ? $this->_var['GUEST'] : ''; echo ' '; echo isset($this->_var['L_GUEST']) ? $this->_var['L_GUEST'] : ''; ?>
					<br />
					<?php echo isset($this->_var['L_MEMBER']) ? $this->_var['L_MEMBER'] : ''; echo ' '; echo isset($this->_var['L_ONLINE']) ? $this->_var['L_ONLINE'] : ''; ?>: <?php echo isset($this->_var['USERS_ONLINE']) ? $this->_var['USERS_ONLINE'] : ''; ?>
				</span>
				<span style="float:right;text-align:right">
					<?php if( isset($this->_var['SELECT_CAT']) && $this->_var['SELECT_CAT'] ) { ?>
					<form action="<?php echo isset($this->_var['U_CHANGE_CAT']) ? $this->_var['U_CHANGE_CAT'] : ''; ?>" method="post">
						<select name="change_cat" onchange="document.location = <?php echo isset($this->_var['U_ONCHANGE']) ? $this->_var['U_ONCHANGE'] : ''; ?>;">
							<?php echo isset($this->_var['SELECT_CAT']) ? $this->_var['SELECT_CAT'] : ''; ?>
						</select>
						<noscript>
							<input type="submit" name="valid_change_cat" value="Go" class="submit" />
						</noscript>
					</form>
					<?php } ?>
						
					<?php if( isset($this->_var['C_MASS_MODO_CHECK']) && $this->_var['C_MASS_MODO_CHECK'] ) { ?>
					<form action="action.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>">
						<?php echo isset($this->_var['L_FOR_SELECTION']) ? $this->_var['L_FOR_SELECTION'] : ''; ?>: 
						<select name="massive_action_type">
							<option value="change"><?php echo isset($this->_var['L_CHANGE_STATUT_TO']) ? $this->_var['L_CHANGE_STATUT_TO'] : ''; ?></option>
							<option value="changebis"><?php echo isset($this->_var['L_CHANGE_STATUT_TO_DEFAULT']) ? $this->_var['L_CHANGE_STATUT_TO_DEFAULT'] : ''; ?></option>
							<option value="move"><?php echo isset($this->_var['L_MOVE_TO']) ? $this->_var['L_MOVE_TO'] : ''; ?></option>
							<option value="lock"><?php echo isset($this->_var['L_LOCK']) ? $this->_var['L_LOCK'] : ''; ?></option>
							<option value="unlock"><?php echo isset($this->_var['L_UNLOCK']) ? $this->_var['L_UNLOCK'] : ''; ?></option>
							<option value="del"><?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?></option>
						</select>
						<input type="submit" value="<?php echo isset($this->_var['L_GO']) ? $this->_var['L_GO'] : ''; ?>" name="valid" class="submit" />
					</form>
					<?php } ?>
				</span>
				<div class="spacer"></div>
			</div>
			<?php } ?>
		</div>
