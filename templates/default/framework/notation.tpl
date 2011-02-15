		<script type="text/javascript">
		<!--
		var Note = Class.create({
			id : 0,
			picture : 0,
			timeout : null,
			notation_scale : 0,
			default_note : new Array(),
			initialize : function(id , notation_scale) {
				this.id = id;
				this.notation_scale = notation_scale;
			},
			set_default_note : function(note_bdd) {
				this.default_note[this.id] = note_bdd;
			},
			get_default_note : function() {
				return this.default_note[this.id];
			},
			send : function(note) {
				document.getElementById('noteloading' + this.id).innerHTML = '<img src="' + PATH_TO_ROOT + '/templates/' + THEME + '/images/loading_mini.gif" alt="" class="valign_middle" />';
					
				new Ajax.Request(
				'{CURRENT_URL}',
				{
					method: 'post',
					parameters: {'note': note, 'valid_note': true},
					onSuccess: function() {
						document.getElementById('noteloading' + this.id).innerHTML = ''; 
					},
				});
			},
			over : function () {
				if(this.picture == 0)
					this.picture = 1;
				clearTimeout(this.timeout);
				this.timeout = null;
			},
			out : function (note) {
				if(this.timeout == null)
					this.timeout = setTimeout('this.display_picture(' + note + '); this.picture = 0;', '50');
			},
			display_picture : function (note) {
				var picture_star;
				var decimal;
				for(var i = 1; i <= this.notation_scale; i++)
				{
					picture_star = 'stars.png';
					if(note < i)
					{							
						decimal = i - note;
						if(decimal >= 1)
							picture_star = 'stars0.png';
						else if(decimal >= 0.75)
							picture_star = 'stars1.png';
						else if(decimal >= 0.50)
							picture_star = 'stars2.png';
						else
							picture_star = 'stars3.png';
					}
					
					if( document.getElementById('n' + this.id + '_stars' + i) )
						document.getElementById('n' + this.id + '_stars' + i).src = PATH_TO_ROOT + '/templates/' + THEME + '/images/' + picture_star;
				}
			}
		});
		
		var Note = new Note({MODULE_ID}, {NOTATION_SCALE});
		Note.set_default_note('{AVERAGE_NOTES}');
		
		Event.observe('note_stars{MODULE_ID}', 'mouseover', function() {
			Note.over();
		});
		
		Event.observe('note_stars{MODULE_ID}', 'mouseout', function() {
			Note.out(Note.get_default_note());
		});
		
		-->
		</script> 
		
		<form action="" method="post" class="text_small">
			<div>
				<span id="note_value{MODULE_ID}">
					# IF C_VOTES #
						<strong>{NUMBER_VOTES}</strong>
					# ELSE #
						{L_NO_NOTE}
					# ENDIF #
				</span>
				<div style="width:{NUMBER_PIXEL}px;margin:auto;display:none" id="note_stars{MODULE_ID}" >
					# START notation #
						<a href="javascript:Note.send({notation.I})" onmouseover="Note.display_picture({notation.I});">
							<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{notation.IMAGE}" alt="" class="valign_middle" id="n{MODULE_ID}_stars{notation.I}" />
						</a>
					# END notation #
				</div>
				<span id="noteloading{MODULE_ID}"></span>
				<select id="note_select{MODULE_ID}" name="note">
					<option value="-1">{L_NOTE}</option>
					# START notation_no_js #
						<option value="{notation_no_js.I}">{notation_no_js.I}</option>
					# END notation_no_js #
				</select>
				
				<input type="hidden" name="token" value="{TOKEN}" />
				<input type="hidden" name="valid_note" value="true" /> 
				<input type="submit" name="valid" id="valid_note{MODULE_ID}" value="{L_VALID_NOTE}" class="submit" style="padding:1px 2px;" />
			</div>
			<script type="text/javascript">
			<!--				
				document.getElementById('note_value{MODULE_ID}').style.display = 'none';
				document.getElementById('note_select{MODULE_ID}').style.display = 'none';
				document.getElementById('valid_note{MODULE_ID}').style.display = 'none';
				document.getElementById('note_stars{MODULE_ID}').style.display = 'inline';
			-->
			</script>
		</form>
		