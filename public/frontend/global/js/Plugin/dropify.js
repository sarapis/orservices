(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/dropify', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginDropify = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'dropify'; // import $ from 'jquery';

  var Dropify = function (_Plugin) {
    babelHelpers.inherits(Dropify, _Plugin);

    function Dropify() {
      babelHelpers.classCallCheck(this, Dropify);
      return babelHelpers.possibleConstructorReturn(this, (Dropify.__proto__ || Object.getPrototypeOf(Dropify)).apply(this, arguments));
    }

    babelHelpers.createClass(Dropify, [{
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
    return Dropify;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Dropify);

  exports.default = Dropify;
});
