..  include:: /Includes.rst.txt


..  _configuration:

=============
Configuration
=============

..  tip::

    You must configure the extension weather2 first to run this extension correctly.

    You can do this in two ways:

    - **Via TypoScript Constants**, like shown below.
    - **Via the Site Configuration in TYPO3 v13**:
      Go to **Site Management → Sites → Your Site → Settings (weather2)**

    There is also a **Backend Module for settings** available in TYPO3 13
    (under **Admin Tools → Settings → Extension Configuration**).

    For map-related options, see: `Maps2 Documentation <https://docs.typo3.org/p/jweiland/maps2/master/en-us/>`_


View
====

view.templateRootPaths
----------------------

Default: Value from Constants *EXT:weather2/Resources/Private/Templates/*

You can override our Templates with your own SitePackage extension. We prefer to change this value in TS Constants.

view.partialRootPaths
---------------------

Default: Value from Constants *EXT:weather2/Resources/Private/Partials/*

You can override our Partials with your own SitePackage extension. We prefer to change this value in TS Constants.

view.layoutsRootPaths
---------------------

Default: Value from Constants *EXT:weather2/Resources/Layouts/Templates/*

You can override our Layouts with your own SitePackage extension. We prefer to change this value in TS Constants.


Persistence
===========

persistence.storagePid
----------------------

Set this value to a Storage Folder (PID) where you have stored the records.

Example: `plugin.tx_weather2.persistence.storagePid = 21,45,3234`


Settings
========

settings.iconsPath
------------------

Set this value to a Storage Folder (PID) where you have stored the records.

Example: `plugin.tx_weather2.settings.iconsPath = path/to/icons`
