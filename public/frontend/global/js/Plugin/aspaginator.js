(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/aspaginator', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginAspaginator = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'paginator';

  var Paginator = function (_Plugin) {
    babelHelpers.inherits(Paginator, _Plugin);

    function Paginator() {
      babelHelpers.classCallCheck(this, Paginator);
      return babelHelpers.possibleConstructorReturn(this, (Paginator.__proto__ || Object.getPrototypeOf(Paginator)).apply(this, arguments));
    }

    babelHelpers.createClass(Paginator, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (!_jquery2.default.fn.asPaginator) {
          return;
        }

        var $el = this.$el,
            total = $el.data('total');

        $el.asPaginator(total, this.options);
      }
    }], [{
      key: 'getDefaults',
      value: function getDefaults() {
        return {
          namespace: 'pagination',
          currentPage: 1,
          itemsPerPage: 10,
          disabledClass: 'disabled',
          activeClass: 'active',

          visibleNum: {
            0: 3,
            480: 5
          },

          tpl: function tpl() {
            return '{{prev}}{{lists}}{{next}}';
          },


          components: {
            prev: {
              tpl: function tpl() {
                return '<li class="' + this.namespace + '-prev page-item"><a class="page-link" href="javascript:void(0)" aria-label="Prev"><span class="icon md-chevron-left"></span></a></li>';
              }
            },
            next: {
              tpl: function tpl() {
                return '<li class="' + this.namespace + '-next page-item"><a class="page-link" href="javascript:void(0)" aria-label="Next"><span class="icon md-chevron-right"></span></a></li>';
              }
            },
            lists: {
              tpl: function tpl() {
                var lists = '',
                    remainder = this.currentPage >= this.visible ? this.currentPage % this.visible : this.currentPage;
                remainder = remainder === 0 ? this.visible : remainder;
                for (var k = 1; k < remainder; k++) {
                  lists += '<li class="' + this.namespace + '-items page-item" data-value="' + (this.currentPage - remainder + k) + '"><a class="page-link" href="javascript:void(0)">' + (this.currentPage - remainder + k) + '</a></li>';
                }
                lists += '<li class="' + this.namespace + '-items page-item ' + this.classes.active + '" data-value="' + this.currentPage + '"><a class="page-link" href="javascript:void(0)">' + this.currentPage + '</a></li>';
                for (var i = this.currentPage + 1, limit = i + this.visible - remainder - 1 > this.totalPages ? this.totalPages : i + this.visible - remainder - 1; i <= limit; i++) {
                  lists += '<li class="' + this.namespace + '-items page-item" data-value="' + i + '"><a class="page-link" href="javascript:void(0)">' + i + '</a></li>';
                }

                return lists;
              }
            }
          }
        };
      }
    }]);
    return Paginator;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, Paginator);

  exports.default = Paginator;
});
