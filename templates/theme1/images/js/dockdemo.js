<!--
/*******************************************************************************
 'Dock' style enlarging and reducing of DOM elements.

 Copyright (C) Nick Jarman 2005. All rights reserved.
*******************************************************************************/

// Parameters which affect the display.
var interval = 10; // How often the sizes of the items are adjusted
 // (msec? - a setting of 10 seems to work OK,
 // though it seems quite fast)
var enlargeSteps = 20; // Number of steps used to enlarge an element
var enlargeStepSize = 3; // How many pixels are added to the width of an
 // element in each enlargement step.
var reduceStepSize = 1; // How many pixels are subtracted from the width
 // of an element in each reduction step. Take
 // care to ensure that this is divisible by
 // enlargeSteps * enlargeStepSize.

// A few globals.
var timeout = null;
var enlargeElement = -1;
var dockElements = new Array();


/*******************************************************************************
 enlarge - call this function in your element's OnMouseOver event.
*******************************************************************************/
function enlarge(element)
{
 // Check to see if this element is already known to us. If it is, it's
 // currently being reduced. Setting enlargeElement (see below) will stop
 // this happening, and cause it to be enlarged.
 elementIndex = findDockElement(element);

 // It's not known to us, so we must note a few details, wrap them in an
 // instance of DockElement.
 if (-1 == elementIndex)
 {
 dockElements[dockElements.length] = new DockElement(element, element.width, element.width + (enlargeStepSize * enlargeSteps));
 elementIndex = dockElements.length - 1;
 }

 // Now we know which item in the dockElements array is the one to be
 // enlarged. Note that only one item at a time can be enlarged (the one
 // under the user's pointer), but many can be reduced.
 enlargeElement = elementIndex

 // If there's no timeout set up, do so now.
 if (null == timeout)
 {
 timeout = setTimeout("doResize()", interval);
 }
}


/*******************************************************************************
 enlarge - call this function in your element's OnMouseOut event.
*******************************************************************************/
function reduce(element)
{
 // Ensure that the element which is asking to be reduced is the one which
 // is currently being enlarged...
 if (enlargeElement == findDockElement(element))
 {
 // ...it is, so we indicate that no elements are being enlarged.
 enlargeElement = -1;
 }

 // If there's no timeout set up, do so now.
 if (null == timeout)
 {
 timeout = setTimeout("doResize()", interval);
 }
}


/*******************************************************************************
 doResize - called when the timeout expires. Simply enlarges the element
 registered for enlarging by one step, reduces all other elements by one
 step and checks whether more enlarging or reducing is needed. If it is,
 another timeout is set.
*******************************************************************************/
function doResize()
{
 keepEnlarging = doEnlarge();
 keepReducing = doReduce();

 if (keepEnlarging || keepReducing)
 {
 timeout = setTimeout("doResize()", interval);
 }
 else
 {
 timeout = null;
 }
}


/*******************************************************************************
 doEnlarge - checks if there is an element to be enlarged, and if so, calls
 the enlarge method of the relevant item in the dockElements array. Returns
 a flag to indicate whether further enlargements are necessary.
*******************************************************************************/
function doEnlarge()
{
 keepGoing = false;

 if (-1 != enlargeElement)
 {
 keepGoing = dockElements[enlargeElement].enlarge();
 }

 return keepGoing;
}


/*******************************************************************************
 doReduce - iterates through all items in the dockElements array. For each
 element, providing it is not the one being enlarged, the reduce method is
 called. Returns a flag to indicate whether further reductions are
 necessary.
*******************************************************************************/
function doReduce()
{
 keepGoing = false;
 tidyupNeeded = false;

 for (thisItem = 0; thisItem < dockElements.length; thisItem ++)
 {
 // Don't reduce the item which is being enlarged!
 if (enlargeElement != thisItem)
 {
 if (dockElements[thisItem].reduce())
 {
 keepGoing = true;
 }
 else
 {
 // This element has been reduced back to its original size, so
 // it is no longer necessary to keep it in the dockElements
 // array. Set a flag to indicate it should be removed.
 dockElements[thisItem].remove = true;
 tidyupNeeded = true;
 }
 }
 }

 // If we have marked some items in the dockElements array for removal, now
 // is the time to remove them.
 if (tidyupNeeded)
 {
 tidyDockElements();
 }

 return keepGoing;
}


/*******************************************************************************
 findDockElement - returns the index of the supplied element in the
 dockElements array, or -1 if it does not exist.
*******************************************************************************/
function findDockElement(element)
{
 thisItem = 0;
 found = false;

 while ((thisItem < dockElements.length) && !found)
 {
 if (dockElements[thisItem].element == element)
 {
 found = true;
 }
 else
 {
 thisItem ++;
 }
 }

 return found ? thisItem : -1;
}


/*******************************************************************************
 tidyDockElements - removes all items from the dockElements array whose
 remove flag is set. This is done by copying all items without the flag set
 to a temporary array, then asigning that array back to dockElements. Care
 must be taken to adjust the value of enlargeElement, since it is an index
 into the array. Use of the splice method is avoided for compatibility with
 browsers earlier than IE5.5.
*******************************************************************************/
function tidyDockElements()
{
 var tempArray = new Array();
 var tempEnlarge = -1;

 for (thisItem = 0; thisItem < dockElements.length; thisItem ++)
 {
 if (!dockElements[thisItem].remove)
 {
 tempArray[tempArray.length] = dockElements[thisItem];

 if (thisItem == enlargeElement)
 {
 tempEnlarge = tempArray.length - 1;
 }
 }
 }

 dockElements = tempArray;
 enlargeElement = tempEnlarge;
}


/*******************************************************************************
 DockElement class, encapsulating a single element in the dock. This stores
 the HTML element and its original and maximum sizes. There are methods for
 enlarging and reducing the element's size by one step. An instance of this
 class is added to the dockElements array for each element that the user's
 pointer hovers over, so there's also a flag which is set when this instance
 should be removed from the array.
*******************************************************************************/
function DockElement(element, originalSize, maxSize)
{
 this.element = element;
 this.originalSize = originalSize;
 this.maxSize = maxSize;
 this.remove = false;
 this.enlarge = dockElementEnlarge;
 this.reduce = dockElementReduce;
}


// Enlarge the element by one step, and return a flag indicating whether further
// enlargement steps are possible.
function dockElementEnlarge()
{
 if (this.element.width < this.maxSize)
 {
 this.element.width += enlargeStepSize;
 }

 return (this.element.width < this.maxSize);
}


// Reduce the element by one step, and return a flag indicating whether further
// reduction steps are possible.
function dockElementReduce()
{
 if (this.element.width > this.originalSize)
 {
 this.element.width -= reduceStepSize;
 }

 return (this.element.width > this.originalSize);

}
//-->