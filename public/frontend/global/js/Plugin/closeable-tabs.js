(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/closeable-tabs', ['exports', 'jquery'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery);
    global.PluginCloseableTabs = mod.exports;
  }
})(this, function (exports, _jquery) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var pluginName = 'tabClose';
  var dismiss = '[data-close="tab"]';

  var TabClose = function () {
    function TabClose(el) {
      babelHelpers.classCallCheck(this, TabClose);

      (0, _jquery2.default)(el).on('click', dismiss, this.close);
    }

    babelHelpers.createClass(TabClose, [{
      key: 'close',
      value: function close(e) {
        var $this = (0, _jquery2.default)(this);
        var $toggle = $this.closest('[data-toggle="tab"]');
        var selector = $toggle.data('target');
        var $li = $toggle.parent('li');

        if (!selector) {
          selector = $toggle.attr('href');
          selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '');
        }

        if ($toggle.hasClass('active')) {
          var $next = $li.siblings().eq(0).children('[data-toggle="tab"]');
          if ($next.length > 0) {
            var api = $next.tab().data('bs.tab');
            api.show();
          }
        }

        var $parent = (0, _jquery2.default)(selector);
        if (e) e.preventDefault();

        $parent.trigger(e = _jquery2.default.Event('close.bs.tab'));

        if (e.isDefaultPrevented()) return;

        $parent.removeClass('in');

        function removeElement() {
          // detach from parent, fire event then clean up data
          $parent.detach().trigger('closed.bs.tab').remove();
          $li.detach().remove();
        }

        _jquery2.default.support.transition && $parent.hasClass('fade') ? $parent.one('bsTransitionEnd', removeElement).emulateTransitionEnd(TabClose.TRANSITION_DURATION) : removeElement();
      }
    }], [{
      key: '_jQueryInterface',
      value: function _jQueryInterface(option) {
        console.log(option);
        return this.each(function () {
          var $this = (0, _jquery2.default)(this);
          var data = $this.data('bs.tab.close');

          if (!data) {
            $this.data('bs.tab.close', data = new TabClose(this));
          }
          if (typeof option == 'string') {
            data[option].call($this);
          }
        });
      }
    }]);
    return TabClose;
  }();

  TabClose.TRANSITION_DURATION = 150;

  _jquery2.default.fn[pluginName] = TabClose._jQueryInterface;
  _jquery2.default.fn[pluginName].Constructor = TabClose;
  _jquery2.default.fn[pluginName].noConflict = function () {
    'use strict';

    _jquery2.default.fn[pluginName] = window.JQUERY_NO_CONFLICT;
    return asSelectable._jQueryInterface;
  };

  // TAB CLOSE DATA-API
  // ==================

  (0, _jquery2.default)(document).on('click.bs.tab-close.data-api', dismiss, TabClose.prototype.close);

  exports.default = TabClose;
});
