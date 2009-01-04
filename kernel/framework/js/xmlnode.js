function XmlDocument(stream) {
    this.root = null;
    XmlDocument.stream = stream;
    XmlDocument.iterator = 0;
    
    while( XmlDocument.stream.charAt(XmlDocument.iterator++) != "<" );
    try {
        this.root = new XmlNode();
    }
    catch( ex ) {
        throw ex;
    }
}

XmlDocument.prototype.toString = function() {
    return this.root.toString();
}

function Node() {
}

function TextNode() {
    this.text = "";
    for( ; XmlDocument.iterator < XmlDocument.stream.length; XmlDocument.iterator++ )
    {
        var c = XmlDocument.stream.charAt(XmlDocument.iterator);
        if( c == "<" ) break;
        else this.text += c;
    }
}

TextNode.prototype.toString = function() {
    return this.text;
}

function XmlNode() {
    this.name = "";
    this.attributes = new Array();
    this.nodes = new Array();
    
    var hasName = false;
    var isTagOpened = true;
    var currentAttribute = "";
    var currentValue = "";
    var previousChar = "";
    var ended = false;
    
    for( ; XmlDocument.iterator < XmlDocument.stream.length; XmlDocument.iterator++ )
    {
        var c = XmlDocument.stream.charAt(XmlDocument.iterator);
        var code = c.charCodeAt(0);
        
        switch( c ) {
            case "<":
                if( XmlDocument.stream.charAt(XmlDocument.iterator + 1) == "/" ) {    // XML Close Tag
                    if( XmlDocument.stream.substr(XmlDocument.iterator + 2, this.name.length + 1) == (this.name + '>') ) {
                        XmlDocument.iterator += this.name.length + 2;
                        ended = true;
                    }
                    else XmlNode.Throw(XmlNode.XML_CLOSE_TAG_MISMATCH_EXCEPTION);
                    break;
                }
                else {  // Opening a XML Tag
                    if( isTagOpened == true ) XmlNode.Throw(XmlNode.XML_EXCEPTION);
                    XmlDocument.iterator++;
                    try {
                        this.nodes.push(new XmlNode());
                    } catch (ex) {
                        throw ex;
                    }
                }
                break;
            case ">":
                if( isTagOpened == true ) isTagOpened = false;
                else XmlNode.Throw(XmlNode.XML_EXCEPTION);
                if( previousChar == "/" ) ended = true; // Empty XML Node
                break;
            default:
                if( isTagOpened ) {
                    if( !hasName ) {    // Name Construction
                        if( this.name == "" ) {
                            if( XmlNode.isAlpha(code) ) this.name += c;
                            else XmlNode.Throw(XmlNode.XML_NODE_NAME_EXCEPTION);
                        }
                        else {
                            if( XmlNode.isAlphaNum(code) || c == "_" || c == "-" || c == ":") this.name += c;
                            else if ( c == " ") hasName = true;
                            else XmlNode.Throw(XmlNode.XML_NODE_NAME_EXCEPTION);
                        }
                    }
                    else {  // Attibutes Construction
                        if( XmlNode.isAlphaNum(code) || c == "_" || c == "-" ) currentAttribute += c;
                        else if( c == "=" || " " ) {}
                        else if( (previousChar = "\"" && c != "\"") || currentValue != "" ) currentValue += c;
                        else if( c == "\"" && previousChar != "\\" ) {
                            this.attributes[currentAttribute] = currentValue;
                            currentAttribute = '';
                            currentValue = '';
                        }
                        else XmlNode.Throw(XmlNode.ATTRIBUTE_EXCEPTION);
                    }
                }
                else this.nodes.push(new TextNode());// Text Node Creation
                break;
        }
        previousChar = c;
        if( ended ) break;
    }
}

XmlNode.prototype.toString = function( pDepht ) {
    var depht = 0;
    var toStr = "";
    
    if( arguments.length == 1 ) depht = pDepht;

    for( var j = 0; j < depht; j++ ) toStr += "\t";
    toStr += "&lt;" + this.name;

    if( this.attributes.length > 0 ) {
        for( attr in this.attributes.length )
            toStr += " " + attr + "=\"" + this.attributes[attr] + "\"";
    }
    if( this.nodes.length > 0 ) {
        toStr += "&gt;\n"
        for( var i = 0; i < this.nodes.length; i++ ) {
            toStr += this.nodes[i].toString( depht + 1);
        }
        for( var j = 0; j < depht; j++ ) toStr += "\t";
        toStr += "&lt;/" + this.name + "&gt;\n";
    }
    else toStr += " /&gt;\n";
    return toStr;
}

XmlDocument.stream = "";
XmlDocument.iterator = 0;

// Types
XmlNode.TEXT_NODE = 0x01;
XmlNode.XML_NODE = 0x02;

// Errors
XmlNode.XML_EXCEPTION = "INVALID XML";
XmlNode.XML_NODE_NAME_EXCEPTION = "XML NODE NAME INVALID";
XmlNode.ATTRIBUTE_EXCEPTION = "XML ATTRIBUTE NAME INVALID";
XmlNode.XML_CLOSE_TAG_MISMATCH_EXCEPTION = "XML CLOSE TAG MISMATCH";

XmlNode.charBeforeError = 0;
XmlNode.textErrorLength = 10;

XmlNode.Throw = function ( ex ) {
    throw ex + " at " + XmlDocument.iterator + " on '" + XmlDocument.stream.charAt(XmlDocument.iterator) +"' in context : ..." + XmlNode.textOnError() + "...\n" + XmlNode.Dump();
}

XmlNode.Dump = function ( ) {
    var dump = "";
//     dump += "\nhasName = " + hasName;
//     dump += "\nisTagOpened = " + isTagOpened;
//     dump += "\ncurrentAttribute = " + currentAttribute;
//     dump += "\ncurrentValue = " + currentValue;
//     dump += "\npreviousChar = " + previousChar;
//     dump += "\nended = " + ended;
    return dump;
}

XmlNode.textOnError = function ( ) {
    return XmlDocument.stream.substr(XmlDocument.iterator - XmlNode.charBeforeError, XmlNode.textErrorLength);
}

XmlNode.isSeparator = function (code) {
    return !XmlNode.isAlphaNum(code);
}

XmlNode.isAlpha = function (code) {
    return (code & 0xDF) > 64 && (code & 0xDF) < 91;
}

XmlNode.isNum = function (code) {
    return code > 47 && code < 58;
}

XmlNode.isAlphaNum = function (code) {
    return XmlNode.isAlpha(code) || XmlNode.isNum(code);
}