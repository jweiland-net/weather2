..  include:: /Includes.rst.txt


..  _changelog:

=========
ChangeLog
=========

Version 6.1.1
=============

*   [BUGFIX] Update testing directory

Version 6.1.0
=============

*   [TASK] Fixed TCA configuration issues
*   [BUGFIX] Fixed DB Analyser Issue

Version 6.0.0
=============

*   Full TYPO3 13 compatibility
*   Dropped older version support
*   Removed all scheduler tasks, replaced with Symfony Console Commands
*   Updated TestSuite and removed old TestSuite configuration files

Version 5.0.0
=============

*   [BUGFIX] Backported Fixed DB Analyser Issues

Version 5.0.0
=============

*   Full TYPO3 12 compatibility
*   Dropped support for TYPO3 10 and below
*   Removed dependency on EXT:static_info_tables
*   Refactored all scheduler tasks to remove ObjectManager usage
*   Important: Old scheduler tasks must be deleted and recreated manually

Version 4.0.0
=============

*   TYPO3 11 LTS support
*   Dropped support for TYPO3 9
*   Removed TYPO3-specific SQL columns from ext_tables.sql
*   Important: Clear DI cache using Maintenance module in backend
*   Fix: Only numeric PIDs are now allowed in scheduler tasks – delete old ones

Version 3.0.0
=============

*   TYPO3 10 support introduced
*   TYPO3 8 support removed
*   Smooth upgrade – no manual changes needed

Version 2.0.4
=============

*   TYPO3 core change fix: unserializing scheduler tasks failed
*   Required manual fix via Upgrade Wizard in TYPO3 backend

Version 2.0.0
=============

*   Major extension refactoring
*   Scheduler task and TypoScript plugin handling improved
*   Upgrade steps are in the documentation

Version 1.0.0
=============

*   Initial stable release with basic support for openweathermap.org API
*   Support for weather alerts via Deutscher Wetterdienst (DWD)
*   Includes TypoScript configuration for widgets and plugin rendering

