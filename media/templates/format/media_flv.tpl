<?php $this->assign_vars(array('FLOW_PLAYER_UID' => 'flowplayer_' . get_uid())); ?>
<a href="{URL}" style="display:block;margin:auto;width:{WIDTH}px;height:{HEIGHT}px;" id="{FLOW_PLAYER_UID}"></a><br />
<script type="text/javascript">
<!--
	flowPlayerShow("{FLOW_PLAYER_UID}");
--> 
</script>