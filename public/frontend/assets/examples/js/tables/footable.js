/*!
 * remark (http://getbootstrapadmin.com/remark)
 * Copyright 2017 amazingsurge
 * Licensed under the Themeforest Standard Licenses
 */
(function(document, window, $) {
  'use strict';

  var Site = window.Site;

  $(document).ready(function($) {
    Site.run();
  });

  // Example Row Toggler
  // -------------------
  (function() {
    $('#exampleRowToggler').footable({
      "toggleColumn": "first",
      "showToggle": true,
      "expandFirst": true
    });
  })();

  // Accordion
  // ---------
  (function() {
    $('#exampleFooAccordion').footable();
  })();

  // Collapse
  // --------------------------
  (function() {
    $('#exampleFooCollapse').footable();
  })();

  // NO HEADERS
  // ----------
  (function() {
    $('#exampleNoHeaders').footable();
  })();

  // Pagination
  // ----------
  (function() {
    $('#examplePagination').footable();

    // var pagesize = 25;
    // FooTable.get('#examplePagination').pageSize(pagesize);

    $('#exampleShow [data-page-size]').on('click', function(e) {
      e.preventDefault();
      var pagesize = $(this).data('pageSize');
      FooTable.get('#examplePagination').pageSize(pagesize);
    });
  })();

  // Custom filter UI
  // ----------
  (function() {
    $('#exampleCustomFilter').footable();
    $('.filter-ui-status').on('change', function() {
      var filtering = FooTable.get('#exampleCustomFilter').use(FooTable.Filtering), // get the filtering component for the table
        filter = $(this).val(); // get the value to filter by
      if (filter === 'none') { // if the value is "none" remove the filter
        filtering.removeFilter('status');
      } else { // otherwise add/update the filter.
        filtering.addFilter('status', filter, ['status']);
      }
      filtering.filter();
    });
  })();

  // Modal
  // ----------
  (function() {
    $('#exampleModal').footable({
      "useParentWidth": true
    });
  })();

  // Loading Rows
  // ----------
  (function() {
    $('#exampleLoading').footable();
    var loading = FooTable.get('#exampleLoading');

    $('.append-rows').on('click', function(e) {
      e.preventDefault();
      // get the url to load off the button
      var url = $(this).data('url');
      // ajax fetch the rows
      $.get(url).then(function(rows) {
        // and then load them using either
        loading.rows.load(rows);
        // or
        // ft.loadRows(rows);
      });
    });
  })();

  // Filtering
  // ---------
  (function() {
    FooTable.MyFiltering = FooTable.Filtering.extend({
      construct: function(instance) {
        this._super(instance);
        this.statuses = ['Active', 'Disabled', 'Suspended'];
        this.def = 'Any Status';
        this.$status = null;
      },
      $create: function() {
        this._super();
        var self = this,
          $form_grp = $('<div/>', {
            'class': 'form-group'
          })
          .append($('<label/>', {
            'class': 'sr-only',
            text: 'Status'
          }))
          .prependTo(self.$form);

        self.$status = $('<select/>', {
            'class': 'form-control'
          })
          .on('change', {
            self: self
          }, self._onStatusDropdownChanged)
          .append($('<option/>', {
            text: self.def
          }))
          .appendTo($form_grp);

        $.each(self.statuses, function(i, status) {
          self.$status.append($('<option/>').text(status));
        });
      },
      _onStatusDropdownChanged: function(e) {
        var self = e.data.self,
          selected = $(this).val();
        if (selected !== self.def) {
          self.addFilter('status', selected, ['status']);
        } else {
          self.removeFilter('status');
        }
        self.filter();
      },
      draw: function() {
        this._super();
        var status = this.find('status');
        if (status instanceof FooTable.Filter) {
          this.$status.val(status.query.val());
        } else {
          this.$status.val(this.def);
        }
      }
    });

    FooTable.components.register('filtering', FooTable.MyFiltering);
    var filtering = $('#exampleFootableFiltering');
    filtering.footable();
  })();

  // Editing Row
  // ----------------
  (function() {
    var $modal = $('#editor-modal'),
      $editor = $('#editor'),
      $editorTitle = $('#editor-title'),
      ft = FooTable.init('#exampleFooEditing', {
        editing: {
          enabled: true,
          addRow: function() {
            $modal.removeData('row');
            $editor[0].reset();
            $editorTitle.text('Add a new row');
            $modal.modal('show');
          },
          editRow: function(row) {
            var values = row.val();
            $editor.find('#id').val(values.id);
            $editor.find('#firstName').val(values.firstName);
            $editor.find('#lastName').val(values.lastName);
            $editor.find('#jobTitle').val(values.jobTitle);
            $editor.find('#startedOn').val(values.startedOn.format('YYYY-MM-DD'));
            $editor.find('#dob').val(values.dob.format('YYYY-MM-DD'));

            $modal.data('row', row); // set the row data value for use later
            $editorTitle.text('Edit row #' + values.id); // set the modal title
            $modal.modal('show'); // display the modal
          },
          deleteRow: function(row) {
            if (confirm('Are you sure you want to delete the row?')) {
              row.delete();
            }
          }
        }
      }),
      uid = 10;

    $editor.on('submit', function(e) {
      if (this.checkValidity && !this.checkValidity()) return;
      e.preventDefault();
      var row = $modal.data('row'),
        values = {
          id: $editor.find('#id').val(),
          firstName: $editor.find('#firstName').val(),
          lastName: $editor.find('#lastName').val(),
          jobTitle: $editor.find('#jobTitle').val(),
          startedOn: moment($editor.find('#startedOn').val(), 'YYYY-MM-DD'),
          dob: moment($editor.find('#dob').val(), 'YYYY-MM-DD'),
        };

      if (row instanceof FooTable.Row) {
        row.val(values);
      } else {
        values.id = uid++;
        ft.rows.add(values);
      }
      $modal.modal('hide');
    });
  })();

})(document, window, jQuery);
