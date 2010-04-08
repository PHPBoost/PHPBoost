var HTMLForm = Class.create( {
	fields : {},
	addField : function(field) {
		this.fields[field.getId()] = field;
	},
	getField : function(id) {
		return this.fields[id];
	},
	getFields : function() {
		var list = new Array();
		for ( var i = 0; i < this.fields.length; i++) {
			list.push(fields.fields[i]);
		}
		return list;
	}
});

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
	}
});
