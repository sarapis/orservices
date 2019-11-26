(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/bootstrap-maxlength', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginBootstrapMaxlength = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'maxlength'; // import $ from 'jquery';

  var Maxlength = function (_Plugin) {
    babelHelpers.inherits(Maxlength, _Plugin);

    function Maxlength() {
      babelHelpers.classCallCheck(this, Maxlength);
      return babelHelpers.possibleConstructorReturn(this, (Maxlength.__proto__ || Object.getPrototypeOf(Maxlength)).apply(this, arguments));
    }

    babelHelpers.createClass(Maxlength, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          warningClass: 'badge badge-warning',
          limitReachedClass: 'badge badge-danger'
        };
      }
    }]);
    return Maxlength;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Maxlength);

  exports.default = Maxlength;
});
