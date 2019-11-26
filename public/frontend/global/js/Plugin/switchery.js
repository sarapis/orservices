(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/switchery', ['exports', 'Plugin', 'Config'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'), require('Config'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin, global.Config);
    global.PluginSwitchery = mod.exports;
  }
})(this, function (exports, _Plugin2, _Config) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var Config = babelHelpers.interopRequireWildcard(_Config);
  // import $ from 'jquery';
  var NAME = 'switchery';

  var SwitcheryPlugin = function (_Plugin) {
    babelHelpers.inherits(SwitcheryPlugin, _Plugin);

    function SwitcheryPlugin() {
      babelHelpers.classCallCheck(this, SwitcheryPlugin);
      return babelHelpers.possibleConstructorReturn(this, (SwitcheryPlugin.__proto__ || Object.getPrototypeOf(SwitcheryPlugin)).apply(this, arguments));
    }

    babelHelpers.createClass(SwitcheryPlugin, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (typeof Switchery === 'undefined') {
          return;
        }
        new Switchery(this.$el[0], this.options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          color: Config.colors('primary', 600)
        };
      }
    }]);
    return SwitcheryPlugin;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, SwitcheryPlugin);

  exports.default = SwitcheryPlugin;
});
