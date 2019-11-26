// import $ from 'jquery';
import Plugin from 'Plugin';

const NAME = 'wizard';

class Wizard extends Plugin {
  getName() {
    return NAME;
  }

  static getDefaults() {
    return {
      step: '.steps .step, .pearls .pearl',
      templates: {
        buttons() {
          let options = this.options;
          return `<div class="wizard-buttons"><a class="btn btn-default btn-outline" href="#${this.id}" data-wizard="back" role="button">${options.buttonLabels.back}</a><a class="btn btn-primary btn-outline float-right" href="#${this.id}" data-wizard="next" role="button">${options.buttonLabels.next}</a><a class="btn btn-success btn-outline float-right" href="#${this.id}" data-wizard="finish" role="button">${options.buttonLabels.finish}</a></div>`;
        }
      },
      classes: {
        step: {
          active: 'active'
        },
        button: {
          hide: 'hidden-xs-up',
          disabled: 'disabled'
        }
      }
    };
  }
}

Plugin.register(NAME, Wizard);

export default Wizard;
