(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/nestable', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginNestable = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'nestable'; // import $ from 'jquery';

  var Nestable = function (_Plugin) {
    babelHelpers.inherits(Nestable, _Plugin);

    function Nestable() {
      babelHelpers.classCallCheck(this, Nestable);
      return babelHelpers.possibleConstructorReturn(this, (Nestable.__proto__ || Object.getPrototypeOf(Nestable)).apply(this, arguments));
    }

    babelHelpers.createClass(Nestable, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {};
      }
    }]);
    return Nestable;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Nestable);

  exports.default = Nestable;
});
