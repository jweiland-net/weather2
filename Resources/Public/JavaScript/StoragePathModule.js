/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Markus Kugler <projects@jweiland.net>, jweiland.net
 *           Pascal Rinker <projects@jweiland.net>, jweiland.net
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Module: TYPO3/CMS/weather2/StoragePathModule
 * Object that replace pages_ID with ID
 */
define('TYPO3/CMS/weather2/StoragePathModule', ['jquery'], function ($) {
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
			TYPO3.jQuery('#record_storage_page').change(function () {
				TYPO3.jQuery(this).val(TYPO3.jQuery(this).val().replace(/[^0-9]/g, ''));
			});

			var errorNotificationFields = ['mail_config_row', 'email_sendername_row', 'email_sender_row', 'email_receiver_row'];

			function toggleErrorNotificationFields() {
				if (TYPO3.jQuery('#error_notification').is(':checked')) {
					setDisplayAttributeOfElements('', errorNotificationFields);
				} else {
					setDisplayAttributeOfElements('none', errorNotificationFields);
				}
			}

			TYPO3.jQuery('#error_notification').click(function () {
				toggleErrorNotificationFields();
			});

			toggleErrorNotificationFields();
		});
	});
});