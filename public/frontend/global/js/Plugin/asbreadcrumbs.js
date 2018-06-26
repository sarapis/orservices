(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/asbreadcrumbs', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginAsbreadcrumbs = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'breadcrumb'; // import $ from 'jquery';

  var Breadcrumb = function (_Plugin) {
    babelHelpers.inherits(Breadcrumb, _Plugin);

    function Breadcrumb() {
      babelHelpers.classCallCheck(this, Breadcrumb);
      return babelHelpers.possibleConstructorReturn(this, (Breadcrumb.__proto__ || Object.getPrototypeOf(Breadcrumb)).apply(this, arguments));
    }

    babelHelpers.createClass(Breadcrumb, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        var $el = this.$el;
        $el.asBreadcrumbs(this.options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          overflow: "left",
          namespace: 'breadcrumb',
          dropdownMenuClass: 'dropdown-menu',
          dropdownItemClass: 'dropdown-item',
          toggleIconClass: 'wb-chevron-down-mini',
          ellipsis: function ellipsis(classes, label) {
            return '<li class="breadcrumb-item ' + classes.ellipsisClass + '">' + label + '</li>';
          },
          dropdown: function dropdown(classes) {
            var dropdownClass = 'dropdown';
            var dropdownMenuClass = 'dropdown-menu';

            if (this.options.overflow === 'right') {
              dropdownMenuClass += ' dropdown-menu-right';
            }

            return '<li class="breadcrumb-item ' + dropdownClass + ' ' + classes.dropdownClass + '">\n          <a href="javascript:void(0);" class="' + classes.toggleClass + '" data-toggle="dropdown">\n            <i class="' + classes.toggleIconClass + '"></i>\n          </a>\n          <div class="' + dropdownMenuClass + ' ' + classes.dropdownMenuClass + '" role="menu"></div>\n        </li>';
          },
          dropdownItem: function dropdownItem(classes, label, href) {
            if (!href) {
              return '<a class="' + classes.dropdownItemClass + ' ' + classes.dropdownItemDisableClass + '" href="#">' + label + '</a>';
            }
            return '<a class="' + classes.dropdownItemClass + '" href="' + href + '">' + label + '</a>';
          }
        };
      }
    }]);
    return Breadcrumb;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Breadcrumb);

  exports.default = Breadcrumb;
});
