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
}
HTMLForms.has = function(id) {
	return HTMLForms.get(id) != null;
}

// This represents a HTML form.
var HTMLForm = Class.create( {
	fields : new Array(),
	id : "",
	initialize: function(id) {
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
	}
});

// This represents a field. It can be overloaded to fit to different fields types
var FormField = Class.create( {
	id : 0,
	initialize : function(id) {
		this.id = id;
	},
	getId : function() {
		return this.id;
	},
	enable : function() {
		Field.enable(this.id);
	},
	disable : function() {
		Field.disable(this.id);
	},
	getValue : function() {
		return $F(id);
	},
	validate : function() {
	}
});
