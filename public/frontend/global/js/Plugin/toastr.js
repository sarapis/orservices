(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/toastr', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginToastr = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'toastr'; // import $ from 'jquery';

  var Toastr = function (_Plugin) {
    babelHelpers.inherits(Toastr, _Plugin);

    function Toastr() {
      babelHelpers.classCallCheck(this, Toastr);
      return babelHelpers.possibleConstructorReturn(this, (Toastr.__proto__ || Object.getPrototypeOf(Toastr)).apply(this, arguments));
    }

    babelHelpers.createClass(Toastr, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        this.$el.data('toastrWrapApi', this);
      }
    }, {
      key: 'show',
      value: function show(e) {
        if (typeof toastr === 'undefined') {
          return;
        }

        e.preventDefault();

        var options = this.options;
        var message = options.message || '';
        var type = options.type || 'info';
        var title = options.title || undefined;

        switch (type) {
          case 'success':
            toastr.success(message, title, options);
            break;
          case 'warning':
            toastr.warning(message, title, options);
            break;
          case 'error':
            toastr.error(message, title, options);
            break;
          case 'info':
            toastr.info(message, title, options);
            break;
          default:
            toastr.info(message, title, options);
        }
      }
    }], [{
      key: 'api',
      value: function api() {
        return 'click|show';
      }
    }]);
    return Toastr;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Toastr);

  exports.default = Toastr;
});
