(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/App/Forum', ['exports', 'BaseApp'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('BaseApp'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.BaseApp);
    global.AppForum = mod.exports;
  }
})(this, function (exports, _BaseApp2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.getInstance = exports.run = exports.AppForum = undefined;

  var _BaseApp3 = babelHelpers.interopRequireDefault(_BaseApp2);

  var AppForum = function (_BaseApp) {
    babelHelpers.inherits(AppForum, _BaseApp);

    function AppForum() {
      babelHelpers.classCallCheck(this, AppForum);
      return babelHelpers.possibleConstructorReturn(this, (AppForum.__proto__ || Object.getPrototypeOf(AppForum)).apply(this, arguments));
    }

    return AppForum;
  }(_BaseApp3.default);

  var instance = null;

  function getInstance() {
    if (!instance) {
      instance = new AppForum();
    }
    return instance;
  }

  function run() {
    var app = getInstance();
    app.run();
  }

  exports.default = AppForum;
  exports.AppForum = AppForum;
  exports.run = run;
  exports.getInstance = getInstance;
});
