/*
 * jQuery SatoshiMori Search Input Selector v1.2
 * http://dot-town-lab.com/laboratory/
 *
 * Copyright 2015 - .dotown-lab
 * Free to use and abuse under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */
(function($){
	var plugin = function (element, options, id) {
		this.element = $(element);
		this.options = $.extend({}, $.fn.smSearchInputSelector.defaults, options);
		this.id = id;
		this.hidden = null;
		this.dropdown = null;
		this.lists = [];
		this.pointer = [];
		this.hasTouch = 'ontouchstart' in window;
		this.is_mouse = false;
		this.is_focus = false;
		this.lastkey = 0;

		this.initial();
	};
	plugin.prototype = {
		initial : function () {
			var t = this;
			t.hidden = t.addhidden();
			t.setevent();
		},
		reset : function (selector) {
			var t = this;
			if(selector != null){
				t.element.val(selector ? selector.name : '');
				t.hidden.val(selector ? selector.value : '');
				t.dropdown.remove();
				t.dropdown = false;
				t.lists = [];
				t.is_mouse = false;
				t.is_focus = false;
				t.options.selected(selector.value);
			}
		},
		setevent : function () {
			var t = this;
			t.element.on('mousedown', t.elementHandler());
			t.element.on('focus', t.elementHandler());
			t.element.on('keydown', t.searchHandler());
			t.element.on('keyup', t.searchHandler());

			// window
			if (t.hasTouch) window.addEventListener("touchstart", t.cancelHandler(), false);
			if (!t.hasTouch) {
				try { window.addEventListener("mousedown", t.cancelHandler(), false); }
				catch (e) { document.attachEvent("onmousedown", t.cancelHandler()); }
			}
		},
		elementHandler : function () {
			var t = this;
			return function (e) {
				switch (e.type) {
					case 'mousedown' :
						t.is_mouse = true;
						t.is_focus = true;
						t.setPointer();
						t.dropdown = t.addDropdown();
						t.setDropdownStyle();
						break;
					case 'focus' :
						if (!t.is_mouse) {
							t.is_focus = true;
							t.setPointer();
							t.dropdown = t.addDropdown();
							t.setDropdownStyle();
						}
						break;
					default : break;
				}
			};
		},
		selectHandler : function (obj) {
			var t = this;
			return function (e) {
				var n = obj.data('num');
				var selector = t.options.selector[n];
				t.reset(selector);
			};
		},
		searchHandler : function () {
			var t = this;
			var TAB_CODE = 9;
			var ENTER_CODE = 13;
			return function (e) {
				if (!t.dropdown) return e.keyCode == ENTER_CODE ? false : true;
				if (e.type == 'keyup') {
					var selector = t.getSearchSelector($(this).val().trim());
					t.dropdown.find('ul').scrollTop(0);
					if (e.keyCode == ENTER_CODE && t.lastkey == ENTER_CODE && selector) {
						t.reset(selector);
					}
				}
				if (e.type == 'keydown') {
					if (e.keyCode == TAB_CODE && t.is_focus) {
						t.reset(t.getSearchSelector($(this).val().trim()));
					}
					t.lastkey = e.keyCode;
					if (e.keyCode == ENTER_CODE) return false;
				}
			};
		},
		cancelHandler : function () {
			var t = this;
			return function (e) {
				if (!t.dropdown) return false;
				var isTarget = false;
				var target = e.target || e.srcElement;
				var parent = null;
				var sisid = 0;
				if (!target) return false;
				if ($(target).data('uniquename') == t.options.uniquename) {
					sisid = parseInt($(target).data('sisid'), 10);
					isTarget = !sisid || sisid == t.id ? true : false;
				} else {
					parent = target.parentNode;
					while (parent.tagName && !isTarget) {
						if ($(parent).hasClass(t.options.classes.thisname)) {
							sisid = parseInt($(parent).data('sisid'), 10);
							isTarget = !sisid || sisid == t.id ? true : false;
						}
						parent = parent.parentNode;
					}
				}
				if (!isTarget) t.reset(t.getSearchSelector());
			};
		},
		addhidden : function () {
			var t = this;
			var elm = $(document.createElement('input'));
			elm.addClass(t.options.classes.thisname+' '+t.options.classes.hidden);
			elm.attr({
				type : 'hidden',
				name : t.element.attr('name')
			});
			elm.val(t.element.data('value'));
			t.element.attr('name', '');
			t.element.data({
				sisid : t.id,
				uniquename : t.options.uniquename
			});
			t.element.after(elm);
			return elm;
		},
		addDropdown : function () {
			var t = this;
			if (t.dropdown) return t.dropdown;
			var value = t.hidden.val();
			var elm = $(document.createElement('div'));
			var str_list = '';
			elm.attr('class', t.options.classes.thisname+' '+t.options.classes.dropdown);
			for (var i=0; i<t.options.selector.length; i++) {
				var obj = t.options.selector[i];
				var selected = value == obj.value ? true : false;
				str_list += '<li'+(selected ? ' class="selected"' : '')+' data-num="'+i+'">'+(obj.name ? obj.name : "&nbsp;")+'</li>';
			}
			elm.html('<div><ul>'+str_list+'</ul></div>');
			elm.find('ul').css('max-height', t.options['max-height']);
			elm.find('li').css('font-size', t.pointer.fs1);
			elm.find('li').each(function(){
				var list = $(this);
				t.lists.push(list);
				list.on('click', t.selectHandler(list));
			});
			$('body').append(elm);
			return elm;
		},
		setDropdownStyle : function () {
			var t = this;
			if (!t.dropdown) return false;
			if (t.dropdown.css('display') == 'none') t.dropdown.css('display', 'block');
			var value = t.hidden.val();
			var wh = $(document).height();
			var dh = t.dropdown.height();
			var offset = wh - (t.pointer.ot + t.pointer.oh);
			var top = dh > offset
				? t.pointer.ot - dh
				: t.pointer.ot + t.pointer.oh;
			t.dropdown.css({
				width : t.pointer.ow,
				top : top,
				left : t.pointer.ol
			});
			if (value) {
				var scroll = 0;
				var height = 0;
				var is_scroll = true;
				for (var i=0; i<t.lists.length; i++) {
					var obj = t.lists[i];
					var n = obj.data('num');
					var selector = t.options.selector[n];
					var h = obj.outerHeight();
					if (selector.value == value) {
						is_scroll = false;
					}
					if (is_scroll) scroll += h;
					height += h;
				}
				scroll = (height-t.options['max-height']) > scroll ? scroll : (height-t.options['max-height']);
				t.dropdown.find('ul').scrollTop(scroll);
			}
		},
		setPointer : function () {
			var t = this;
			var pt = t.element.position();
			var off = t.element.offset();
			var fs = parseInt(t.element.css('font-size'), 10);
			t.pointer = {
				w : t.element.width(),
				h : t.element.height(),
				ow : t.element.outerWidth(),
				oh : t.element.outerHeight(),
				iw : t.element.innerWidth(),
				ih : t.element.innerHeight(),
				sh : 0,
				soh : 0,
				sih : 0,
				t : pt.top,
				l : pt.left,
				ot : off.top,
				ol : off.left,
				fs1 : fs + t.options.addfontsize,
				fs2 : fs>=12 ? fs - 2 : fs
			};
			return t.pointer;
		},
		getSearchSelector : function (value) {
			var t = this;
			var result = null;
			value = value ? value : t.element.val().trim();
			value = t.getSearchStrings(value);
			for (var i=0; i<t.lists.length; i++) {
				var obj = t.lists[i];
				var n = obj.data('num');
				var selector = t.options.selector[n];
				var ismatch = t.matchArray(value, selector.search);
				obj.css('display', (ismatch ? 'block' : 'none'));
				if (ismatch && !result) result = selector;
			}
			return result;
		},
		getSearchStrings : function (str) {
			if (!str) return false;
			str = str.replace(/\s+/g, ' ');
			return str.split(' ');
		},
		matchArray : function (arr, target) {
			if (!arr) return true;
			var result = true;
			for (var i=0; i<arr.length; i++) {
				var regexp = new RegExp(arr[i], 'i');
				result = target.match(regexp) ? result : false;
				if (!result) break;
			}
			return result;
		},
		inArray : function (str, arr) {
			if (typeof arr != 'object' || !arr) return false;
			for (var i=0; i<arr.length; i++) {
				if (arr[i] === str) return true;
			}
			return false;
		}
	};

	$.fn.smSearchInputSelector = function (options) {
		var id = 1;
		return this.each(function() {
			new plugin(this, options, id);
			id++;
		});
	};

	$.fn.smSearchInputSelector.defaults = {
		uniquename : 'jsSearchInputSelector',
		classes : {
			thisname : 'jsSearchInputSelector',
			hidden : 'hidden',
			dropdown : 'dropdown'
		},
		selector : [],
		selected : function (value) { return value; },
		'max-height' : 250,
		addfontsize : 0
	};
})(jQuery);