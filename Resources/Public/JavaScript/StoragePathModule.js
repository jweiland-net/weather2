/**
 * Module: TYPO3/CMS/Weather2/StoragePathModule
 * Object that replace pages_ID with ID
 */
define('TYPO3/CMS/Weather2/StoragePathModule', ['jquery'], function ($) {
	$(function () {

		/**
		 * Sets the display property for each element in array elements
		 * @param display display property value from css (e.g. block or none)
		 * @param elements array of all elements ['first_element', 'second_element']
		 */
		function setDisplayAttributeOfElements(display, elements) {
			TYPO3.jQuery(elements).each(function (index, value) {
				TYPO3.jQuery('#' + value).css('display', display);
			});
		}

		TYPO3.jQuery(document).ready(function () {
			TYPO3.jQuery('#recordStoragePage').change(function () {
				TYPO3.jQuery(this).val(TYPO3.jQuery(this).val().replace(/[^0-9]/g, ''));
			});

			var errorNotificationFields = ['mailConfig_row', 'emailSenderName_row', 'emailSender_row', 'emailReceiver_row'];

			function toggleErrorNotificationFields() {
				if (TYPO3.jQuery('#errorNotification').is(':checked')) {
					setDisplayAttributeOfElements('', errorNotificationFields);
				} else {
					setDisplayAttributeOfElements('none', errorNotificationFields);
				}
			}

			TYPO3.jQuery('#errorNotification').click(function () {
				toggleErrorNotificationFields();
			});

			toggleErrorNotificationFields();
		});
	});
});