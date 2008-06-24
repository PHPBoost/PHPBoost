function XmlNode(arg1, arg2) {
    this.name = "";
    this.text = "";
    this.attributes = new Array();
    this.nodes = new Array();
    
    var hasName = false;
    var isTagOpened = false;
    var currentAttribute = "";
    var currentValue = "";
    var previousChar = "";
    var ended = false;
    
    switch( arguments.length ) {
        case 1:
            XmlNode.Stream = arg1;
            XmlNode.iterator = 0;
            this.type = XmlNode.XML_NODE;
            isTagOpened = false;
            break;
        case 2:
            this.type = arg1;
            isTagOpened = arg2;
            break;
        default:
            break;
    }
    
    for( ; XmlNode.iterator < XmlNode.Stream.length; XmlNode.iterator++ )
    {
        var c = XmlNode.Stream.charAt(XmlNode.iterator);
        var code = c.charCodeAt(0);
        
        switch( c ) {
            case "<":
                if( XmlNode.Stream.charAt(XmlNode.iterator + 1) == "/" ) {
                    while( XmlNode.Stream.charAt(XmlNode.iterator++) != ">" );
                    ended = true;
                    break;
                }
                if( isTagOpened == false ) isTagOpened = true;
                else throw XmlNode.XML_EXCEPTION + " at " + XmlNode.iterator + " on '" + c + "'";
                XmlNode.iterator++;
                try {
                    xNode = new XmlNode(XmlNode.XML_NODE, true);
                } catch (ex) {
                    throw ex;
                }
                this.nodes.push(xNode);
                break;
            case ">":
                if( isTagOpened == true ) isTagOpened = false;
                else throw XmlNode.XML_EXCEPTION + " at " + XmlNode.iterator + " on '" + c + "'";
                break;
            default:
                if( isTagOpened && !hasName ) { // Name Construction
                    if( this.name == "" ) {
                        if( XmlNode.isAlpha(code) ) this.name += c;
                        else throw XmlNode.XML_NODE_NAME_EXCEPTION + " at " + XmlNode.iterator + " on '" + c + "'";
                    }
                    else {
                        if( XmlNode.isAlphaNum(code) || c == "_" || c == "-" || c == ":") this.name += c;
                        else if ( c = " ") hasName = true;
                        else throw XmlNode.XML_NODE_NAME_EXCEPTION + " at " + XmlNode.iterator + " on '" + c + "'"
                    }
                }
                else if( isTagOpened ) {  // Attibutes Construction
                    if( XmlNode.isAlphaNum(code) || c == "_" || c == "-" ) currentAttribute += c;
                    else if( c == "=" || " " ) {}
                    else if( (previousChar = "\"" && c != "\"") || currentValue != "" ) currentValue += c;
                    else if( c == "\"" && previousChar != "\\" ) {
                        this.attributes[currentAttribute] = currentValue;
                        currentAttribute = '';
                        currentValue = '';
                    }
                    else throw ATTRIBUTE_EXCEPTION + " at " + XmlNode.iterator + " on '" + c + "'";
                }
                else if( !isTagOpened ) {
                    if( this.type == XmlNode.XML_NODE ) {
                        if( this.text == "" ) { // Text Node Creation
                            try {
                                xNode = new XmlNode(XmlNode.TEXT_NODE, false);
                            } catch (ex) {
                                throw ex;
                            }
                            this.nodes.push(xNode);
                        }
                    }
                }
//                     this.text += this.text;
                break;
        }
        if( ended ) break;
        previousChar = c;
        this.text += this.text;
    }
}

XmlNode.Stream = "";
XmlNode.iterator = 0;

// Types
XmlNode.TEXT_NODE = 0x01;
XmlNode.XML_NODE = 0x02;

// Errors
XmlNode.XML_EXCEPTION = "INVALID XML";
XmlNode.XML_NODE_NAME_EXCEPTION = "XML NODE NAME INVALID";
XmlNode.ATTRIBUTE_EXCEPTION = "XML ATTRIBUTE NAME INVALID";

// XmlNode.prototype.setName = new function ( newName ) {
//     this.name = newName;
// }

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