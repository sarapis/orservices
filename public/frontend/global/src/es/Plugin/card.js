import $ from 'jquery';
import Plugin from 'Plugin';

const NAME = 'card';

class Card extends Plugin {
  getName() {
    return;
  }

  static getDefaults() {
    return {};
  }

  render() {
    if (!$.fn.card) {
      return;
    }

    let $el = this.$el,
      options = this.options;

    if (options.target) {
      options.container = $(options.target);
    }
    $el.card(options);
  }
}

Plugin.register(NAME, Card);

export default Card;
