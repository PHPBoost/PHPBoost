// This contains all the HTML forms contained in the page
var HTMLForms = Class.create();
HTMLForms.forms = new Array();
HTMLForms.add = function(form) {
	return HTMLForms.forms.push(form);
};
HTMLForms.get = function(id) {
	for ( var i = 0; i < HTMLForms.forms.length; i++) {
		var form = HTMLForms.forms[i];
		if (form.getId() == id) {
			return form;
		}
	}
	return null;
};
HTMLForms.has = function(id) {
	return HTMLForms.get(id) != null;
};
HTMLForms.getField = function(id) {
	for ( var i = 0; i < HTMLForms.forms.length; i++) {
		var form = HTMLForms.forms[i];
		var field = form.getField(id);
		if (field != null) {
			return field;
		}
	}
	return null;
};

// This represents a HTML form.
var HTMLForm = Class.create( {
	fields : new Array(),
	id : "",
	initialize : function(id) {
		this.id = id;
	},
	getId : function() {
		return this.id;
	},
	addField : function(field) {
		this.fields.push(field);
	},
	getField : function(id) {
		for ( var i = 0; i < this.fields.length; i++) {
			var field = this.fields[i];
			if (field.getId() == id) {
				return field;
			}
		}
		return null;
	},
	getFields : function() {
		return this.fields;
	},
	hasField : function(id) {
		for ( var i = 0; i < this.fields.length; i++) {
			var field = this.fields[i];
			if (field.getId() == id) {
				return true;
			}
		}
		return false;
	},
	validate : function() {
		for ( var i = 0; i < this.fields.length; i++) {
			var field = this.fields[i];
			var validation = field.validate();
			if (validation != "") {
				this.displayValidationError(validation);
				return false;
			}
		}
		this.registerDisabledFields();
		return true;
	},
	displayValidationError : function(message) {
		message = message.replace(/&quot;/g, '"');
		message = message.replace(/&amp;/g, '&');
		alert(message);
	},
	registerDisabledFields : function() {
		var disabled = "";
		for ( var i = 0; i < this.fields.length; i++) {
			var field = this.fields[i];
			if (field.isDisabled()) {
				disabled += "|" + field.getId();
			}
		}
		$(this.id + '_disabled_fields').value = disabled;
	}
});

// This represents a field. It can be overloaded to fit to different fields
// types
var FormField = Class
		.create( {
			id : 0,
			validationMessageEnabled : false,
			initialize : function(id) {
				this.id = id;
			},
			getId : function() {
				return this.id;
			},
			enable : function() {
				Field.enable(this.id);
				this.liveValidate();
			},
			disable : function() {
				Field.disable(this.id);
				this.clearErrorMessage();
			},
			isDisabled : function() {
				var element = $(this.id);
				return element.disabled != "disabled"
						&& element.disabled != false;
			},
			getValue : function() {
				return $F(this.id);
			},
			enableValidationMessage : function() {
				this.validationMessageEnabled = true;
			},
			displayErrorMessage : function(message) {
				if (!this.validationMessageEnabled) {
					return;
				}
				if ($('onblurContainerResponse' + this.id)
						&& $('onblurMesssageResponse' + this.id)) {
					$('onblurContainerResponse' + this.id).innerHTML = '<img src="'
							+ PATH_TO_ROOT
							+ '/templates/'
							+ THEME
							+ '/images/forbidden_mini.png" alt="" class="valign_middle" />';
					$('onblurMesssageResponse' + this.id).innerHTML = message;

					Effect.Appear('onblurContainerResponse' + this.id, {
						duration : 0.5
					});
					Effect.Appear('onblurMesssageResponse' + this.id, {
						duration : 0.5
					});
				}
			},
			displaySuccessMessage : function() {
				if (!this.validationMessageEnabled) {
					return;
				}
				if ($('onblurContainerResponse' + this.id)) {
					$('onblurContainerResponse' + this.id).innerHTML = '<img src="'
							+ PATH_TO_ROOT
							+ '/templates/'
							+ THEME
							+ '/images/processed_mini.png" alt="" class="valign_middle" />';
					Effect.Appear('onblurContainerResponse' + this.id, {
						duration : 0.2
					});

					Effect.Fade('onblurMesssageResponse' + this.id, {
						duration : 0.2
					});
				}
			},
			clearErrorMessage : function() {
				if ($('onblurContainerResponse' + this.id)) {
					$('onblurContainerResponse' + this.id).innerHTML = '';

					Effect.Appear('onblurContainerResponse' + this.id, {
						duration : 0.2
					});

					Effect.Fade('onblurMesssageResponse' + this.id, {
						duration : 0.2
					});
				}
			},
			liveValidate : function() {
				if (!this.isDisabled()) {
					var errorMessage = this.doValidate();
					if (errorMessage != "") {
						this.displayErrorMessage(errorMessage);
					} else {
						this.displaySuccessMessage();
					}
				}
			},
			validate : function() {
				if (!this.isDisabled()) {
					return this.doValidate();
				}
				return "";
			},
			doValidate : function() {
				return '';
			}
		});
