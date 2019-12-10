(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/raty', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginRaty = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'rating';

  var Rating = function (_Plugin) {
    babelHelpers.inherits(Rating, _Plugin);

    function Rating() {
      babelHelpers.classCallCheck(this, Rating);
      return babelHelpers.possibleConstructorReturn(this, (Rating.__proto__ || Object.getPrototypeOf(Rating)).apply(this, arguments));
    }

    babelHelpers.createClass(Rating, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (!_jquery2.default.fn.raty) {
          return;
        }

        var $el = this.$el;

        if (this.options.hints) {
          this.options.hints = this.options.hints.split(',');
        }

        $el.raty(this.options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          targetKeep: true,
          icon: 'font',
          starType: 'i',
          starOff: 'icon md-star',
          starOn: 'icon md-star orange-600',
          cancelOff: 'icon md-minus-circle',
          cancelOn: 'icon md-minus-circle orange-600',
          starHalf: 'icon md-star-half orange-500'
        };
      }
    }]);
    return Rating;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Rating);

  exports.default = Rating;
});
