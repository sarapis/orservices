(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/App/Documents', ['exports', 'Site'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Site'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Site);
    global.AppDocuments = mod.exports;
  }
})(this, function (exports, _Site2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });
  exports.getInstance = exports.run = exports.AppDocuments = undefined;

  var _Site3 = babelHelpers.interopRequireDefault(_Site2);

  var AppDocuments = function (_Site) {
    babelHelpers.inherits(AppDocuments, _Site);

    function AppDocuments() {
      babelHelpers.classCallCheck(this, AppDocuments);
      return babelHelpers.possibleConstructorReturn(this, (AppDocuments.__proto__ || Object.getPrototypeOf(AppDocuments)).apply(this, arguments));
    }

    babelHelpers.createClass(AppDocuments, [{
      key: 'processed',
      value: function processed() {
        babelHelpers.get(AppDocuments.prototype.__proto__ || Object.getPrototypeOf(AppDocuments.prototype), 'processed', this).call(this);

        this.scrollHandle();
        this.stickyfillHandle();
        this.handleResize();
      }
    }, {
      key: 'scrollHandle',
      value: function scrollHandle() {
        $('body').scrollspy({
          target: '#articleSticky',
          offset: 80
        });
      }
    }, {
      key: 'stickyfillHandle',
      value: function stickyfillHandle() {
        $('#articleSticky').Stickyfill();
      }
    }, {
      key: 'handleResize',
      value: function handleResize() {
        $(window).on('resize orientationchange', function () {
          $(this).width() > 767 ? Stickyfill.init() : Stickyfill.stop();
        }).resize();
      }
    }]);
    return AppDocuments;
  }(_Site3.default);

  var instance = null;

  function getInstance() {
    if (!instance) {
      instance = new AppDocuments();
    }

    return instance;
  }

  function run() {
    var app = getInstance();
    app.run();
  }

  exports.default = AppDocuments;
  exports.AppDocuments = AppDocuments;
  exports.run = run;
  exports.getInstance = getInstance;
});
