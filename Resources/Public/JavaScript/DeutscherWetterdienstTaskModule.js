/**
 * Module: TYPO3/CMS/Weather2/DeutscherWetterdienstTaskModule
 * Object that replace pages_ID with ID
 */
define('TYPO3/CMS/Weather2/DeutscherWetterdienstTaskModule', ['jquery', 'jquery-ui/jquery-ui-1.10.4.custom.min'], function ($) {
  $(document).ready(function () {
    $('#dwd_recordStoragePage').change(function () {
      $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });
    
    var autoRemoveFields = ['dwd_removeOldAlertsHours_row'];
    
    function toggleAutoRemoveFields() {
      if ($('#dwd_removeOldAlerts').is(':checked')) {
        setDisplayAttributeOfElements('', autoRemoveFields);
      } else {
        setDisplayAttributeOfElements('none', autoRemoveFields);
      }
    }
    
    /**
     * Sets the display property for each element in array elements
     *
     * @param display display property value from css (e.g. block or none)
     * @param elements array of all elements ['first_element', 'second_element']
     */
    function setDisplayAttributeOfElements(display, elements) {
      $(elements).each(function (index, value) {
        $('#' + value).css('display', display);
      });
    }
    
    $('#dwd_region_search').autocomplete({
      source: TYPO3.settings.ajaxUrls['Weather2Dwd::renderRegions'], classes: {
        "ui-autocomplete": "highlight"
      }, select: function (e, ui) {
        if (!$('#dwd_regionItem_' + ui.item.value).length) {
          $('#dwd_selected_regions_ul').append('<li id="dwd_regionItem_' + ui.item.value + '"><a href="#" class="dwd_removeItem">' + TYPO3.lang.removeItem + '</a>' + ui.item.label + '</div><input type="hidden" name="tx_scheduler[dwd_selectedRegions][]" value="' + ui.item.value + '" /></li>');
          $('#dwd_regionItem_' + ui.item.value + ' .dwd_removeItem').click(function () {
              $(this).parent('li').remove();
            });
        }
      }
    }).keypress(function(e) {
      var code = (e.keyCode ? e.keyCode : e.which);
      if(code == 13) {
        return false;
      }
    });
    
    $('#dwd_removeOldAlerts').click(function () {
      toggleAutoRemoveFields();
    });
    
    toggleAutoRemoveFields();
    
    $('.dwd_removeItem').click(function () {
      $(this).parent('li').remove();
    });
  });
});