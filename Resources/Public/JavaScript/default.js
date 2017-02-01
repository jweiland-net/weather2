'use strict';

var txWeather2 = '.weather2-item';

jQuery(document).ready(function () {
  jQuery(txWeather2 + ' .showMore').click(function (e) {
    e.preventDefault();
    var $weather2Item = $(this).parentsUntil('.weather2-item');
    console.log($weather2Item);
    var temp = $(this).html();
    $(this).html($(this).attr('toggle-label'));
    $(this).attr('toggle-label', temp);
    $weather2Item.next('.secondaryProperties').toggleClass('expanded');
    
  });
  jQuery(txWeather2 + ' .showMore').show().addClass('notSelectable cursorPointer');
});