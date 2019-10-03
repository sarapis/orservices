(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/webui-popover', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginWebuiPopover = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'webuiPopover'; // import $ from 'jquery';

  var WebuiPopover = function (_Plugin) {
    babelHelpers.inherits(WebuiPopover, _Plugin);

    function WebuiPopover() {
      babelHelpers.classCallCheck(this, WebuiPopover);
      return babelHelpers.possibleConstructorReturn(this, (WebuiPopover.__proto__ || Object.getPrototypeOf(WebuiPopover)).apply(this, arguments));
    }

    babelHelpers.createClass(WebuiPopover, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          trigger: 'click',
          width: 320,
          multi: true,
          cloaseable: false,
          style: '',
          delay: 300,
          padding: true
        };
      }
    }]);
    return WebuiPopover;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, WebuiPopover);

  exports.default = WebuiPopover;
});
