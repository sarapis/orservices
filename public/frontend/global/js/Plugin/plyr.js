(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/plyr', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginPlyr = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'plyr';

  (0, _jquery2.default)(document).ready(function () {
    var a = new XMLHttpRequest(),
        d = document,
        u = 'https://cdn.plyr.io/1.1.5/sprite.svg';
    var b = d.body;

    // Check for CORS support
    if ('withCredentials' in a) {
      a.open('GET', u, true);
      a.send();
      a.onload = function () {
        var c = d.createElement('div');
        c.style.display = 'none';
        c.innerHTML = a.responseText;
        b.insertBefore(c, b.childNodes[0]);
      };
    }
  });

  var Plyr = function (_Plugin) {
    babelHelpers.inherits(Plyr, _Plugin);

    function Plyr() {
      babelHelpers.classCallCheck(this, Plyr);
      return babelHelpers.possibleConstructorReturn(this, (Plyr.__proto__ || Object.getPrototypeOf(Plyr)).apply(this, arguments));
    }

    babelHelpers.createClass(Plyr, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (typeof plyr === 'undefined') {
          return;
        }
        plyr.setup(this.$el[0], this.options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {};
      }
    }]);
    return Plyr;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Plyr);

  exports.default = Plyr;
});
