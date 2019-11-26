(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/jquery-labelauty', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginJqueryLabelauty = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'labelauty'; // import $ from 'jquery';

  var Labelauty = function (_Plugin) {
    babelHelpers.inherits(Labelauty, _Plugin);

    function Labelauty() {
      babelHelpers.classCallCheck(this, Labelauty);
      return babelHelpers.possibleConstructorReturn(this, (Labelauty.__proto__ || Object.getPrototypeOf(Labelauty)).apply(this, arguments));
    }

    babelHelpers.createClass(Labelauty, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          same_width: true
        };
      }
    }]);
    return Labelauty;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Labelauty);

  exports.default = Labelauty;
});
