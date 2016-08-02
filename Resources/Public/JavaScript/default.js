'use strict';

var txWeather2 = '.tx-weather2';

jQuery(document).ready(function () {
	jQuery(txWeather2 + ' .secondaryProperties').hide();
	jQuery(txWeather2 + ' .showMore').show().addClass('notSelectable cursorPointer');
	jQuery(txWeather2 + ' .showLess').addClass('notSelectable cursorPointer');

	initSecondaryPropertiesButtonEventListener();
});

function initSecondaryPropertiesButtonEventListener() {
	jQuery(txWeather2).on('click', '.showMore, .showLess', function (e) {
		e.preventDefault();
		handleElementDisplay(jQuery(txWeather2 + ' .showMore'), jQuery(txWeather2 + ' .showLess'), jQuery(txWeather2 + ' .secondaryProperties'));
	});
}

function handleElementDisplay(btnShow, btnHide, elem) {
	if (elem.is(':visible')) {
		elem.slideUp();
		btnShow.show();
		btnHide.hide();
	} else {
		elem.slideDown();
		btnShow.hide();
		btnHide.show();
	}
}