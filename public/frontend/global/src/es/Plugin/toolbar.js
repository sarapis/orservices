import $ from 'jquery';
import Plugin from 'Plugin';

const NAME = 'toolbar';

class Toolbar extends Plugin {
  getName() {
    return NAME;
  }

  static getDefaults() {
    return {
      hideOnClick: true,
      event: 'hover'
    };
  }

  render() {
    if (!$.fn.toolbar) {
      return;
    }

    let $el = this.$el,
      content = $el.data('toolbar');

    if (content) {
      this.options.content = content;
    }

    $el.toolbar(this.options);
  }
}

Plugin.register(NAME, Toolbar);

export default Toolbar;
