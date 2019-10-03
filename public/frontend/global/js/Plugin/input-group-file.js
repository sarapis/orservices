(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/input-group-file', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginInputGroupFile = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'inputGroupFile';

  var InputGroupFile = function (_Plugin) {
    babelHelpers.inherits(InputGroupFile, _Plugin);

    function InputGroupFile() {
      babelHelpers.classCallCheck(this, InputGroupFile);
      return babelHelpers.possibleConstructorReturn(this, (InputGroupFile.__proto__ || Object.getPrototypeOf(InputGroupFile)).apply(this, arguments));
    }

    babelHelpers.createClass(InputGroupFile, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        this.$file = this.$el.find('[type=file]');
        this.$text = this.$el.find('.form-control');
      }
    }, {
      key: 'change',
      value: function change() {
        var value = '';
        _jquery2.default.each(this.$file[0].files, function (i, file) {
          value += file.name + ', ';
        });

        value = value.substring(0, value.length - 2);

        this.$text.val(value);
      }
    }], [{
      key: 'api',
      value: function api() {
        return 'change|change';
      }
    }]);
    return InputGroupFile;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, InputGroupFile);
  exports.default = InputGroupFile;
});
