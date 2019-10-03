(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/summernote', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginSummernote = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'summernote'; // import $ from 'jquery';

  var Summernote = function (_Plugin) {
    babelHelpers.inherits(Summernote, _Plugin);

    function Summernote() {
      babelHelpers.classCallCheck(this, Summernote);
      return babelHelpers.possibleConstructorReturn(this, (Summernote.__proto__ || Object.getPrototypeOf(Summernote)).apply(this, arguments));
    }

    babelHelpers.createClass(Summernote, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          height: 300
        };
      }
    }]);
    return Summernote;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Summernote);

  exports.default = Summernote;
});
