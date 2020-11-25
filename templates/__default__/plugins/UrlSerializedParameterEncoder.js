var UrlSerializedParameterEncoder = function UrlSerializedParameterEncoder(){};

UrlSerializedParameterEncoder.prototype = {
	encode : function(parameters) {
		return this.encode_array_values(parameters);
	},
	encode_array_values : function(array) {
		var serialized_parameters = new Array();
		for (key in array) {
			if (typeof array[key] != 'function') {
				serialized_parameters.push(this.encode_parameter(key, array[key]));
			}
		}
		return serialized_parameters.join(',');
	},
	encode_array : function(array) {
		return '{' + this.encode_array_values(array) + '}';
	},
	encode_parameter : function(key, value) {
		return this.encode_name(key) + this.encode_value(value);
	},
	encode_name : function(key) {
		if (typeof(key) == 'string' && key.match(/^[a-z][a-z0-9]*$/i)) {
			return key + ':';
		}
		return '';
	},
	encode_value : function(value) {
		if (typeof value == 'object') {
			return this.encode_array(value);
		} else {
			return this.encode_string(String(value));
		}
	},
	encode_string : function(value) {
		var chars = new Array('\\', ',', ':', '{', '}');
		for (i in chars) {
			var char = chars[i];
			value = value.replace(char, '\\' + char);
		}
		return value;
	}	
};