# IF C_MINI #
<div style="margin:2px;height:100px;width:150px;background-color:pink;float:left; {STYLE}" class="draggable" id="draggable_{IDMENU}">
    <div class="module_mini_top">
        <span id="m{IDMENU}"></span>
        <h5 class="sub_title">
            {NAME}
			# IF C_EDIT #
			    <a href="{U_EDIT}" title="{L_EDIT}" />
			        <img src="{PATH_TO_ROOT}/templates/{THEME}/images/french/edit.png" alt="{L_EDIT}" class="valign_middle">
			    </a>
			# ENDIF #
			# IF C_DEL #
			    <a href="{U_DELETE}" title="{L_DEL}" onclick="javascript:return Confirm_menu();">
			        <img src="{PATH_TO_ROOT}/templates/{THEME}/images/french/delete.png" alt="{L_DEL}" class="valign_middle">
			    </a>
			# ENDIF #
          </h5>
    </div><br />
    <div class="module_mini_contents">
        <p>
            <select name="{IDMENU}_enabled" onchange="document.location = {U_ONCHANGE_ENABLED}">
                <option value="1" {SELECT_ENABLED}>{L_ENABLED}</option>
                <option value="0" {SELECT_DISABLED}>{L_DISABLED}</option>
            </select>
        </p>
    </div>
    <div class="module_mini_bottom"></div>
</div>
# ELSE #
<div style="margin:2px;height:100px;width:150px;background-color:pink;float:left;" class="draggable" id="draggable_{IDMENU}">
    <div class="module_top_l"></div>
    <div class="module_top_r"></div>
    <div class="module_top">
        <strong><span id="m{IDMENU}"></span></strong>
        <h5 class="sub_title">
            {NAME}
            # IF C_EDIT #
                <a href="{U_EDIT}" title="{L_EDIT}" />
                    <img src="{PATH_TO_ROOT}/templates/{THEME}/images/french/edit.png" alt="{L_EDIT}" class="valign_middle">
                </a>
            # ENDIF #
            # IF C_DEL #
                <a href="{U_DELETE}" title="{L_DEL}" onclick="javascript:return Confirm_menu();">
                    <img src="{PATH_TO_ROOT}/templates/{THEME}/images/french/delete.png" alt="{L_DEL}" class="valign_middle">
                </a>
            # ENDIF #
          </h5>
    </div>
        
    <div class="module_contents">
        <p>
            <select name="{IDMENU}_enabled" onchange="document.location = {U_ONCHANGE_ENABLED}">
                <option value="1" {SELECT_ENABLED}>{L_ENABLED}</option>
                <option value="0" {SELECT_DISABLED}>{L_DISABLED}</option>
            </select>
        </p>
    </div>
    <div class="module_bottom_l"></div>
    <div class="module_bottom_r"></div>
    <div class="module_bottom"></div>
</div>
# ENDIF #
<script type="text/javascript">
  // <![CDATA[
new Draggable('draggable_{IDMENU}');
  // ]]>
</script>