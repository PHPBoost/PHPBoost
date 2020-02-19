# INCLUDE MSG #
# IF C_MORE_THAN_ONE_SOCIAL_NETWORK #
<script>
<!--
var SocialNetworks = function(id){
	this.id = id;
};

SocialNetworks.prototype = {
	init_sortable : function() {
		jQuery("ul#social-networks-list").sortable({
			handle: '.sortable-selector',
			placeholder: '<div class="dropzone">' + ${escapejs(LangLoader::get_message('position.drop_here', 'common'))} + '</div>',
			onDrop: function ($item, container, _super, event) {
				SocialNetworks.change_reposition_pictures();
				$item.removeClass(container.group.options.draggedClass).removeAttr("style");
				$("body").removeClass(container.group.options.bodyClass);
			}
		});
	},
	serialize_sortable : function() {
		jQuery('#tree').val(JSON.stringify(this.get_sortable_sequence()));
	},
	get_sortable_sequence : function() {
		var sequence = jQuery("ul#social-networks-list").sortable("serialize").get();
		return sequence[0];
	},
	change_reposition_pictures : function() {
		sequence = this.get_sortable_sequence();
		var length = sequence.length;
		for(var i = 0; i < length; i++)
		{
			if (jQuery('#list-' + sequence[i].id).is(':first-child'))
				jQuery("#move-up-" + sequence[i].id).hide();
			else
				jQuery("#move-up-" + sequence[i].id).show();

			if (jQuery('#list-' + sequence[i].id).is(':last-child'))
				jQuery("#move-down-" + sequence[i].id).hide();
			else
				jQuery("#move-down-" + sequence[i].id).show();
		}
	}
};

var SocialNetwork = function(id, social_networks){
	this.id = id;
	this.SocialNetworks = social_networks;

	this.SocialNetworks.change_reposition_pictures();
};

SocialNetwork.prototype = {
	change_display : function() {
		jQuery("#change-display-" + this.id).html('<i class="fa fa-spin fa-spinner"></i>');
		jQuery.ajax({
			url: '/SocialNetworks/index.php?url=/config/change_display',
			type: "post",
			dataType: "json",
			data: {'id' : this.id, 'token' : '{TOKEN}'},
			success: function(returnData){
				if (returnData.id != '') {
					if (returnData.display) {
						jQuery("#change-display-" + returnData.id).html('<i class="fa fa-eye" aria-label="{@admin.display_share_link}"></i>');
					} else {
						jQuery("#change-display-" + returnData.id).html('<i class="fa fa-eye-slash" aria-label="{@admin.hide_share_link}"></i>');
					}
				}
			}
		});
	}
};

var SocialNetworks = new SocialNetworks('social-networks-list');
jQuery(document).ready(function() {
	SocialNetworks.init_sortable();
});
-->
</script>
<form action="{REWRITED_SCRIPT}" method="post" onsubmit="SocialNetworks.serialize_sortable();" class="fieldset-content">
	<fieldset id="social-networks-management">
		<legend>{@admin.order.manage}</legend>
		<ul id="social-networks-list" class="sortable-block social-networks-list-container">
			# START social_networks_list #
				<li class="sortable-element" id="list-{social_networks_list.ID}" data-id="{social_networks_list.ID}">
					<div class="sortable-selector" aria-label="${LangLoader::get_message('position.move', 'common')}"></div>
					<div class="sortable-title">
						<span class="social-connect {social_networks_list.CSS_CLASS}"><i class="fab fa-fw fa-{social_networks_list.ICON_NAME}"></i></span>
						{social_networks_list.NAME}
					</div>
					<div class="sortable-actions">
						# IF social_networks_list.C_MOBILE_ONLY #<a href="#" aria-label="{@admin.visible_on_mobile_only}" onclick="return false;"><i class="fa fa-mobile-alt" aria-hidden="true"></i></a># ENDIF #
						# IF social_networks_list.C_DESKTOP_ONLY #<a href="#" aria-label="{@admin.visible_on_desktop_only}" onclick="return false;"><i class="fa fa-laptop" aria-hidden="true"></i></a># ENDIF #
						<a href="#" aria-label="${LangLoader::get_message('position.move_up', 'common')}" id="move-up-{social_networks_list.ID}" onclick="return false;"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
						<a href="#" aria-label="${LangLoader::get_message('position.move_down', 'common')}" id="move-down-{social_networks_list.ID}" onclick="return false;"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
						# IF social_networks_list.C_SHARING_CONTENT #
							<a href="#" onclick="return false;" id="change-display-{social_networks_list.ID}" aria-label="# IF social_networks_list.C_DISPLAY #{@admin.display_share_link}# ELSE #{@admin.hide_share_link}# ENDIF #">
								<i aria-hidden="true" # IF social_networks_list.C_DISPLAY #class="fa fa-eye"# ELSE #class="fa fa-eye-slash"# ENDIF #></i>
							</a>
						# ELSE #
							<i class="fa fa-ban" aria-hidden="true" aria-label="{@admin.no_sharing_content_url}"></i>
							<span class="sr-only">{@admin.no_sharing_content_url}</span>
						# ENDIF #
					</div>
					<script>
					<!--
					jQuery(document).ready(function() {
						var social_network = new SocialNetwork('{social_networks_list.ID}', SocialNetworks);

						jQuery("#change-display-{social_networks_list.ID}").on('click',function(){
							social_network.change_display();
						});

						jQuery("#move-up-{social_networks_list.ID}").on('click',function(){
							var li = jQuery(this).closest('li');
							li.insertBefore( li.prev() );
							SocialNetworks.change_reposition_pictures();
						});

						jQuery("#move-down-{social_networks_list.ID}").on('click',function(){
							var li = jQuery(this).closest('li');
							li.insertAfter( li.next() );
							SocialNetworks.change_reposition_pictures();
						});
					});
					-->
					</script>
				</li>
			# END social_networks_list #
		</ul>
	</fieldset>
	<fieldset class="fieldset-submit">
		<div class="fieldset-inset">
			<button type="submit" name="order_manage_submit" value="true" class="button submit">${LangLoader::get_message('position.update', 'common')}</button>
			<input type="hidden" name="token" value="{TOKEN}">
			<input type="hidden" name="tree" id="tree" value="">
		</div>
	</fieldset>
</form>
# ENDIF #

<div class="fieldset social-networks-menu-container">
	<div class="legend">{@admin.menu.position}</div>
	<div class="fieldset-inset">
		<div class="social-networks-menu">{@H|admin.menu.mini_module_message}</div>
		<div class="social-networks-menu">{@H|admin.menu.content_message}</div>
	</div>
</div>

# INCLUDE FORM #
