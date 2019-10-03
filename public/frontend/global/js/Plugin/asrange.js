(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/asrange', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginAsrange = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'asRange'; // import $ from 'jquery';

  var AsRange = function (_Plugin) {
    babelHelpers.inherits(AsRange, _Plugin);

    function AsRange() {
      babelHelpers.classCallCheck(this, AsRange);
      return babelHelpers.possibleConstructorReturn(this, (AsRange.__proto__ || Object.getPrototypeOf(AsRange)).apply(this, arguments));
    }

    babelHelpers.createClass(AsRange, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          tip: false,
          scale: false
        };
      }
    }]);
    return AsRange;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, AsRange);

  exports.default = AsRange;
});
