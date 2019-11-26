(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/menu', ['Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(global.Plugin);
    global.PluginMenu = mod.exports;
  }
})(this, function (_Plugin2) {
  'use strict';

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'menu';

  var Scrollable = function () {
    function Scrollable($el, light) {
      babelHelpers.classCallCheck(this, Scrollable);

      this.$el = $el;
      this.light = light;
      this.built = false;
      this.init();
    }

    babelHelpers.createClass(Scrollable, [{
      key: 'init',
      value: function init() {
        this.$el.asScrollable({
          namespace: 'scrollable',
          skin: '', // this.light ? '' : 'scrollable-inverse',
          direction: 'vertical',
          contentSelector: '>',
          containerSelector: '>'
        });

        this.built = true;
      }
    }, {
      key: 'update',
      value: function update($target) {
        if (typeof $target !== 'undefined') {
          $($target).data('asScrollable').update();
        } else {
          this.$el.each(function () {
            $(this).data('asScrollable').update();
          });
        }
      }
    }, {
      key: 'enable',
      value: function enable() {
        this.$el.each(function () {
          $(this).data('asScrollable').enable();
        });
      }
    }, {
      key: 'disable',
      value: function disable() {
        this.$el.each(function () {
          $(this).data('asScrollable').disable();
        });
      }
    }, {
      key: 'refresh',
      value: function refresh() {
        this.$el.each(function () {
          $(this).data('asScrollable').update();
        });
      }
    }, {
      key: 'destroy',
      value: function destroy() {
        this.$el.each(function () {
          $(this).data('asScrollable').disable();
        });

        this.built = false;
      }
    }]);
    return Scrollable;
  }();

  var Menu = function (_Plugin) {
    babelHelpers.inherits(Menu, _Plugin);

    function Menu() {
      var _ref;

      babelHelpers.classCallCheck(this, Menu);

      for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
        args[_key] = arguments[_key];
      }

      var _this = babelHelpers.possibleConstructorReturn(this, (_ref = Menu.__proto__ || Object.getPrototypeOf(Menu)).call.apply(_ref, [this].concat(args)));

      _this.$scrollItems = _this.$el.find('.site-menu-scroll-wrap');

      return _this;
    }

    babelHelpers.createClass(Menu, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        this.bindEvents();
        this.bindResize();

        if (Breakpoints.current().name !== 'xs') {
          this.scrollable = new Scrollable(this.$scrollItems, this.options.light);
        }
        this.$el.data('menuApi', this);
      }
    }, {
      key: 'globalClick',
      value: function globalClick(flag) {
        switch (flag) {
          case 'on':
            $(document).on('click.site.menu', function (e) {
              if ($('.dropdown > [data-dropdown-toggle="true"]').length > 0) {
                if ($(e.target).closest('.dropdown-menu').length === 0) {
                  $('.dropdown > [data-dropdown-toggle="true"]').attr('data-dropdown-toggle', 'false').closest('.dropdown').removeClass('open');
                }
              }
            });
            break;
          case 'off':
            $(document).off('click.site.menu');
            break;
        }
      }
    }, {
      key: 'open',
      value: function open($tag) {
        if ($tag.is('.dropdown')) {
          $('[data-dropdown-toggle="true"]').attr('data-dropdown-toggle', 'false').closest('.dropdown').removeClass('open');
          $tag.find('>[data-toggle="dropdown"]').attr('data-dropdown-toggle', 'true');
        }
        $tag.addClass('open');
      }
    }, {
      key: 'close',
      value: function close($tag) {
        $tag.removeClass('open');
        if ($tag.is('.dropdown')) {
          $tag.find('>[data-toggle="dropdown"]').attr('data-dropdown-toggle', 'false');
        }
      }
    }, {
      key: 'reset',
      value: function reset() {
        $('.dropdown > [data-dropdown-toggle="true"]').attr('data-dropdown-toggle', 'false').closest('.dropdown').removeClass('open');
      }
    }, {
      key: 'bindEvents',
      value: function bindEvents() {
        var self = this;

        if (Breakpoints.current().name !== 'xs') {
          this.globalClick('on');
        }

        this.$el.on('open.site.menu', '.site-menu-item', function (e) {
          var $item = $(this);

          if (Breakpoints.current().name === 'xs') {
            self.expand($item, function () {
              self.open($item);
            });
          } else {
            self.open($item);
          }

          if (self.options.accordion) {
            $item.siblings('.open').trigger('close.site.menu');
          }

          e.stopPropagation();
        }).on('close.site.menu', '.site-menu-item.open', function (e) {
          var $item = $(this);

          if (Breakpoints.current().name === 'xs') {
            self.collapse($item, function () {
              self.close($item);
            });
          } else {
            self.close($item);
          }

          e.stopPropagation();
        }).on('click.site.menu ', '.site-menu-item', function (e) {
          var $item = $(this);
          if ($item.is('.has-sub') && $(e.target).closest('.site-menu-item').is(this)) {
            if ($item.is('.open')) {
              $item.trigger('close.site.menu');
            } else {
              $item.trigger('open.site.menu');
            }
          }

          if (Breakpoints.current().name === 'xs') {
            e.stopPropagation();
          } else {
            if ($item.is('.dropdown')) {
              e.stopPropagation();
            }

            if ($(e.target).closest('.site-menu-scroll-wrap').length === 1) {
              self.scrollable.update($(e.target).closest('.site-menu-scroll-wrap'));
              e.stopPropagation();
            }
          }
        });
      }
    }, {
      key: 'bindResize',
      value: function bindResize() {
        var _this2 = this;

        var prevBreakpoint = Breakpoints.current().name;
        Breakpoints.on('change', function () {
          var current = Breakpoints.current().name;

          _this2.reset();
          if (current === 'xs') {
            _this2.globalClick('off');
            _this2.scrollable.destroy();
            _this2.$el.off('click.site.menu.scroll');
          } else {
            if (prevBreakpoint === 'xs') {
              if (!_this2.scrollable) {
                _this2.scrollable = new Scrollable(_this2.$scrollItems, _this2.options.light);
              }
              if (!_this2.scrollable.built) {
                _this2.scrollable.init();
              }

              _this2.scrollable.enable();

              _this2.globalClick('off');
              _this2.globalClick('on');

              $('.site-menu .scrollable-container', _this2.$el).css({
                'height': '',
                'width': ''
              });

              _this2.$el.one('click.site.menu.scroll', '.site-menu-item', function () {
                _this2.scrollable.refresh();
              });
            }
          }
          prevBreakpoint = current;
        });
      }
    }, {
      key: 'collapse',
      value: function collapse($item, callback) {
        var self = this;
        var $sub = $($('> .site-menu-sub', $item)[0] || $('> .dropdown-menu', $item)[0] || $('> .site-menu-scroll-wrap', $item)[0]);

        $sub.show().slideUp(this.options.speed, function () {
          $(this).css('display', '');

          $(this).find('> .site-menu-item').removeClass('is-shown');

          if (callback) {
            callback();
          }

          self.$el.trigger('collapsed.site.menu');
        });
      }
    }, {
      key: 'expand',
      value: function expand($item, callback) {
        var self = this;
        var $sub = $($('> .site-menu-sub', $item)[0] || $('> .dropdown-menu', $item)[0] || $('> .site-menu-scroll-wrap', $item)[0]);
        var $children = $sub.is('.site-menu-sub') ? $sub.children('.site-menu-item').addClass('is-hidden') : $($sub.find('.site-menu-sub')[0]).addClass('is-hidden');

        $sub.hide().slideDown(this.options.speed, function () {
          $(this).css('display', '');

          if (callback) {
            callback();
          }

          self.$el.trigger('expanded.site.menu');
        });

        setTimeout(function () {
          $children.addClass('is-shown');
          $children.removeClass('is-hidden');
        }, 0);
      }
    }, {
      key: 'refresh',
      value: function refresh() {
        this.$el.find('.open').filter(':not(.active)').removeClass('open');
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          speed: 250,
          accordion: true
        };
      }
    }]);
    return Menu;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Menu);
});
