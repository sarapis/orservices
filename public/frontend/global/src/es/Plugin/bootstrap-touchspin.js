// import $ from 'jquery';
import Plugin from 'Plugin';

const NAME = 'TouchSpin';

class TouchSpin extends Plugin {
  getName() {
    return NAME;
  }

  static getDefaults() {
    return {
      verticalupclass: 'md-plus',
      verticaldownclass: 'md-minus',
      buttondown_class: 'btn btn-default',
      buttonup_class: 'btn btn-default'
    };
  }
}

Plugin.register(NAME, TouchSpin);

export default TouchSpin;
