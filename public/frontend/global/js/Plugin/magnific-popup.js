(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/magnific-popup', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginMagnificPopup = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'magnificPopup'; // import $ from 'jquery';

  var MagnificPopup = function (_Plugin) {
    babelHelpers.inherits(MagnificPopup, _Plugin);

    function MagnificPopup() {
      babelHelpers.classCallCheck(this, MagnificPopup);
      return babelHelpers.possibleConstructorReturn(this, (MagnificPopup.__proto__ || Object.getPrototypeOf(MagnificPopup)).apply(this, arguments));
    }

    babelHelpers.createClass(MagnificPopup, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          type: 'image',
          closeOnContentClick: true,
          image: {
            verticalFit: true
          }
        };
      }
    }]);
    return MagnificPopup;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, MagnificPopup);

  exports.default = MagnificPopup;
});
