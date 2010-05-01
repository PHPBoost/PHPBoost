// This contains all the HTML forms contained in the page
var HTMLForms = Class.create();
HTMLForms.forms = new Array();
HTMLForms.add = function(form) {
	return HTMLForms.forms.push(form);
};
HTMLForms.get = function(id) {
	var form = null;
	HTMLForms.forms.each(function(aForm) {
		if (aForm.getId() == id) {
			form = aForm;
			throw $break;
		}
	});
	return form;
};
HTMLForms.has = function(id) {
	return HTMLForms.get(id) != null;
};
HTMLForms.getFieldset = function(id) {
	var fieldset = null;
	HTMLForms.forms.each(function(form) {
		var aFieldset = form.getFieldset(id);
		if (aFieldset != null) {
			fieldset = aFieldset;
			throw $break;
		}
	});
	return fieldset;
};
HTMLForms.getField = function(id) {
	var field = null;
	HTMLForms.forms.each(function(form) {
		var aField = form.getField(id);
		if (aField != null) {
			field = aField;
			throw $break;
		}
	});
	return field;
};

// This represents a HTML form.
var HTMLForm = Class.create( {
	fieldsets : new Array(),
	id : "",
	initialize : function(id) {
		this.id = id;
		this.fieldsets = new Array();
	},
	getId : function() {
		return this.id;
	},
	addFieldset : function(fieldset) {
		this.fieldsets.push(fieldset);
	},
	getFieldset : function(id) {
		var fieldset = null;
		this.fieldsets.each(function(aFieldset) {
			if (aFieldset.getId() == id) {
				fieldset = aFieldset;
				throw $break;
			}
		});
		return fieldset;
	},
	getFieldsets : function() {
		return this.fieldset;
	},
	hasFieldset : function(id) {
		var hasFieldset = false;
		this.fieldsets.each(function(aFieldset) {
			if (aFieldset.getId() == id) {
				hasFieldset = true;
				throw $break;
			}
		});
		return hasFieldset;
	},
	getFields : function() {
		var fields = new Array();
		fieldsets.each(function(fieldset) {
			fields.push(fieldset.getFields());
		});
		return fields;
	},
	getField : function(id) {
		var field = null;
		this.getFields().each(function(aField) {
			if (aField.getId() == id) {
				field = aField;
				throw $break;
			}
		});
		return field;
	},
	validate : function() {
		var validated = true;
		this.fields.each(function(field) {
			var validation = field.validate();
			if (validation != "") {
				this.displayValidationError(validation);
				validated = false;
				throw $break;
			}
		});
		this.registerDisabledFields();
		return validated;
	},
	displayValidationError : function(message) {
		message = message.replace(/&quot;/g, '"');
		message = message.replace(/&amp;/g, '&');
		alert(message);
	},
	registerDisabledFields : function() {
		var disabled = "";
		this.getFields().each(function(field) {
			if (field.isDisabled()) {
				disabled += "|" + field.getId();
			}
		});
		$(this.id + '_disabled_fields').value = disabled;
	}
});

// This represents a fieldset
var FormFieldset = Class.create( {
	fields : new Array(),
	id : "",
	initialize : function(id) {
		this.id = id;
		this.fields = new Array();
	},
	getId : function() {
		return this.id;
	},
	addField : function(field) {
		this.fields.push(field);
	},
	getField : function(id) {
		var field = null;
		this.fields.each(function(aField) {
			if (aField.getId() == id) {
				field = aField;
				throw $break;
			}
		});
		return field;
	},
	getFields : function() {
		return this.fields;
	},
	hasField : function(id) {
		var hasField = false;
		this.fields.each(function(field) {
			if (field.getId() == id) {
				hasField = true;
				throw $break;
			}
		});
		return hasField;
	},
	enable : function() {
		Effect.Appear(this.id);
		this.fields.each(function(field) {
			field.enable();
		});
	},
	disable : function() {
		Effect.Fade(this.id);
		this.fields.each(function(field) {
			field.disable();
		});
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
