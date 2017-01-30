'use strict';

var txWeather2 = '.weather2-item';

jQuery(document).ready(function () {
  jQuery(txWeather2 + ' .showMore').click(function (e) {
    var $weather2Item = $(this).parentsUntil('.weather2-item');
    console.log($weather2Item);
    var temp = $(this).html();
    $(this).html($(this).attr('toggle-label'));
    $(this).attr('toggle-label', temp);
    $weather2Item.next('.secondaryProperties').toggleClass('expanded');
    
  });
  
  //
  // jQuery('.showMore').click(function (e) {
  //   e.preventDefault();
  //   var temp = $(this).html();
  //   $(this).html($(this).attr('toggle-label'));
  //   $(this).attr('toggle-label', temp);
  //   $(this).parent().next('.secondaryProperties').toggle();
  //   console.log($(this).parentsUntil('.weather2-item').find('.secondaryProperties'));
  // });
// 	jQuery(txWeather2 + ' .secondaryProperties').hide();
  jQuery(txWeather2 + ' .showMore').show().addClass('notSelectable cursorPointer');
// 	jQuery(txWeather2 + ' .showLess').addClass('notSelectable cursorPointer');
//
// 	initSecondaryPropertiesButtonEventListener();
// });
//
// function initSecondaryPropertiesButtonEventListener() {
// 	jQuery(txWeather2).on('click', '.showMore, .showLess', function (e) {
// 		e.preventDefault();
// 		handleElementDisplay(jQuery(txWeather2 + ' .showMore'), jQuery(txWeather2 + ' .showLess'), jQuery(txWeather2 + ' .secondaryProperties'));
// 	});
// }
//
// function handleElementDisplay(btnShow, btnHide, elem) {
// 	if (elem.is(':visible')) {
// 		elem.slideUp();
// 		btnShow.show();
// 		btnHide.hide();
// 	} else {
// 		elem.slideDown();
// 		btnShow.hide();
// 		btnHide.show();
// 	}
});