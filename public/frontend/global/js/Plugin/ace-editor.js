(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('/Plugin/ace-editor', ['exports', 'jquery', 'Plugin'], factory);
  } else if (typeof exports !== "undefined") {
    factory(exports, require('jquery'), require('Plugin'));
  } else {
    var mod = {
      exports: {}
    };
    factory(mod.exports, global.jQuery, global.Plugin);
    global.PluginAceEditor = mod.exports;
  }
})(this, function (exports, _jquery, _Plugin2) {
  'use strict';

  Object.defineProperty(exports, "__esModule", {
    value: true
  });

  var _jquery2 = babelHelpers.interopRequireDefault(_jquery);

  var _Plugin3 = babelHelpers.interopRequireDefault(_Plugin2);

  var NAME = 'ace';

  var AceEditor = function (_Plugin) {
    babelHelpers.inherits(AceEditor, _Plugin);

    function AceEditor() {
      babelHelpers.classCallCheck(this, AceEditor);
      return babelHelpers.possibleConstructorReturn(this, (AceEditor.__proto__ || Object.getPrototypeOf(AceEditor)).apply(this, arguments));
    }

    babelHelpers.createClass(AceEditor, [{
      key: 'getName',
      value: function getName() {
        return NAME;
      }
    }, {
      key: 'render',
      value: function render() {
        if (typeof ace === 'undefined') return;
        // ace.config.set("themePath", "../theme");
        ace.config.loadModule('ace/ext/language_tools');

        var $el = this.$el,
            id = $el.attr('id'),
            editor = ace.edit(id);

        editor.container.style.opacity = '';
        if (this.options.mode) {
          editor.session.setMode('ace/mode/' + this.options.mode);
        }
        if (this.options.theme) {
          editor.setTheme('ace/theme/' + this.options.theme);
        }

        editor.setOption('maxLines', 40);
        editor.setAutoScrollEditorIntoView(true);

        ace.config.loadModule('ace/ext/language_tools', function () {
          editor.setOptions({
            enableSnippets: true,
            enableBasicAutocompletion: true
          });
        });
      }
    }]);
    return AceEditor;
  }(_Plugin3.default);

  _Plugin3.default.register(NAME, AceEditor);

  exports.default = AceEditor;
});
