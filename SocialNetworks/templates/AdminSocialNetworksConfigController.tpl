# INCLUDE MESSAGE_HELPER #
# IF C_MORE_THAN_ONE_SOCIAL_NETWORK #
	<script>
		var SocialNetworks = function(id){
			this.id = id;
		};

		SocialNetworks.prototype = {
			init_sortable : function() {
				jQuery("ul#social-networks-list").sortable({
					handle: '.sortable-selector',
					placeholder: '<div class="dropzone">' + ${escapejs(@common.drop.here)} + '</div>',
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
								jQuery("#change-display-" + returnData.id).html('<i class="fa fa-eye" aria-label="{@sn.display.share.link}"></i>');
							} else {
								jQuery("#change-display-" + returnData.id).html('<i class="fa fa-eye-slash" aria-label="{@sn.hide.share.link}"></i>');
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
	</script>
	<form action="{REWRITED_SCRIPT}" method="post" onsubmit="SocialNetworks.serialize_sortable();" class="fieldset-content">
		<fieldset id="social-networks-management">
			<legend>{@sn.order.management}</legend>
			<ul id="social-networks-list" class="sortable-block social-networks-list-container">
				# START social_networks_list #
					<li class="sortable-element" id="list-{social_networks_list.ID}" data-id="{social_networks_list.ID}">
						<div class="sortable-selector" aria-label="{@common.move}"></div>
						<div class="sortable-title">
							<span class="social-connect {social_networks_list.CSS_CLASS}"><i class="fab fa-fw fa-{social_networks_list.ICON_NAME}"></i></span>
							{social_networks_list.NAME}
						</div>
						<div class="sortable-actions">
							# IF social_networks_list.C_MOBILE_ONLY #<a href="#" aria-label="{@sn.visible.on.mobile.only}" onclick="return false;"><i class="fa fa-mobile-alt" aria-hidden="true"></i></a># ENDIF #
							# IF social_networks_list.C_DESKTOP_ONLY #<a href="#" aria-label="{@sn.visible.on.desktop.only}" onclick="return false;"><i class="fa fa-laptop" aria-hidden="true"></i></a># ENDIF #
							<a href="#" aria-label="{@common.move.up}" id="move-up-{social_networks_list.ID}" onclick="return false;"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
							<a href="#" aria-label="{@common.move.down}" id="move-down-{social_networks_list.ID}" onclick="return false;"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
							# IF social_networks_list.C_SHARING_CONTENT #
								<a href="#" onclick="return false;" id="change-display-{social_networks_list.ID}" aria-label="# IF social_networks_list.C_DISPLAY #{@sn.display.share.link}# ELSE #{@sn.hide.share.link}# ENDIF #">
									<i aria-hidden="true" class="fa # IF social_networks_list.C_DISPLAY #fa-eye# ELSE # fa-eye-slash# ENDIF #"></i>
								</a>
							# ELSE #
								<a href="#" onclick="return false;" aria-label="{@sn.no.sharing.content.url}">
									<i class="fa fa-ban" aria-hidden="true"></i>
								</a>
							# ENDIF #
						</div>
						<script>
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
						</script>
					</li>
				# END social_networks_list #
			</ul>
		</fieldset>
		<fieldset class="fieldset-submit">
			<legend>{@form.submit}</legend>
			<div class="fieldset-inset">
				<button type="submit" name="order_manage_submit" value="true" class="button submit">{@form.submit}</button>
				<input type="hidden" name="token" value="{TOKEN}">
				<input type="hidden" name="tree" id="tree" value="">
			</div>
		</fieldset>
	</form>
# ENDIF #

<div class="fieldset-content">
	<fieldset>
		<legend>{@sn.menu.position}</legend>
		<div class="fieldset-inset">
			<div class="form-element half-field">
				<div class="form-field">{@H|sn.menu.mini.module.message}</div>
			</div>
			<div class="form-element half-field">
				<div class="form-field">{@H|sn.menu.content.message}</div>
			</div>
		</div>
	</fieldset>
</div>

# INCLUDE FORM #
