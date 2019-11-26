(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/jstree', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginJstree = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'jstree'; // import $ from 'jquery';

  var Jstree = function (_Plugin) {
    babelHelpers.inherits(Jstree, _Plugin);

    function Jstree() {
      babelHelpers.classCallCheck(this, Jstree);
      return babelHelpers.possibleConstructorReturn(this, (Jstree.__proto__ || Object.getPrototypeOf(Jstree)).apply(this, arguments));
    }

    babelHelpers.createClass(Jstree, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }]);
    return Jstree;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Jstree);

  exports.default = Jstree;
});
