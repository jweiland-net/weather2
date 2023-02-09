..  include:: /Includes.rst.txt

=============
Configuration
=============

Storage Page
============

#.  Create a storage page
#.  Refer to it in the scheduler task
#.  Also refer to it in the plugin

Multi plugin use
----------------

You can configure your scheduler task to save data to a specific storage page that you can later use in the plugin to access data.

Change timezone
---------------

Go into your TYPO3-Install tool and change the timezone under *All configuration* -> *System* -> *phpTimeZone*.
This will change the timezone

Current Weather (Weather Report)
================================

How do I get set up?
--------------------

#.  Create a new http://openweathermap.org account and copy your api key
#.  Download the extension from the TYPO3 extension repository
#.  Enable the scheduler extension in your TYPO3 installation if not already done
#.  Create a new scheduler task from type (class) :code:`Call openweathermap.org api (weather2)`
#.  Configure the scheduler by filling out the required fields. Please Note that the field "name" is later used to only display specific records
#.  Create a new content element with the weather extension plugin selected
#.  Select the measure units and city to display
#.  Add extension template file to your template
#.  Enjoy! ;)

..  figure:: ../Images/CurrentWeather/BackendPluginContentElement.gif
    :alt: Backend plugin content element

    This is how the content element plugin looks like


Weather Alerts
==============

Get warn cells from Deutscher Wetterdienst
------------------------------------------

#.  Go to the scheduler module.
#.  Add a new task and select :code:`Get warn cells from Deutscher Wetterdienst`.
#.  Set this task as single because you have to execute this only once.
#.  Save and exit
#.  Execute the task
#.  If the execution was successful you will see all the region records in your root page.

    ..  figure:: ../Images/WeatherAlert/DwdWarnCellRecordList.jpg
        :width: 350px
        :alt: List of warn cell records

#.  Done

Take a look into `Get Weather Alerts from Deutscher Wetterdienst`_

Create warn cells manually
--------------------------

#.  Download the `warn cell ids csv <https://www.dwd.de/DE/leistungen/gds/help/warnungen/cap_warncellids_csv.csv?__blob=publicationFile&v=1>`_ from DWD and search for your city/location.
#.  Go into the page or list module.
#.  Select your root page (the one with the TYPO3 logo) on the page tree.
#.  Click on :code:`Create new record` and select :code:`DWD warn cell`.

    ..  figure:: ../Images/WeatherAlert/DwdWarnCellNewRecord.jpg
        :width: 350px
        :alt: Create new Weather Alert Region record

#.  Now you can enter the City name and additionally the district of your city.
#.  Done

    ..  figure:: ../Images/WeatherAlert/DwdWarnCellRecord.jpg
        :alt: Create a DWD warn cell record

Take a look into `Get Weather Alerts from Deutscher Wetterdienst`_

..  _get-weather-alerts-from-dwd:

Get Weather Alerts from Deutscher Wetterdienst
----------------------------------------------

#.  Go to the scheduler module.
#.  Add a new task and select :code:`Get weather alerts from Deutscher Wetterdienst`.
#.  You should set recurring as type and e.g. 3600 as frequency to get each hour the latest alerts.
#.  Now you can search for your regions. Please make sure you added the warn cell records (cities/locations) or got them from Deutscher Wetterdienst. Don´t know? Take a look into `Get warn cells from Deutscher Wetterdienst`_ and/or `Create warn cells manually`_.
#.  You can add multiple cities to your selection.

    ..  figure:: ../Images/WeatherAlert/WeatherAlertSchedulerSelectedCities.jpg
        :width: 350px
        :alt: List of selected cities (warn cell records)

#.  If you have a record storage page you can select it additionally. This can be useful for `Multi plugin use`_. Otherwise the records will be saved on root page.

    ..  figure:: ../Images/WeatherAlert/WeatherAlertSchedulerStoragePage.jpeg
        :width: 350px
        :alt: Record storage page

#.  Now you´re done and ready to execute the scheduler.

Read the :ref:`user manual <user-manual>` to get an output on your website.
