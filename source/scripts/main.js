jQuery(document).ready(function($) {

    var offcanvas, login;

    $('.link-current').click(function() {
        $('.breadcrumb').toggleClass('open');
    })

    $('.link-title').click(function() {
        $('.breadcrumb').toggleClass('open');
    })
    $('.spoiler_head').click(function() {

        if ($(this).next('.spoiler_body').hasClass('active')) {

            $(this).next('.spoiler_body').slideUp();
            $(this).next('.spoiler_body').removeClass('active');
            $(this).html('Show content');

        } else {
            $(this).next('.spoiler_body').slideDown();
            $(this).next('.spoiler_body').addClass('active');
            $(this).html('Hide content');
        }
    })


    $('.modifybutton').click(function() {
        setTimeout(function() {
            loadQuickEdit();
        }, 300)

    });

		// calendar
		HideEmptyDays();

    function loadQuickEdit() {
        $('#quick_edit_body_container .button--accept').click(function() {
            $('.modified-pre-text').addClass('is-hidden');
            $('.modified').removeClass('is-hidden');
        });
    }

    $('[data-openChild]').click(function(e) {
        e.preventDefault();
        var element = $(this).data('openchild');

        $(this).find('.icon-up-open').toggleClass('open');

        $('[data-childOf=' + element + ']').slideToggle();
    })

    $('.js-recent-time').each(function(k, v) {
        var options = {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            month: 'short',
            day: 'numeric'
        };
        date = new Date($(v).data('timestamp') * 1000).toLocaleDateString('en-GB', options);

        $(v).html(date);
    })

    $('.js-offcanvas__toggle').click(function() {

        offcanvas = true;

        $('.offcanvas').toggleClass('offcanvas--right');
        $('html').toggleClass('is-offcanvas');
        $('body').toggleClass('noscroll');
        $('.js-overlay').fadeToggle();
    })

    $('.js-overlay').click(function() {

        if (login) {
            $('.js-login').fadeToggle();
            $('.js-overlay').fadeToggle();
            $('body').toggleClass('noscroll');

        }

        if (offcanvas) {
            $('.offcanvas').toggleClass('offcanvas--right');
            $('html').toggleClass('is-offcanvas');
            $('body').toggleClass('noscroll');
            $('.js-overlay').fadeToggle();

            offcanvas = false;
        }

    })

    $('.js-login__toggle').click(function() {

        login = true;

        $('.js-login').fadeToggle();
        $('.js-overlay').fadeToggle();
        $('body').toggleClass('noscroll');



    })

    $('.offcanvas .has-children a').click(function(e) {

        if ($(this).hasClass('icon-up-open'))
            e.preventDefault();
        $(this).next('ul').slideToggle(299);
        if ($(this).hasClass('open')) {

            element = $(this);

            setTimeout(function(el) {
                $(element).toggleClass('open');
            }, 300);
        } else {
            $(this).toggleClass('open');
        }


    })

    $('.js-register').click(function() {
        location.href = "/?action=register";
    })

    var bLazy = new Blazy({
        selector: '.article_inner img', // article images
        offset: 300,
        success: function(ele) {
            $(ele).parent().css('background-image', 'none');
            $(ele).addClass('fpsc');
            $(ele).removeClass('loading');
        }
    });

    // Script for getting realm ranks

    $.ajax({
        url: "/getRealmRank.php",
        method: "GET",
    }).done(function(msg) {
        var str = jQuery.parseJSON(msg);

        if (typeof $('.js-realmrank').html() !== 'undefined')
            $('.js-realmrank').html('#' + str.realm_rank);
        if (typeof $('.js-arearank').html() !== 'undefined')
            $('.js-arearank').html('#' + str.area_rank);
        if (typeof $('.js-worldrank').html() !== 'undefined')
            $('.js-worldrank').html('#' + str.world_rank);

        $('.js-ranks').fadeIn(600);

    });



    $('#postMoreExpandLink').click(function() {
        $(this).toggleClass('open');
        $(this).toggleClass('closed');
    })
})



/*!
  hey, [be]Lazy.js - v1.6.2 - 2016.05.09
  A fast, small and dependency free lazy load script (https://github.com/dinbror/blazy)
  (c) Bjoern Klinggaard - @bklinggaard - http://dinbror.dk/blazy
*/
;
(function(root, blazy) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register bLazy as an anonymous module
        define(blazy);
    } else if (typeof exports === 'object') {
        // Node. Does not work with strict CommonJS, but
        // only CommonJS-like environments that support module.exports,
        // like Node.
        module.exports = blazy();
    } else {
        // Browser globals. Register bLazy on window
        root.Blazy = blazy();
    }
})(this, function() {
    'use strict';

    //private vars
    var _source, _viewport, _isRetina, _attrSrc = 'src',
        _attrSrcset = 'srcset';

    // constructor
    return function Blazy(options) {
        //IE7- fallback for missing querySelectorAll support
        if (!document.querySelectorAll) {
            var s = document.createStyleSheet();
            document.querySelectorAll = function(r, c, i, j, a) {
                a = document.all, c = [], r = r.replace(/\[for\b/gi, '[htmlFor').split(',');
                for (i = r.length; i--;) {
                    s.addRule(r[i], 'k:v');
                    for (j = a.length; j--;) a[j].currentStyle.k && c.push(a[j]);
                    s.removeRule(0);
                }
                return c;
            };
        }

        //options and helper vars
        var scope = this;
        var util = scope._util = {};
        util.elements = [];
        util.destroyed = true;
        scope.options = options || {};
        scope.options.error = scope.options.error || false;
        scope.options.offset = scope.options.offset || 100;
        scope.options.success = scope.options.success || false;
        scope.options.selector = scope.options.selector || '.b-lazy';
        scope.options.separator = scope.options.separator || '|';
        scope.options.container = scope.options.container ? document.querySelectorAll(scope.options.container) : false;
        scope.options.errorClass = scope.options.errorClass || 'b-error';
        scope.options.breakpoints = scope.options.breakpoints || false; // obsolete
        scope.options.loadInvisible = scope.options.loadInvisible || false;
        scope.options.successClass = scope.options.successClass || 'b-loaded';
        scope.options.validateDelay = scope.options.validateDelay || 25;
        scope.options.saveViewportOffsetDelay = scope.options.saveViewportOffsetDelay || 50;
        scope.options.srcset = scope.options.srcset || 'data-srcset';
        scope.options.src = _source = scope.options.src || 'data-src';
        _isRetina = window.devicePixelRatio > 1;
        _viewport = {};
        _viewport.top = 0 - scope.options.offset;
        _viewport.left = 0 - scope.options.offset;


        /* public functions
         ************************************/
        scope.revalidate = function() {
            initialize(this);
        };
        scope.load = function(elements, force) {
            var opt = this.options;
            if (elements.length === undefined) {
                loadElement(elements, force, opt);
            } else {
                each(elements, function(element) {
                    loadElement(element, force, opt);
                });
            }
        };
        scope.destroy = function() {
            var self = this;
            var util = self._util;
            if (self.options.container) {
                each(self.options.container, function(object) {
                    unbindEvent(object, 'scroll', util.validateT);
                });
            }
            unbindEvent(window, 'scroll', util.validateT);
            unbindEvent(window, 'resize', util.validateT);
            unbindEvent(window, 'resize', util.saveViewportOffsetT);
            util.count = 0;
            util.elements.length = 0;
            util.destroyed = true;
        };

        //throttle, ensures that we don't call the functions too often
        util.validateT = throttle(function() {
            validate(scope);
        }, scope.options.validateDelay, scope);
        util.saveViewportOffsetT = throttle(function() {
            saveViewportOffset(scope.options.offset);
        }, scope.options.saveViewportOffsetDelay, scope);
        saveViewportOffset(scope.options.offset);

        //handle multi-served image src (obsolete)
        each(scope.options.breakpoints, function(object) {
            if (object.width >= window.screen.width) {
                _source = object.src;
                return false;
            }
        });

        // start lazy load
        setTimeout(function() {
            initialize(scope);
        }); // "dom ready" fix

    };


    /* Private helper functions
     ************************************/
    function initialize(self) {
        var util = self._util;
        // First we create an array of elements to lazy load
        util.elements = toArray(self.options.selector);
        util.count = util.elements.length;
        // Then we bind resize and scroll events if not already binded
        if (util.destroyed) {
            util.destroyed = false;
            if (self.options.container) {
                each(self.options.container, function(object) {
                    bindEvent(object, 'scroll', util.validateT);
                });
            }
            bindEvent(window, 'resize', util.saveViewportOffsetT);
            bindEvent(window, 'resize', util.validateT);
            bindEvent(window, 'scroll', util.validateT);
        }
        // And finally, we start to lazy load.
        validate(self);
    }

    function validate(self) {
        var util = self._util;
        for (var i = 0; i < util.count; i++) {
            var element = util.elements[i];
            if (elementInView(element) || hasClass(element, self.options.successClass)) {
                self.load(element);
                util.elements.splice(i, 1);
                util.count--;
                i--;
            }
        }
        if (util.count === 0) {
            self.destroy();
        }
    }

    function elementInView(ele) {
        var rect = ele.getBoundingClientRect();
        return (
            // Intersection
            rect.right >= _viewport.left && rect.bottom >= _viewport.top && rect.left <= _viewport.right && rect.top <= _viewport.bottom
        );
    }

    function loadElement(ele, force, options) {
        // if element is visible, not loaded or forced
        if (!hasClass(ele, options.successClass) && (force || options.loadInvisible || (ele.offsetWidth > 0 && ele.offsetHeight > 0))) {
            var dataSrc = ele.getAttribute(_source) || ele.getAttribute(options.src); // fallback to default 'data-src'
            if (dataSrc) {
                var dataSrcSplitted = dataSrc.split(options.separator);
                var src = dataSrcSplitted[_isRetina && dataSrcSplitted.length > 1 ? 1 : 0];
                var isImage = equal(ele, 'img');
                // Image or background image
                if (isImage || ele.src === undefined) {
                    var img = new Image();
                    // using EventListener instead of onerror and onload
                    // due to bug introduced in chrome v50
                    // (https://productforums.google.com/forum/#!topic/chrome/p51Lk7vnP2o)
                    var onErrorHandler = function() {
                        if (options.error) options.error(ele, "invalid");
                        addClass(ele, options.errorClass);
                        unbindEvent(img, 'error', onErrorHandler);
                        unbindEvent(img, 'load', onLoadHandler);
                    };
                    var onLoadHandler = function() {
                        // Is element an image
                        if (isImage) {
                            setSrc(ele, src); //src
                            handleSource(ele, _attrSrcset, options.srcset); //srcset
                            //picture element
                            var parent = ele.parentNode;
                            if (parent && equal(parent, 'picture')) {
                                each(parent.getElementsByTagName('source'), function(source) {
                                    handleSource(source, _attrSrcset, options.srcset);
                                });
                            }
                            // or background-image
                        } else {
                            ele.style.backgroundImage = 'url("' + src + '")';
                        }
                        itemLoaded(ele, options);
                        unbindEvent(img, 'load', onLoadHandler);
                        unbindEvent(img, 'error', onErrorHandler);
                    };
                    bindEvent(img, 'error', onErrorHandler);
                    bindEvent(img, 'load', onLoadHandler);
                    setSrc(img, src); //preload
                } else { // An item with src like iframe, unity, simpelvideo etc
                    setSrc(ele, src);
                    itemLoaded(ele, options);
                }
            } else {
                // video with child source
                if (equal(ele, 'video')) {
                    each(ele.getElementsByTagName('source'), function(source) {
                        handleSource(source, _attrSrc, options.src);
                    });
                    ele.load();
                    itemLoaded(ele, options);
                } else {
                    if (options.error) options.error(ele, "missing");
                    addClass(ele, options.errorClass);
                }
            }
        }
    }

    function itemLoaded(ele, options) {
        addClass(ele, options.successClass);
        if (options.success) options.success(ele);
        // cleanup markup, remove data source attributes
        ele.removeAttribute(options.src);
        each(options.breakpoints, function(object) {
            ele.removeAttribute(object.src);
        });
    }

    function setSrc(ele, src) {
        ele[_attrSrc] = src;
    }

    function handleSource(ele, attr, dataAttr) {
        var dataSrc = ele.getAttribute(dataAttr);
        if (dataSrc) {
            ele[attr] = dataSrc;
            ele.removeAttribute(dataAttr);
        }
    }

    function equal(ele, str) {
        return ele.nodeName.toLowerCase() === str;
    }

    function hasClass(ele, className) {
        return (' ' + ele.className + ' ').indexOf(' ' + className + ' ') !== -1;
    }

    function addClass(ele, className) {
        if (!hasClass(ele, className)) {
            ele.className += ' ' + className;
        }
    }

    function toArray(selector) {
        var array = [];
        var nodelist = document.querySelectorAll(selector);
        for (var i = nodelist.length; i--; array.unshift(nodelist[i])) {}
        return array;
    }

    function saveViewportOffset(offset) {
        _viewport.bottom = (window.innerHeight || document.documentElement.clientHeight) + offset;
        _viewport.right = (window.innerWidth || document.documentElement.clientWidth) + offset;
    }

    function bindEvent(ele, type, fn) {
        if (ele.attachEvent) {
            ele.attachEvent && ele.attachEvent('on' + type, fn);
        } else {
            ele.addEventListener(type, fn, false);
        }
    }

    function unbindEvent(ele, type, fn) {
        if (ele.detachEvent) {
            ele.detachEvent && ele.detachEvent('on' + type, fn);
        } else {
            ele.removeEventListener(type, fn, false);
        }
    }

    function each(object, fn) {
        if (object && fn) {
            var l = object.length;
            for (var i = 0; i < l && fn(object[i], i) !== false; i++) {}
        }
    }

    function throttle(fn, minDelay, scope) {
        var lastCall = 0;
        return function() {
            var now = +new Date();
            if (now - lastCall < minDelay) {
                return;
            }
            lastCall = now;
            fn.apply(scope, arguments);
        };
    }

});

// Gallery
function ShowSearchBox() {
    $('#gallery-search-button').addClass("hidden");

    var searchForm = $('#gallery-search-form');
    searchForm.removeClass("hidden");
    searchForm.find("input").focus();
}

function HideSearchBox() {
    var searchForm = $('#gallery-search-form');
    searchForm.addClass("hidden");
    searchForm.find("input").val('');

    $('#gallery-search-button').removeClass("hidden");
}

// calendar
function HideEmptyDays() {
    var days = $("#calendar").find("td.windowbg.days");

    days.each(function(day) {
        var element = $(days[day]);
        var hasContents = element.html().trim() ? true : false;

        if (hasContents) element.addClass("show-border");
    });
}

// Breadcrumbs
function ExpandCrumbs() {
  var breadcrumbs = $(".breadcrumb").find("li");
  var delay = 0
  $(".breadcrumb").next('.link-current').hide();

  breadcrumbs.each(function(idx) {
    var item = breadcrumbs[idx];
    var itemType = item.hasClass('link-previous');
    if (itemType) {
      $( item ).delay( delay ).fadeIn(800);
      delay += 50;
    }
  });

  $(".breadcrumb").next('.link-current').fadeIn();

}
