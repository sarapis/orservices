(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/gauge', ['exports', 'Plugin', 'Config'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'), require('Config'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin, global.Config);
    global.PluginGauge = mod.exports;
  }
})(this, function (exports, _Plugin2, _Config) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var Config = babelHelpers.interopRequireWildcard(_Config);
  // import $ from 'jquery';
  var NAME = 'gauge';

  var GaugePlugin = function (_Plugin) {
    babelHelpers.inherits(GaugePlugin, _Plugin);

    function GaugePlugin() {
      babelHelpers.classCallCheck(this, GaugePlugin);
      return babelHelpers.possibleConstructorReturn(this, (GaugePlugin.__proto__ || Object.getPrototypeOf(GaugePlugin)).apply(this, arguments));
    }

    babelHelpers.createClass(GaugePlugin, [{
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
            $text = $el.find('.gauge-label');

        if ($canvas.length === 0) {
          return;
        }

        var gauge = new Gauge($canvas[0]).setOptions(this.options);

        $el.data('gauge', gauge);

        gauge.animationSpeed = 50;
        gauge.maxValue = $el.data('max-value');

        gauge.set($el.data('value'));

        if ($text.length > 0) {
          gauge.setTextField($text[0]);
        }
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          lines: 12,
          angle: 0.2,
          lineWidth: 0.4,
          pointer: {
            length: 0.58,
            strokeWidth: 0.03,
            color: Config.colors('grey', 400)
          },
          limitMax: true,
          colorStart: Config.colors('grey', 200),
          colorStop: Config.colors('grey', 200),
          strokeColor: Config.colors('primary', 500),
          generateGradient: true
        };
      }
    }]);
    return GaugePlugin;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, GaugePlugin);

  exports.default = GaugePlugin;
});
