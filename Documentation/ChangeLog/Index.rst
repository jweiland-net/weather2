..  include:: /Includes.rst.txt


..  _changelog:

=========
ChangeLog
=========

..  _changelog-7-0-0:

Version 7.0.0
=============

*   [TASK] Fetch a localized weather description from OpenWeatherMap
*   [TASK] Add TYPO3 14 compatibility
*   [TASK] Remove TYPO3 13 compatibility

..  _changelog-6-1-4:

Version 6.1.4
=============

*   [BUGFIX] Replace hardcoded tab labels with localized labels in tt_content

..  _changelog-6-1-3:

Version 6.1.3
=============

*   [BUGFIX] Assign wind direction to correct array key

..  _changelog-6-1-2:

Version 6.1.2
=============

*   [TASK] Updated wizard title with [extension] name format

..  _changelog-6-1-1:

Version 6.1.1
=============

*   [BUGFIX] Update testing directory

..  _changelog-6-1-0:

Version 6.1.0
=============

*   [TASK] Fixed TCA configuration issues
*   [BUGFIX] Fixed DB Analyser issue

..  _changelog-6-0-0:

Version 6.0.0
=============

*   Full TYPO3 13 compatibility
*   Dropped older version support
*   Removed all scheduler tasks, replaced with Symfony console commands
*   Updated test suite and removed old test suite configuration files

..  _changelog-5-0-0-a:

Version 5.0.0
=============

..  Note: the source history contains two separate "Version 5.0.0" entries.
    This looks like an unintentional duplication in the original changelog and
    should be reconciled (e.g. one of them renumbered) by whoever maintains the
    release history.

*   [BUGFIX] Backported fixed DB Analyser issues

..  _changelog-5-0-0-b:

Version 5.0.0
=============

*   Full TYPO3 12 compatibility
*   Dropped support for TYPO3 10 and below
*   Removed dependency on :ext:`static_info_tables`
*   Refactored all scheduler tasks to remove ObjectManager usage
*   Important: old scheduler tasks must be deleted and recreated manually

..  _changelog-4-0-0:

Version 4.0.0
=============

*   TYPO3 11 LTS support
*   Dropped support for TYPO3 9
*   Removed TYPO3-specific SQL columns from :file:`ext_tables.sql`
*   Important: clear the DI cache using the maintenance module in the backend
*   Fix: only numeric PIDs are now allowed in scheduler tasks - delete old ones

..  _changelog-3-0-0:

Version 3.0.0
=============

*   TYPO3 10 support introduced
*   TYPO3 8 support removed
*   Smooth upgrade - no manual changes needed

..  _changelog-2-0-4:

Version 2.0.4
=============

*   TYPO3 core change fix: unserializing scheduler tasks failed
*   Required manual fix via upgrade wizard in the TYPO3 backend

..  _changelog-2-0-0:

Version 2.0.0
=============

*   Major extension refactoring
*   Scheduler task and TypoScript plugin handling improved
*   Upgrade steps are in the documentation

..  _changelog-1-0-0:

Version 1.0.0
=============

*   Initial stable release with basic support for the openweathermap.org API
*   Support for weather alerts via Deutscher Wetterdienst (DWD)
*   Includes TypoScript configuration for widgets and plugin rendering
