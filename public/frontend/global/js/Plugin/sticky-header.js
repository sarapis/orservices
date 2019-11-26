(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/sticky-header', ['exports', 'jquery'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery);
    global.PluginStickyHeader = mod.exports;
  }
})(this, function (exports, _jquery) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var pluginName = 'stickyHeader';

  var defaults = {
    headerSelector: '.header',
    changeHeaderOn: 100,
    activeClassName: 'active-sticky-header',
    min: 50,
    method: 'toggle'
  };

  var stickyHeader = function () {
    function stickyHeader(el, options) {
      babelHelpers.classCallCheck(this, stickyHeader);

      this.isActive = false;
      this.init(options);
      this.bind();
    }

    babelHelpers.createClass(stickyHeader, [{
      key: 'init',
      value: function init(options) {
        var $el = this.$el.css('transition', 'none');

        var $header = this.$header = $el.find(options.headerSelector).css({
          position: 'absolute',
          top: 0,
          left: 0
        });

        this.options = _jquery2.default.extend(true, {}, defaults, options, $header.data());
        this.headerHeight = $header.outerHeight();
        // this.offsetTop()
        // $el.css('transition','all .5s linear');
        // $header.css('transition','all .5s linear');
        this.$el.css('paddingTop', this.headerHeight);
      }
    }, {
      key: '_toggleActive',
      value: function _toggleActive() {
        if (this.isActive) {
          this.$header.css('height', this.options.min);
        } else {
          this.$header.css('height', this.headerHeight);
        }
      }
    }, {
      key: 'bind',
      value: function bind() {
        var self = this;
        this.$el.on('scroll', function () {
          if (self.options.method === 'toggle') {
            if ((0, _jquery2.default)(this).scrollTop() > self.options.changeHeaderOn && !self.isActive) {
              self.$el.addClass(self.options.activeClassName);
              self.isActive = true;
              self.$header.css('height', self.options.min);
              self.$el.trigger('toggle:sticky', [self, self.isActive]);
            } else if ((0, _jquery2.default)(this).scrollTop() <= self.options.changeHeaderOn && self.isActive) {
              self.$el.removeClass(self.options.activeClassName);
              self.isActive = false;
              self.$header.css('height', self.headerHeight);
              self.$el.trigger('toggle:sticky', [self, self.isActive]);
            }
          } else if (self.options.method === 'scroll') {
            var offset = Math.max(self.headerHeight - (0, _jquery2.default)(this).scrollTop(), self.options.min);
            if (offset === self.headerHeight) {
              self.$el.removeClass(self.options.activeClassName);
            } else {
              self.$el.addClass(self.options.activeClassName);
            }
            self.$header.css('height', offset);
            self.$el.trigger('toggle:sticky', [self]);
          }
        });
      }
    }], [{
      key: '_jQueryInterface',
      value: function _jQueryInterface(options) {
        for (var _len = arguments.length, args = Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
          args[_key - 1] = arguments[_key];
        }

        if (typeof options === 'string') {
          var method = options;
          if (/^\_/.test(method)) {
            return false;
          } else {
            return this.each(function () {
              var api = _jquery2.default.data(this, pluginName);
              if (api && typeof api[method] === 'function') {
                api[method].apply(api, args);
              }
            });
          }
        } else {
          return this.each(function () {
            if (!_jquery2.default.data(this, pluginName)) {
              _jquery2.default.data(this, pluginName, new stickyHeader(this, options));
            } else {
              _jquery2.default.data(this, pluginName).init(options);
            }
          });
        }
      }
    }]);
    return stickyHeader;
  }();

  _jquery2.default.fn[pluginName] = stickyHeader._jQueryInterface;
  _jquery2.default.fn[pluginName].constructor = stickyHeader;
  _jquery2.default.fn[pluginName].noConflict = function () {
    'use strict';

    _jquery2.default.fn[pluginName] = window.JQUERY_NO_CONFLICT;
    return stickyHeader._jQueryInterface;
  };

  exports.default = stickyHeader;
});
