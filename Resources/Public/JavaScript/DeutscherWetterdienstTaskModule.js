/**
 * Module: TYPO3/CMS/Weather2/DeutscherWetterdienstTaskModule
 * Object that replace pages_ID with ID
 */
define('TYPO3/CMS/Weather2/DeutscherWetterdienstTaskModule', ['jquery', 'jquery/autocomplete'], function ($) {
  $(document).ready(function () {
    $('#dwd_recordStoragePage').change(function () {
      $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });

    $('#dwd_warn_cell_search').autocomplete({
      serviceUrl: TYPO3.settings.ajaxUrls['weather2_dwd_warn-cell-search'],
      dataType: 'json',
      minChars: 3,
      onSelect: function (suggestion) {
        if (!$('#dwd_warnCellItem_' + suggestion.data).length) {
          $('#dwd_selected_warn_cells_ul').append('<li class="list-group-item" id="dwd_warnCellItem_' + suggestion.data + '"><a href="#" class="badge dwd_removeItem">' + TYPO3.lang.removeItem + '</a>' + suggestion.value + '</div><input type="hidden" name="tx_scheduler[dwd_selectedWarnCells][]" value="' + suggestion.data + '" /></li>');
          $('#dwd_warnCellItem_' + suggestion.data + ' .dwd_removeItem').click(function () {
            $(this).parent('li').remove();
          });
        }
      }
    }).keypress(function (e) {
      let code = (e.keyCode ? e.keyCode : e.which);
      if (code == 13) {
        return false;
      }
    });

    $('.dwd_removeItem').click(function () {
      $(this).parent('li').remove();
    });
  });
});
