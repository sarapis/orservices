(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/donut', ['exports', 'Plugin', 'Config'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'), require('Config'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin, global.Config);
    global.PluginDonut = mod.exports;
  }
})(this, function (exports, _Plugin2, _Config) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var Config = babelHelpers.interopRequireWildcard(_Config);
  // import $ from 'jquery';
  var NAME = 'donut';

  var DonutPlugin = function (_Plugin) {
    babelHelpers.inherits(DonutPlugin, _Plugin);

    function DonutPlugin() {
      babelHelpers.classCallCheck(this, DonutPlugin);
      return babelHelpers.possibleConstructorReturn(this, (DonutPlugin.__proto__ || Object.getPrototypeOf(DonutPlugin)).apply(this, arguments));
    }

    babelHelpers.createClass(DonutPlugin, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (!Gauge) {
          return;
        }

        var $el = this.$el;
        var $canvas = $el.find('canvas'),
            $text = $el.find('.donut-label');

        if ($canvas.length === 0) {
          return;
        }

        var donut = new Donut($canvas[0]).setOptions(this.options);

        $el.data('donut', donut);

        donut.animationSpeed = 50;
        donut.maxValue = $el.data('max-value');

        donut.set($el.data('value'));

        if ($text.length > 0) {
          donut.setTextField($text[0]);
        }
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          lines: 12,
          angle: 0.3,
          lineWidth: 0.08,
          pointer: {
            length: 0.9,
            strokeWidth: 0.035,
            color: Config.colors('grey', 400)
          },
          limitMax: false, // If true, the pointer will not go past the end of the gauge
          colorStart: Config.colors('grey', 200),
          colorStop: Config.colors('grey', 200),
          strokeColor: Config.colors('primary', 500),
          generateGradient: true
        };
      }
    }]);
    return DonutPlugin;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, DonutPlugin);

  exports.default = DonutPlugin;
});
