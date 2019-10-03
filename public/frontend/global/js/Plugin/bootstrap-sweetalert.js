(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/bootstrap-sweetalert', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginBootstrapSweetalert = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'sweetalert'; // import $ from 'jquery';

  var Sweetalert = function (_Plugin) {
    babelHelpers.inherits(Sweetalert, _Plugin);

    function Sweetalert() {
      babelHelpers.classCallCheck(this, Sweetalert);
      return babelHelpers.possibleConstructorReturn(this, (Sweetalert.__proto__ || Object.getPrototypeOf(Sweetalert)).apply(this, arguments));
    }

    babelHelpers.createClass(Sweetalert, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        this.$el.data('sweetalertWrapApi', this);
      }
    }, {
      key: 'show',
      value: function show() {
        if (typeof swal === 'undefined') {
          return;
        }

        swal(this.options);
      }
    }], [{
      key: 'api',
      value: function api() {
        return 'click|show';
      }
    }]);
    return Sweetalert;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Sweetalert);

  exports.default = Sweetalert;
});
