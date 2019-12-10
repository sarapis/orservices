import $ from 'jquery';
import Plugin from 'Plugin';

const NAME = 'rating';

class Rating extends Plugin {
  getName() {
    return NAME;
  }

  static getDefaults() {
    return {
      targetKeep: true,
      icon: 'font',
      starType: 'i',
      starOff: 'icon md-star',
      starOn: 'icon md-star orange-600',
      cancelOff: 'icon md-minus-circle',
      cancelOn: 'icon md-minus-circle orange-600',
      starHalf: 'icon md-star-half orange-500'
    };
  }

  render() {
    if (!$.fn.raty) {
      return;
    }

    let $el = this.$el;

    if (this.options.hints) {
      this.options.hints = this.options.hints.split(',');
    }

    $el.raty(this.options);
  }
}

Plugin.register(NAME, Rating);

export default Rating;
