..  include:: /Includes.rst.txt

..  _admin-manual-configuration:

=============
Configuration
=============

..  _admin-manual-configuration-storage-page:

Storage page
============

#.  Create a storage page
#.  Refer to it in the `recordStoragePage` argument when you set up the Scheduler
    task for the relevant console command (see
    :ref:`Console commands overview <admin-manual-configuration-commands>`)
#.  Also refer to it in the plugin

..  _admin-manual-configuration-multi-plugin-use:

Multi plugin use
----------------

You can configure the `recordStoragePage` argument of a console command's Scheduler
task to save data to a specific storage page that you can later use in the plugin to
access data.

..  _admin-manual-configuration-change-timezone:

Change timezone
---------------

Go into your TYPO3 install tool and change the timezone under
:guilabel:`All configuration > System > phpTimeZone`.
This will change the timezone.

..  _admin-manual-configuration-commands:

Console commands overview
=========================

Since version 7.0.0, weather2 no longer ships its own Scheduler task types. All
data fetching is implemented as Symfony console commands instead. To run one
regularly (or once), go to the scheduler module, add a new task, choose the task
type :guilabel:`Execute console commands`
(:php:`\TYPO3\CMS\Scheduler\Task\ExecuteSchedulableCommandTask`), select the desired
weather2 command from the list, and fill in its arguments in the form fields that
appear below.

The following three commands are available. They are listed in the order in which
they should be set up.

..  _admin-manual-configuration-command-warncells:

`weather2:fetch:dwdWarnCells`
-----------------------------

:Purpose:
    Downloads the official DWD warn cell reference list (place name to warn cell ID
    mapping) and stores it in weather2. It does **not** fetch any weather warnings
    itself.

:Arguments:
    None.

:Depends on:
    Nothing. Run this command **first**, before the other two.

:Recommended scheduling:
    Set the Scheduler task type to :guilabel:`Single execution` and run it once.
    Re-run it manually from time to time, since Deutscher Wetterdienst occasionally
    revises warn cell boundaries and IDs. Alternatively, configure it as a
    low-frequency recurring task, e.g. once a month.

..  _admin-manual-configuration-command-dwd-alerts:

`weather2:fetch:dwdAlerts`
--------------------------

:Purpose:
    Fetches the currently active weather warnings from Deutscher Wetterdienst for
    the configured place names and stores them in weather2.

:Arguments:
    #.  `selectedWarnCells` (required) - comma separated list of place names,
        matched against your local warn cell records, e.g. `Pforzheim,Karlsruhe`.
    #.  `recordStoragePage` (required) - the storage page (PID) the fetched records
        should be saved to.
    #.  `pageIdsToClear` (optional) - comma separated list of page IDs whose cache
        should be cleared after the run.

:Depends on:
    :ref:`weather2:fetch:dwdWarnCells
    <admin-manual-configuration-command-warncells>` must have been executed at
    least once beforehand, so the place names can be resolved to warn cell IDs.

:Recommended scheduling:
    Configure it as a :guilabel:`Recurring` task, e.g. every hour, to keep the
    displayed alerts up to date.

..  _admin-manual-configuration-command-openweathermap:

`weather2:fetch:openWeatherMap`
-------------------------------

:Purpose:
    Fetches current weather conditions (temperature, wind, humidity, ...) for one
    city from OpenWeatherMap. It is unrelated to the two Deutscher Wetterdienst
    commands above.

:Arguments:
    #.  `name` (required) - a freely chosen identifier for this report. It is used
        later as the `selection` TypoScript setting to pick which report to
        display, see
        :ref:`Render weather reports inside a fluid template
        <user-manual-fluid-rendering-current-weather>`.
    #.  `city` (required) - city name, e.g. `Munich`.
    #.  `country` (required) - country code, e.g. `DE`.
    #.  `apiKey` (required) - your OpenWeatherMap API key.
    #.  `pageIdsToClear` (optional) - comma separated list of page IDs whose cache
        should be cleared after the run.
    #.  `recordStoragePage` (optional) - the storage page (PID) the fetched record
        should be saved to.

:Depends on:
    Nothing. Independent from the other two commands - set up one task per city.

:Recommended scheduling:
    Configure it as a :guilabel:`Recurring` task, e.g. every 30 to 60 minutes.

..  _admin-manual-configuration-current-weather:

Current weather (weather report)
================================

..  _admin-manual-configuration-current-weather-setup:

How do I get set up?
--------------------

#.  Create a new `openweathermap.org <https://openweathermap.org>`__ account and copy
    your API key
#.  Download the extension from the TYPO3 Extension Repository
#.  Enable the scheduler extension in your TYPO3 installation if not already done
#.  Go to the scheduler module and add a new task of type
    :guilabel:`Execute console commands`, then select the command
    `weather2:fetch:openWeatherMap`
    (see :ref:`Console commands overview <admin-manual-configuration-commands>`)
#.  Fill in the command's arguments. Please note that the `name` argument is later
    used to only display this specific record
#.  Create a new content element with the weather extension plugin selected
#.  Select the measure units and city to display
#.  Add the extension template file to your template
#.  Enjoy! ;)

..  figure:: ../../Images/CurrentWeather/BackendPluginContentElement.gif
    :alt: Backend plugin content element

    This is how the content element plugin looks like


..  _admin-manual-configuration-weather-alerts:

Weather alerts
==============

..  _get-warn-cells-from-deutscher-wetterdienst:

Get warn cells from Deutscher Wetterdienst
------------------------------------------

#.  Go to the scheduler module.
#.  Add a new task of type :guilabel:`Execute console commands` and select the
    command `weather2:fetch:dwdWarnCells`.
#.  Set this task as single execution because you have to execute this only once.
#.  Save and exit
#.  Execute the task
#.  If the execution was successful you will see all the region records in your root
    page.

    ..  figure:: ../../Images/WeatherAlert/DwdWarnCellRecordList.jpg
        :width: 350px
        :alt: List of warn cell records

#.  Done

Take a look into `Get weather alerts from Deutscher Wetterdienst`_

..  _create-warn-cells-manually:

Create warn cells manually
--------------------------

#.  Download the `warn cell IDs CSV
    <https://www.dwd.de/DE/leistungen/gds/help/warnungen/cap_warncellids_csv.csv?__blob=publicationFile&v=1>`__
    from DWD and search for your city/location.
#.  Go into the page or list module.
#.  Select your root page (the one with the TYPO3 logo) on the page tree.
#.  Click on :guilabel:`Create new record` and select :guilabel:`DWD warn cell`.

    ..  figure:: ../../Images/WeatherAlert/DwdWarnCellNewRecord.jpg
        :width: 350px
        :alt: Create new weather alert region record

#.  Now you can enter the city name and additionally the district of your city.
#.  Done

    ..  figure:: ../../Images/WeatherAlert/DwdWarnCellRecord.jpg
        :alt: Create a DWD warn cell record

Take a look into `Get weather alerts from Deutscher Wetterdienst`_

..  _get-weather-alerts-from-dwd:

Get weather alerts from Deutscher Wetterdienst
----------------------------------------------

#.  Go to the scheduler module.
#.  Add a new task of type :guilabel:`Execute console commands` and select the
    command `weather2:fetch:dwdAlerts`.
#.  You should set recurring as type and e.g. 3600 as frequency to get the latest
    alerts every hour.
#.  Fill in the `selectedWarnCells` argument with a comma separated list of place
    names. Please make sure you added the warn cell records (cities/locations) or
    got them from Deutscher Wetterdienst. Don't know? Take a look into
    :ref:`Get warn cells from Deutscher Wetterdienst <get-warn-cells-from-deutscher-wetterdienst>`
    and/or :ref:`Create warn cells manually <create-warn-cells-manually>`.
#.  You can add multiple place names to the `selectedWarnCells` argument, comma
    separated.

    ..  figure:: ../../Images/WeatherAlert/WeatherAlertSchedulerSelectedCities.jpg
        :width: 350px
        :alt: List of selected cities (warn cell records)

#.  If you have a record storage page you can fill in its PID in the
    `recordStoragePage` argument additionally. This can be useful for
    `Multi plugin use`_. Otherwise the records will be saved on the root page.

    ..  figure:: ../../Images/WeatherAlert/WeatherAlertSchedulerStoragePage.jpeg
        :width: 350px
        :alt: Record storage page

#.  Now you're done and ready to execute the scheduler.

Read the :ref:`user manual <user-manual>` to get an output on your website.
