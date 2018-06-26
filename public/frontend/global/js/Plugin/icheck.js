(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/icheck', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginIcheck = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'iCheck'; // import $ from 'jquery';

  var ICheck = function (_Plugin) {
    babelHelpers.inherits(ICheck, _Plugin);

    function ICheck() {
      babelHelpers.classCallCheck(this, ICheck);
      return babelHelpers.possibleConstructorReturn(this, (ICheck.__proto__ || Object.getPrototypeOf(ICheck)).apply(this, arguments));
    }

    babelHelpers.createClass(ICheck, [{
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
    return ICheck;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, ICheck);

  exports.default = ICheck;
});
