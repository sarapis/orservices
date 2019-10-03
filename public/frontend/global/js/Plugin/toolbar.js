(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/toolbar', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginToolbar = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'toolbar';

  var Toolbar = function (_Plugin) {
    babelHelpers.inherits(Toolbar, _Plugin);

    function Toolbar() {
      babelHelpers.classCallCheck(this, Toolbar);
      return babelHelpers.possibleConstructorReturn(this, (Toolbar.__proto__ || Object.getPrototypeOf(Toolbar)).apply(this, arguments));
    }

    babelHelpers.createClass(Toolbar, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (!_jquery2.default.fn.toolbar) {
          return;
        }

        var $el = this.$el,
            content = $el.data('toolbar');

        if (content) {
          this.options.content = content;
        }

        $el.toolbar(this.options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          hideOnClick: true,
          event: 'hover'
        };
      }
    }]);
    return Toolbar;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Toolbar);

  exports.default = Toolbar;
});
