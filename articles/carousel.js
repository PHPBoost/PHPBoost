/* Carousel.js v0.1b - <http://andydust.com/projects/carouseljs/>
 *
 * Copyright (c) 2009 Andy Dust <http://wwww.andydust.com/>
 * Released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 */ 

var CarouselJs = function(elementId, options) {
  var Utils;
  var Button;
  var Carousel = function(elementId, options) {

    var listId = elementId;
    var suppliedOptions = options || {};
    this.components = {};
    this.baseInterval = null;
    this.queueInterval = null;
    this.defaultSegment = null;

    function determineOptions(suppliedOptions) {
      var so = suppliedOptions;
      var options = {
        carouselId:	    so.carouselId	|| null,
        pauseInterval:      so.pauseInterval    || 0.2,
        fps:                so.fps              || 33,
        speed:              so.speed            || 20,
        disableAnimation:   so.disableAnimation || false,
        disableSkipping:    so.disableSkipping  || false,
        defaultItemClass:   so.defaultItemClass || null
      };
      return options;
    }

    function determineCarouselWidth() {
       return this.listContainer.offsetWidth - Utils.GetOffsetWidth(this.components.leftScroller.element) - Utils.GetOffsetWidth(this.components.rightScroller.element);
    }

    function getSegmentData() {
      var carouselWidth = determineCarouselWidth.call(this);
      var itemWidth = Utils.GetOffsetWidth(this.listContainer.items[0]);
      if(itemWidth > carouselWidth) {
        throw "CarouselJs: listItem width (" + itemWidth + ") cannot be larger than UL/OL width ("+ carouselWidth +" after taking into account button width). Please tweak your CSS."; 
      }
      if(itemWidth <= 0) {
        throw "CarouselJs: listItem width cannot be less than or equal to 0";
      }
      return {
        carouselWidth:    carouselWidth,
        containerWidth:   this.listContainer.offsetWidth,
        containerHeight:  this.listContainer.offsetHeight,
        itemWidth:        itemWidth,
        itemsPerSegment:  Math.floor(carouselWidth / this.listContainer.items[0].offsetWidth),
        itemCount:        this.listContainer.items.length,
        segmentCount:     Math.ceil(this.listContainer.items.length / Math.floor(carouselWidth / this.listContainer.items[0].offsetWidth))
      };
    }

    function createContainer(childNode,containerId) {
      var container = document.createElement('div');
      container.className = "carouseljs";
      if(containerId) {
       container.setAttribute('id', containerId);
      }
      var e = childNode.parentNode.insertBefore(container, childNode);
      e.appendChild(childNode);
      return e;
    }

    function createComponents(container) {
      if(!this.options.disableSkipping) {
        var buttonTitle = [];
        buttonTitle.push("Double-click to skip to beginning.");
        buttonTitle.push("Double-click to skip to end.");
      }

      var leftScroller = new Button('cjs-left', buttonTitle ? buttonTitle[0] : null);
      leftScroller.eventBehaviour = {
        mouseDown: this.scroll,
        mouseUp: function() {},
        doubleClick: this.skipTo
      };

      var rightScroller = new Button('cjs-right', buttonTitle ? buttonTitle[1] : null);
      rightScroller.eventBehaviour = {
        mouseDown: this.scroll,
        mouseUp: function() {},
        doubleClick: this.skipTo
      };

      leftScroller.setLabel("previous");
      rightScroller.setLabel("next");

      leftScroller.enable(this);
      rightScroller.enable(this);

      container.insertBefore(leftScroller.element, container.firstChild);
      container.appendChild(rightScroller.element);

      return {
        leftScroller: leftScroller,
        rightScroller: rightScroller,
        scrollers: [leftScroller, rightScroller]
      };
    }

    function SetLayout() {
      // the list (ol/ul) element
      Utils.SetStyle.call(this.listContainer, "width", (this.data.itemWidth * this.data.itemCount));
      Utils.SetStyle.call(this.listContainer, "position", "absolute");
      Utils.SetStyle.call(this.listContainer, "left", this.data.startOffset);
      
      // the container div
      Utils.SetStyle.call(this.container, "height", this.listContainer.offsetHeight);
      Utils.SetStyle.call(this.container, "width", this.data.containerWidth);
      Utils.SetStyle.call(this.container, "overflow", "hidden");
      Utils.SetStyle.call(this.container, "position", "relative");

      // the scroll buttons
      for(var i = 0; i < this.components.scrollers.length; i++) {
        Utils.SetStyle.call(this.components.scrollers[i].element, "position", "absolute");
        Utils.SetStyle.call(this.components.scrollers[i].element, "zIndex", "2");
      }
      Utils.SetStyle.call(this.components.leftScroller.element, "left", 0);
      Utils.SetStyle.call(this.components.rightScroller.element, "right", 0);

    }

    function createSegments() {
      var it = this.listContainer.items;
      var ps = this.data.itemsPerSegment;
      var segmentArray = [];
      var index;
      var segmentX;

      for(var i = 0; i < this.data.segmentCount; i++) {
        index = i * ps;
        var itemOffset = i * ps + ps;
        if(this.defaultItemIndex && this.defaultItemIndex >= index && this.defaultItemIndex <= itemOffset) {
          this.defaultSegmentIndex = i;
        }
        segmentX = this.data.startOffset - index *  this.data.itemWidth;
        segmentArray.push({offset: segmentX, items: it.slice(it[index], itemOffset)});
      }

      return segmentArray;
    }

    function getDefaultItem(clazz) {
      for(var i = 0; i < this.length; i++) {
        if(this[i].className.match(clazz)) { return i; }
      }
    }

    this.options = determineOptions(suppliedOptions);
    this.listContainer = document.getElementById(listId);
    this.listContainer.items = Utils.GetImmediateTagDescendants(this.listContainer, ["LI"]);

    if(this.options.defaultItemClass) {
      this.defaultItemIndex = getDefaultItem.call(this.listContainer.items, this.options.defaultItemClass);
    }

    this.container = createContainer(this.listContainer, this.options.carouselId);

    this.components = createComponents.call(this,this.container);

    this.data = getSegmentData.call(this);
    this.data.startOffset = Utils.GetOffsetWidth(this.components.leftScroller.element);

    this.segments = createSegments.call(this);
    this.currentSegmentIndex = 0;
    this.targetSegmentIndex = 0;

    this.activeScroller = null;
    SetLayout.call(this);

    if(this.defaultItemIndex) {
      this.goToSegment(this.defaultSegmentIndex);
    } else {
      this.goToSegment(0);
    }

  };

  Carousel.prototype.goToSegment = function(segIndex) {
    var segment = this.segments[segIndex];
    if(segment) {
      Utils.SetStyle.call(this.listContainer, "left", segment.offset);
      this.currentSegmentIndex = segIndex;
      this.buttonCheck();
    }
  };


  Carousel.prototype.slideToSegment = function(segIndex, direction) {
    var c = this.listContainer;
    var s = this.options.speed;
    var left = parseInt(c.style.left, 10);
    var candidateLeft;
    var obj = this;
    var d = direction;
    var targetPx;
    var segment = this.segments[segIndex];
    var segmentIndex = segIndex;
    var i = 0;
    var tweenFactor;
    var cleanUp;

    function shiftTo() {
      if(left != targetPx) {
        candidateLeft = (left + (0-d) * (Math.max(s - Math.floor(Math.pow(Math.E , (i * 1.8)/tweenFactor)), 1)));

        if(d > 0) {
          left = candidateLeft < targetPx ? targetPx : candidateLeft;
        } else {
          left = candidateLeft > targetPx ? targetPx : candidateLeft;
        }
        c.style.left = left + "px";
      } else {
        cleanUp();
      }
      i++;
    }

    cleanUp = function() {
      clearInterval(obj.baseInterval);
      obj.baseInterval = null;
      obj.currentSegmentIndex = segmentIndex;
      obj.buttonCheck();
      if(obj.activeScroller.mouseState == 1) {
        if(obj.options.pauseInterval > 0) {
          obj.queueInterval = setInterval( function() { obj.queueSlide(obj.activeScroller); }, obj.options.pauseInterval * 1000);
        } else {
          obj.queueSlide(obj.activeScroller);
        }
      }
    };

    if(segment) {
      targetPx = segment.offset;
      tweenFactor = (Math.abs(left - targetPx) / 100) * 4.25;
      obj.baseInterval = setInterval(function() { shiftTo(targetPx); }, this.options.fps);
    }

  };

  Carousel.prototype.queueSlide = function(button) {
    clearInterval(this.queueInterval);
    if(!this.baseInterval && button.mouseState == 1) {
      var d = button.element._name == "cjs-right" ? 1 : -1;
      this.targetSegmentIndex = this.currentSegmentIndex + d;
      this.slideToSegment(this.targetSegmentIndex, d);
    }
  };

  Carousel.prototype.scroll = function(obj, e, button) {
    if(!obj.baseInterval) {
      obj.activeScroller = button;
      var d = button.element._name == "cjs-right" ? 1 : -1;
      obj.targetSegmentIndex = obj.currentSegmentIndex + d;
      if(obj.options.disableAnimation) {
        obj.goToSegment(obj.targetSegmentIndex);
      } else {
        obj.slideToSegment(obj.targetSegmentIndex, d);
      }
    }
  };

  Carousel.prototype.skipTo = function(obj, e, button) {
    if(!obj.options.disableSkipping) {
      obj.killAll();    
      var segment = button.element._name == "cjs-right" ? obj.segments.length - 1 : 0;
      obj.goToSegment(segment);
    }
  };

  Carousel.prototype.killAll = function() {
    clearInterval(this.baseInterval);
    clearInterval(this.queueInterval);
    this.baseInterval = null;
    this.queueInterval = null;
  };

  Carousel.prototype.buttonCheck = function() {

    if(this.currentSegmentIndex === 0) {
      this.components.leftScroller.disable(this);
    } else if(!this.components.leftScroller.enabled) {
      this.components.leftScroller.enable(this);
    }
    if(this.currentSegmentIndex == this.segments.length - 1) {
      this.components.rightScroller.disable(this);
    } else if(!this.components.rightScroller.enabled) {
      this.components.rightScroller.enable(this);
    }
  };

  Button = function(name, labelText) {
    this.element = document.createElement('a');
    if(labelText) {
     this.element.setAttribute("title", labelText);
    }
    this.enabled = 0;

    this.element._name = name;
    this.eventBehaviour = null;
    this.mouseState = 0;

    this.element.className = this.element._name;
  };

  Button.prototype.setLabel = function(label) {
    this.element.innerHTML = label;
  };

  Button.prototype.disable = function(parent) {
    var thisButton = this;
    this.element.onmousedown = null;
    this.element.onmouseup   = null;
    this.element.ondblclick  = null;
    this.element.className = this.element._name + " cjs-disabled";
    this.enabled = 0;
  };

  Button.prototype.enable = function(parent) {
    var thisButton = this;
    var p = parent;
    this.element.onmousedown = function(e) { thisButton.mouseState = 1; thisButton.eventBehaviour.mouseDown(p, e, thisButton); };
    this.element.onmouseup   = function(e) { thisButton.mouseState = 0; thisButton.eventBehaviour.mouseUp(p, e, thisButton); };
    this.element.ondblclick  = function(e) { thisButton.mouseState = 0; thisButton.eventBehaviour.doubleClick(p, e, thisButton); };
    this.element.className = this.element._name + " cjs-enabled";
    this.enabled = 1;
  };

  Utils = (function() {

    function ArrayIndex(value) {
      for(var i = 0; i < this.length; i++) {
        if(this[i] == value) { return i; }
      }
      return -1;
    }

    function ArrayInclude(value) {
      return (ArrayIndex.call(this, value) >= 0);
    }

    function SetStyle(prop, value, unit) {
      var v = value;
      if(!unit) {
        if(ArrayInclude.call(["width", "height", "margin", "padding", "left", "right"], prop)) {
          v += "px";
        }
      } else {
        v+= unit;
      }
      this.style[prop] = v;
    }

    function GetOffsetWidth(element) {
      var e = element;
      var width = 0;
      var w;

      var props = ["width",
                   "paddingLeft",
                   "paddingRight",
                   "borderLeftWidth",
                   "borderRightWidth",
                   "marginLeft",
                   "marginRight"];

      for(var i = 0; i < props.length; i++) {
        var c = props[i];
        if(e.style[c]) {
          width += parseInt(e.style[c],10);
        } else if(e.currentStyle) {
          w = parseInt(e.currentStyle[c],10);
          if(!isNaN(w)) { width += w; }
        } else if(document.defaultView && document.defaultView.getComputedStyle) {
          c = c.replace(/([A-Z])/g,"-$1");
          c = c.toLowerCase();
          width += parseInt(document.defaultView.getComputedStyle(e,"").getPropertyValue(c),10);
        } else {
          return null;
        }
      }
      return width;
    }

    function GetImmediateTagDescendants(parentElement, tagTypeArray) {
      var items = [];
      var t = tagTypeArray;
      var e = parentElement;
      for(var i = 0; i < e.childNodes.length; i++) {
        var c = e.childNodes[i];
        if(c.nodeType == 1 && ArrayInclude.call(t, c.tagName)) {
          items.push(c);
        }
      }
      return items;
    }

    return {
      GetImmediateTagDescendants:GetImmediateTagDescendants,
      ArrayInclude:ArrayInclude,
      SetStyle:SetStyle,
      GetOffsetWidth:GetOffsetWidth
    };
  })();
  (new Carousel(elementId, options));
};

