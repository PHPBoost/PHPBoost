/**
 * Autobox jQuery plugin - Version: 3.0.1
 * @copyright   &copy; 2005-2020 PHPBoost - 2018 Dumitru Uzun
 * @license     https://www.opensource.org/licenses/mit-license.php
 * @author      Dumitru Uzun
 * @link        https://duzun.me
 * @doc         https://github.com/duzun/jquery.autobox
 * @version     PHPBoost 5.3 - last update: 2020 03 12
 * @since       PHPBoost 5.2 - 2020 03 12
 *
 * Patch
 * 	removal of the map version call
*/

(function (global, factory) {
	typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
	typeof define === 'function' && define.amd ? define('jqueryAutobox', factory) :
	(global.jqueryAutobox = factory());
}(this, (function () { 'use strict';
/**
 *
 * Usage:
 *
 * $().
 *    autobox()        - Adjust Height/Width of all TEXTAREAs in this and it's descendants
 *    autoboxOn(sel)   - Bind Auto Height/Width Adjustment events to matched element, listening on sel elements
 *    autoboxBind()    - Bind Auto Height/Width Adjustment events to all TEXTAREAs in this and it's descendants
 */

/*jshint
    esversion: 6,
    browser: true
*/

/** Patch
	leave map call
*/

var TEXTAREA = 'TEXTAREA';
var autoboxedClass = 'autoboxed';
var namespace = '.dynSiz';

var _events = ['autobox', 'keypress', 'keyup', 'click', 'change', 'focusin', 'cut', 'paste'];

// Constants for internal use
var RESIZE_VERTICAL_FLAG = 1;
var RESIZE_HORIZONTAL_FLAG = 2;

var ROWS_POS = 0;
var COLS_POS = 1;
var HEIGHT_POS = 2;
var WIDTH_POS = 3;
var OVERFLOW_Y_POS = 4;
var OVERFLOW_X_POS = 5;
var RESIZE_POS = 6;

function init($) {
    var cchChkElement;
    var cchChkWidth;
    var cchChkHeight;

    function taMH(h, i) {
        if (!h || (i = parseInt(h, 10)) && i < 18) {
            h = '18px';
        }
        return h;
    }

    function findTEXTAREA(ctx) {
        return ctx.filter(TEXTAREA).add(ctx.find(TEXTAREA));
    }

    function chkSize(s, save) {
        var t = $(s),
            w = t.outerWidth(),
            h = t.outerHeight();
        s = t.get(0);
        if (save) {
            if (cchChkElement && cchChkElement !== s) {
                chkSize(cchChkElement);
            }
            cchChkWidth = w;
            cchChkHeight = h;
            cchChkElement = s;
        } else {
            if (cchChkElement === s) {
                if (cchChkHeight != h || cchChkWidth != w) {
                    t.trigger('resize');
                    cchChkWidth = w;
                    cchChkHeight = h;
                    return true;
                }
            }
        }
    }

    function taBoxAdj() {
        var t = this,
            o = $(t),
            d = o.data(),
            e = d._ab_origs,
            s = t.style,
            ol = o.val(),
            v = ol.split('\n'),
            ar = o.prop('rows'),
            ac = o.prop('cols'),
            c = 0,
            r,
            i,
            l;

        chkSize(o, true);

        for (i = 0, r = v.length; i < r; i++) {
            if ((l = v[i].length) > c) {
                c = l;
            }
        }

        // On first call, backup original metric properties
        if (!e) {
            // Can't init when hidden - all metrics are zero
            if (o.is(':hidden')) {
                return;
            }
            o.stop(true);
            e = d._ab_origs = [ar, ac, s.height || o.css('height'), s.width || o.css('width'), o.css('overflow-y'), o.css('overflow-x'), o.css('resize')];
            e.aw = o.attr('width');
            e.ah = o.attr('height');
            i = 0;
            if (!e.ah) {
                i |= RESIZE_VERTICAL_FLAG;
            }
            if (!e.aw) {
                i |= RESIZE_HORIZONTAL_FLAG;
            }

            // use ar
            if (i === 0 || i === (RESIZE_VERTICAL_FLAG | RESIZE_HORIZONTAL_FLAG)) {
                switch (o.prop('resize') || o.attr('resize') || e[RESIZE_POS]) {
                    case 'vertical':
                        i = RESIZE_VERTICAL_FLAG;break;
                    case 'horizontal':
                        i = RESIZE_HORIZONTAL_FLAG;break;
                }
            }
            if (e.ar = i) {
                var css = { resize: 'none' };
                if (i === RESIZE_VERTICAL_FLAG) {
                    css['overflow-y'] = 'hidden';
                    !e.aw && (e.aw = e[WIDTH_POS]);
                    delete e.ah;
                }
                if (i === RESIZE_HORIZONTAL_FLAG) {
                    css['overflow-x'] = 'hidden';
                    !e.ah && (e.ah = e[HEIGHT_POS]);
                    delete e.aw;
                }
                o.css(css);
            }
            // Ensure data is saved
            o.data('_ab_origs', e);
        }

        // Not first call
        else {
                e = d._ab_origs;
                delete e.rest;
            }

        ol = ol.length;
        v = e.ah || 'auto';
        l = e.aw || 'auto';
        e.nadj = ~e.ar & RESIZE_VERTICAL_FLAG;

        if (!c) {
            if (r <= 1) {
                r = e[ROWS_POS];
                v = e[HEIGHT_POS];
                e.nadj = true;
            } else {
                r++;
            }
            c = e[COLS_POS];
            l = e[WIDTH_POS];
            e.ar & RESIZE_VERTICAL_FLAG && o.prop('rows', r);
        } else {
            c += 5 + (c >> 4);
            r += ar > 2 || r > 1;
            (r > ar || ol < e.tl && e.ar & RESIZE_VERTICAL_FLAG) && o.prop('rows', r);
        }
        e.ar & RESIZE_HORIZONTAL_FLAG && o.prop('cols', c).prop('size', c);
        o.css({ 'height': taMH(v), 'width': l });
        e.tl = ol;

        function adjRows() {
            if (!o.data('_ab_origs')) return;
            ar = t.rows;
            var s = t.scrollHeight,
                h = t.offsetHeight,
                d = 0,
                a = s - h,
                ih = h,
                ir = ar;
            for (; d != a && s && h;) {
                // if(d == a || !s || !h) break;
                d = a;
                if (a > 0) {
                    o.prop('rows', Math.max(++ar, (s * ar / h >> 0) - 1, r));
                    s = t.scrollHeight;
                    h = t.offsetHeight;
                    a = s - h;
                    // If rows changed but height not, seems there is some limitation on height (ex max-height)
                    if (ir != t.rows && ih == h) {
                        o.css('overflow-y', '');
                        o.prop('rows', ir);
                        break;
                    }
                }
            }
            // if need to adjust height and it changed, try to change it after a delay
            if (a > 5 && ih != h) setTimeout(adjRows, 16);

            chkSize(o);
        }

        e.nadj ? chkSize(o) : adjRows();
    }

    function taRestoreBox(e) {
        var o = $(this),
            d = e.data;
        if (e = o.data('_ab_origs')) {
            e.rest = true;
            setTimeout(function () {
                if (!e.rest) return;
                chkSize(o, true);
                o.removeData('_ab_origs').prop('rows', e[ROWS_POS]) // for textarea
                .prop('cols', e[COLS_POS]) // for textarea
                .prop('size', e[COLS_POS]) // for input
                .css({
                    'overflow-y': e[OVERFLOW_Y_POS],
                    'overflow-x': e[OVERFLOW_X_POS],
                    'resize': e[RESIZE_POS]
                });
                e = {
                    'height': taMH(e[HEIGHT_POS]),
                    'width': e[WIDTH_POS]
                };

                if (d.speed) {
                    o.animate(e, d.speed, function () {
                        chkSize(o);
                    });
                } else {
                    chkSize(o.css(e));
                }
            }, d.delay || 250); // bigger delay to allow for clicks on element beneath textarea
        }
    }

    function autoBox() {
        var o = findTEXTAREA(this);
        o.each(taBoxAdj);
        return this;
    }

    function autoboxBind(s) {
        var o = findTEXTAREA(this);
        s = $.extend({}, $.autobox.options, s);
        o.addClass(autoboxedClass).off(namespace);
        $.each(_events, function (i, e) {
            o.on(e + namespace, taBoxAdj);
        });
        if (!s.permanent) {
            o.on('blur' + namespace, s, taRestoreBox);
        }
        return this;
    }

    function autoBoxOn(sel, s) {
        var o = this;
        s = $.extend({}, $.autobox.options, s);
        sel || (sel = TEXTAREA);
        o.off(namespace, sel).addClass(autoboxedClass).on(_events.join(namespace + ' ') + namespace, sel, taBoxAdj);

        if (!s.permanent) {
            o.on('blur' + namespace + ' ' + 'focusout' + namespace, sel, s, taRestoreBox);
        }

        return o;
    }

    // Export:

    // Collection methods.
    $.fn.autobox = autoBox;
    $.fn.autoboxOn = autoBoxOn;
    $.fn.autoboxBind = autoboxBind;

    // Alias
    $.fn.bindAutobox = autoboxBind;

    // Static method.
    $.autobox = function (elements, options) {
        // Override default options with passed-in options.
        options = $.extend({}, $.autobox.options, options);
        // Return something awesome.
        return $(elements).call(autoBox);
    };

    // Static method default options.
    $.autobox.options = {
        permanent: false
    };

    // Custom selector.
    $.expr[':'].autobox = function (elem) {
        // Is this element awesome?
        return $(elem).hasClass(autoboxedClass);
    };
}

if (typeof window !== 'undefined') {
    var $ = window.jQuery || window.Zepto;
    if ($) init($);
}

return init;

})));
