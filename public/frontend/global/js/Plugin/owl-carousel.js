(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/owl-carousel', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginOwlCarousel = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'owlCarousel'; // import $ from 'jquery';

  var OwlCarousel = function (_Plugin) {
    babelHelpers.inherits(OwlCarousel, _Plugin);

    function OwlCarousel() {
      babelHelpers.classCallCheck(this, OwlCarousel);
      return babelHelpers.possibleConstructorReturn(this, (OwlCarousel.__proto__ || Object.getPrototypeOf(OwlCarousel)).apply(this, arguments));
    }

    babelHelpers.createClass(OwlCarousel, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          // autoWidth: true,
          loop: true,
          nav: true,
          dots: false,
          dotsClass: 'owl-dots owl-dots-fall',
          responsive: {
            0: {
              items: 1
            },
            600: {
              items: 3
            }
          }
        };
      }
    }]);
    return OwlCarousel;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, OwlCarousel);

  exports.default = OwlCarousel;
});
