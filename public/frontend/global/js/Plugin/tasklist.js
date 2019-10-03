(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/tasklist', ['exports', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.Plugin);
    global.PluginTasklist = mod.exports;
  }
})(this, function (exports, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'tasklist'; // import $ from 'jquery';

  var TaskList = function (_Plugin) {
    babelHelpers.inherits(TaskList, _Plugin);

    function TaskList() {
      babelHelpers.classCallCheck(this, TaskList);
      return babelHelpers.possibleConstructorReturn(this, (TaskList.__proto__ || Object.getPrototypeOf(TaskList)).apply(this, arguments));
    }

    babelHelpers.createClass(TaskList, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        this.$el.data('tasklistApi', this);
        this.$checkbox = this.$el.find('[type="checkbox"]');
        this.$el.trigger('change.site.task');
      }
    }, {
      key: 'toggle',
      value: function toggle() {
        if (this.$checkbox.is(':checked')) {
          this.$el.addClass('task-done');
        } else {
          this.$el.removeClass('task-done');
        }
      }
    }], [{
      key: 'api',
      value: function api() {
        return 'change|toggle';
      }
    }]);
    return TaskList;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, TaskList);

  exports.default = TaskList;
});
