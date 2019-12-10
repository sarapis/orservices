(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/bootstrap-treeview', ['exports', 'jquery', 'Plugin', 'Config'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'), require('Config'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin, global.Config);
    global.PluginBootstrapTreeview = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2, _Config) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var Config = babelHelpers.interopRequireWildcard(_Config);


  var NAME = 'treeview';

  var Treeview = function (_Plugin) {
    babelHelpers.inherits(Treeview, _Plugin);

    function Treeview() {
      babelHelpers.classCallCheck(this, Treeview);
      return babelHelpers.possibleConstructorReturn(this, (Treeview.__proto__ || Object.getPrototypeOf(Treeview)).apply(this, arguments));
    }

    babelHelpers.createClass(Treeview, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (!_jquery2.default.fn.treeview) {
          return;
        }

        var $el = this.$el,
            options = this.options;

        if (typeof options.source === 'string' && _jquery2.default.isFunction(window[options.source])) {
          options.data = window[options.source]();
          delete options.source;
        } else if (_jquery2.default.isFunction(options.souce)) {
          options.data = options.source();
          delete options.source;
        }

        $el.treeview(options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          injectStyle: false,
          expandIcon: 'icon md-plus',
          collapseIcon: 'icon md-minus',
          emptyIcon: 'icon',
          nodeIcon: 'icon md-folder',
          showBorder: false,
          // color: undefined, // "#000000",
          // backColor: undefined, // "#FFFFFF",
          borderColor: Config.colors('blue-grey', 200),
          onhoverColor: Config.colors('blue-grey', 100),
          selectedColor: '#ffffff',
          selectedBackColor: Config.colors('primary', 600),

          searchResultColor: Config.colors('primary', 600),
          searchResultBackColor: '#ffffff'
        };
      }
    }]);
    return Treeview;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Treeview);

  exports.default = Treeview;
});
