..  include:: /Includes.rst.txt


..  _configuration-typoscript:

==========
TypoScript
==========

..  tip::

    You must configure the extension weather2 first to run this extension correctly.

    You can do this in two ways:

    -   **Via TypoScript constants**, as shown below.
    -   **Via the site configuration in TYPO3 v13**:
        Go to :guilabel:`Site Management > Sites > Your Site > Settings (weather2)`.

    There is also a **backend module for settings** available in TYPO3 13
    (under :guilabel:`Admin Tools > Settings > Extension Configuration`).

    For map-related options, see the
    `maps2 documentation <https://docs.typo3.org/p/jweiland/maps2/master/en-us/>`__.


..  _configuration-typoscript-view:

View
====

..  confval-menu::
    :name: configuration-typoscript-view-settings
    :display: table

    ..  confval:: templateRootPaths
        :name: configuration-typoscript-view-templateRootPaths
        :type: array
        :Path: :typoscript:`plugin.tx_weather2.view.templateRootPaths`
        :default: :file:`EXT:weather2/Resources/Private/Templates/`

        You can override our templates with your own site package extension. We prefer
        to change this value in TypoScript constants.

    ..  confval:: partialRootPaths
        :name: configuration-typoscript-view-partialRootPaths
        :type: array
        :Path: :typoscript:`plugin.tx_weather2.view.partialRootPaths`
        :default: :file:`EXT:weather2/Resources/Private/Partials/`

        You can override our partials with your own site package extension. We prefer
        to change this value in TypoScript constants.

    ..  confval:: layoutsRootPaths
        :name: configuration-typoscript-view-layoutsRootPaths
        :type: array
        :Path: :typoscript:`plugin.tx_weather2.view.layoutsRootPaths`
        :default: :file:`EXT:weather2/Resources/Layouts/Templates/`

        You can override our layouts with your own site package extension. We prefer
        to change this value in TypoScript constants.


..  _configuration-typoscript-persistence:

Persistence
===========

..  confval:: storagePid
    :name: configuration-typoscript-persistence-storagePid
    :type: integer list
    :Path: :typoscript:`plugin.tx_weather2.persistence.storagePid`
    :required: false

    Set this value to one or more storage folders (PIDs) where you have stored the
    records.

    Example: :typoscript:`plugin.tx_weather2.persistence.storagePid = 21,45,3234`


..  _configuration-typoscript-settings:

Settings
========

..  confval:: iconsPath
    :name: configuration-typoscript-settings-iconsPath
    :type: string
    :Path: :typoscript:`plugin.tx_weather2.settings.iconsPath`
    :required: false

    Set this value to a path, relative to the public web root, where your custom
    weather icons are stored, if you want to override the bundled icon set.

    Example: :typoscript:`plugin.tx_weather2.settings.iconsPath = path/to/icons`
