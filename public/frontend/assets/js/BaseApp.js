(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/BaseApp', ['exports', 'jquery', 'Plugin', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'), require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin, global.Site);
    global.BaseApp = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin, _Site2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Site3 = babelHelpers.interopRequireDefault(_Site2);

  var BaseApp = function (_Site) {
    babelHelpers.inherits(BaseApp, _Site);

    function BaseApp() {
      babelHelpers.classCallCheck(this, BaseApp);
      return babelHelpers.possibleConstructorReturn(this, (BaseApp.__proto__ || Object.getPrototypeOf(BaseApp)).apply(this, arguments));
    }

    babelHelpers.createClass(BaseApp, [{
      key: 'processed',
      value: function processed() {
        babelHelpers.get(BaseApp.prototype.__proto__ || Object.getPrototypeOf(BaseApp.prototype), 'processed', this).call(this);

        this.handlSlidePanelPlugin();
      }
    }, {
      key: 'handlSlidePanelPlugin',
      value: function handlSlidePanelPlugin() {
        var self = this;
        this.slidepanelOptions = _jquery2.default.extend({}, (0, _Plugin.getDefaults)('slidePanel'), {
          template: function template(options) {
            return '<div class="' + options.classes.base + ' ' + options.classes.base + '-' + options.direction + '">\n                  <div class="' + options.classes.base + '-scrollable">\n                    <div><div class="' + options.classes.content + '"></div></div>\n                  </div>\n                  <div class="' + options.classes.base + '-handler"></div>\n                </div>';
          },
          afterLoad: function afterLoad() {
            this.$panel.find('.' + this.options.classes.base + '-scrollable').asScrollable({
              namespace: 'scrollable',
              contentSelector: '>',
              containerSelector: '>'
            });
            self.initializePlugins(this.$panel);
          },
          afterShow: function afterShow() {
            var _this2 = this;

            (0, _jquery2.default)(document).on('click.slidePanelShow', function (e) {
              if ((0, _jquery2.default)(e.target).closest('.slidePanel').length === 0 && (0, _jquery2.default)(e.target).closest('html').length === 1) {
                _this2.hide();
              }
            });
          },
          afterHide: function afterHide() {
            (0, _jquery2.default)(document).off('click.slidePanelShow');
            (0, _jquery2.default)(document).off('click.slidePanelDatepicker');
          }
        }, this.getSlidePanelOptions());

        (0, _jquery2.default)(document).on('click', '[data-toggle="slidePanel"]', function (e) {

          self.openSlidePanel((0, _jquery2.default)(this).data('url'));

          e.stopPropagation();
        });
      }
    }, {
      key: 'getSlidePanelOptions',
      value: function getSlidePanelOptions() {
        return {};
      }
    }, {
      key: 'openSlidePanel',
      value: function openSlidePanel() {
        var url = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';

        _jquery2.default.slidePanel.show({
          url: url,
          settings: {
            cache: false
          }
        }, this.slidepanelOptions);
      }
    }]);
    return BaseApp;
  }(_Site3.default);

  exports.default = BaseApp;
});
