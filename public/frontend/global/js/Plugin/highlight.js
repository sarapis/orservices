(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/highlight', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginHighlight = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'highlight';

  var Highlight = function (_Plugin) {
    babelHelpers.inherits(Highlight, _Plugin);

    function Highlight() {
      babelHelpers.classCallCheck(this, Highlight);
      return babelHelpers.possibleConstructorReturn(this, (Highlight.__proto__ || Object.getPrototypeOf(Highlight)).apply(this, arguments));
    }

    babelHelpers.createClass(Highlight, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (typeof _jquery2.default.fn.hightlight === 'undefined') {
          return;
        }

        // hljs.configure({useBR: true});
        hljs.highlightBlock(block);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {};
      }
    }]);
    return Highlight;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Highlight);

  exports.default = Highlight;
});
