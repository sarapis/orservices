(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/slidepanel', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginSlidepanel = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'slidePanel';

  var SlidePanel = function (_Plugin) {
    babelHelpers.inherits(SlidePanel, _Plugin);

    function SlidePanel() {
      babelHelpers.classCallCheck(this, SlidePanel);
      return babelHelpers.possibleConstructorReturn(this, (SlidePanel.__proto__ || Object.getPrototypeOf(SlidePanel)).apply(this, arguments));
    }

    babelHelpers.createClass(SlidePanel, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (typeof _jquery2.default.slidePanel === 'undefined') {
          return;
        }
        if (!this.options.url) {
          this.options.url = this.$el.attr('href');
          this.options.url = this.options.url && this.options.url.replace(/.*(?=#[^\s]*$)/, '');
        }

        this.$el.data('slidePanelWrapAPI', this);
      }
    }, {
      key: 'show',
      value: function show() {
        var options = this.options;

        _jquery2.default.slidePanel.show({
          url: options.url
        }, options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          closeSelector: '.slidePanel-close',
          mouseDragHandler: '.slidePanel-handler',
          loading: {
            template: function template(options) {
              return '<div class="' + options.classes.loading + '">\n                    <div class="loader loader-default"></div>\n                  </div>';
            },
            showCallback: function showCallback(options) {
              this.$el.addClass(options.classes.loading + '-show');
            },
            hideCallback: function hideCallback(options) {
              this.$el.removeClass(options.classes.loading + '-show');
            }
          }
        };
      }
    }, {
      key: 'api',
      value: function api() {
        return 'click|show';
      }
    }]);
    return SlidePanel;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, SlidePanel);
  exports.default = SlidePanel;
});
