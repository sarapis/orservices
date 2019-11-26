(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/bootstrap-tagsinput', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginBootstrapTagsinput = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'tagsinput'; // import $ from 'jquery';

  var Tagsinput = function (_Plugin) {
    babelHelpers.inherits(Tagsinput, _Plugin);

    function Tagsinput() {
      babelHelpers.classCallCheck(this, Tagsinput);
      return babelHelpers.possibleConstructorReturn(this, (Tagsinput.__proto__ || Object.getPrototypeOf(Tagsinput)).apply(this, arguments));
    }

    babelHelpers.createClass(Tagsinput, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          tagClass: 'badge badge-default'
        };
      }
    }]);
    return Tagsinput;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Tagsinput);

  exports.default = Tagsinput;
});
