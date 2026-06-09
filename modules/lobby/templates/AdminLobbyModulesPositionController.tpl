<script>
	var lobbyModules = function(id){
		this.id = id;
		this._sortable = null;
	};

	lobbyModules.prototype = {
		init_sortable : function() {
			var self = this;
			this._sortable = Sortable.create(document.getElementById('modules_list'), {
				handle: '.sortable-selector',
				animation: 150,
				onEnd: function() {
					self.change_reposition_pictures();
				}
			});
		},
		serialize_sortable : function() {
			jQuery('#tree').val(JSON.stringify(this.get_sortable_sequence()));
		},
		get_sortable_sequence : function() {
			var sequence = [];
			jQuery('ul#modules_list').children('li').each(function() {
				sequence.push({ id: jQuery(this).data('id') });
			});
			return sequence;
		},
		change_reposition_pictures : function() {
			var sequence = this.get_sortable_sequence();
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

	var lobbyModule = function(id, modules){
		this.id = id;
		this.lobbyModules = modules;

		# IF C_SEVERAL_MODULES #
            this.lobbyModules.change_reposition_pictures();
		# ENDIF #
	};

	lobbyModule.prototype = {
		change_display : function() {
			jQuery("#change-display-" + this.id).html('<i class="fa fa-spin fa-spinner"></i>');
			jQuery.ajax({
				url: '${relative_url(lobbyUrlBuilder::change_display())}',
				type: "post",
				dataType: "json",
				data: {'id' : this.id, 'token' : '{TOKEN}'},
				success: function(returnData){
					if (returnData.id > 0) {
						if (returnData.display) {
							jQuery("#change-display-" + returnData.id).attr('aria-label', '{@common.displayed}').html('<i class="fa fa-eye" aria-hidden="true"></i>');
						} else {
							jQuery("#change-display-" + returnData.id).attr('aria-label', '{@common.hidden}').html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
						}
					}
				}
			});
		}
	};

	var lobbyModules = new lobbyModules('modules_list');
	jQuery(document).ready(function() {
		lobbyModules.init_sortable();
	});
</script>
# INCLUDE MESSAGE_HELPER #
<form id="lobby-module-position" action="{REWRITED_SCRIPT}" method="post" onsubmit="lobbyModules.serialize_sortable();" class="fieldset-content">
	<fieldset id="lobby_modules_management">
		<legend>{@lobby.modules.position}</legend>
		<ul id="modules_list" class="sortable-block">
			# START modules_list #
				# IF modules_list.C_ACTIVE #
					<li class="sortable-element" id="list-{modules_list.ID}" data-id="{modules_list.ID}">
						<div class="sortable-selector" aria-label="{@common.move}"></div>
						<div class="sortable-title">
							{modules_list.NAME}
						</div>
						<div class="sortable-actions">
							# IF C_SEVERAL_MODULES #
								<a href="#" id="move-up-{modules_list.ID}" onclick="return false;" aria-label="{@common.move.up}"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
								<a href="#" id="move-down-{modules_list.ID}" onclick="return false;" aria-label="{@common.move.down}"><i class="fa fa-arrow-down" aria-hidden="true"></i></a>
							# ENDIF #
							<a href="{modules_list.U_EDIT}" aria-label="{@common.edit}"><i class="fa fa-edit" aria-hidden="true"></i></a>
							<a href="#" onclick="return false;" id="change-display-{modules_list.ID}" aria-label="# IF modules_list.C_DISPLAY #{@common.displayed}# ELSE #{@common.hidden}# ENDIF #"><i aria-hidden="true" class="# IF modules_list.C_DISPLAY #fa fa-eye# ELSE #fa fa-eye-slash# ENDIF #"></i></a>
						</div>
						<div class="spacer"></div>
						<script>
							jQuery(document).ready(function() {
								var module = new lobbyModule({modules_list.ID}, lobbyModules);

								jQuery("#change-display-{modules_list.ID}").on('click',function(){
									module.change_display();
								});

								# IF C_SEVERAL_MODULES #
									jQuery("#move-up-{modules_list.ID}").on('click',function(){
										var li = jQuery(this).closest('li');
										li.insertBefore( li.prev() );
										lobbyModules.change_reposition_pictures();
									});

									jQuery("#move-down-{modules_list.ID}").on('click',function(){
										var li = jQuery(this).closest('li');
										li.insertAfter( li.next() );
										lobbyModules.change_reposition_pictures();
									});
								# ENDIF #
							});
						</script>
					</li>
				# ENDIF #
			# END modules_list #
		</ul>
	</fieldset>
	# IF C_SEVERAL_MODULES #
		<fieldset class="fieldset-submit">
			<legend>{@form.submit}</legend>
			<div class="fieldset-inset">
				<button type="submit" name="submit" value="true" class="button submit">{@form.submit}</button>
				<input type="hidden" name="token" value="{TOKEN}">
				<input type="hidden" name="tree" id="tree" value="">
			</div>
		</fieldset>
	# ENDIF #
</form>
