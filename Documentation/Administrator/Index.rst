.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _admin-manual:

Administrator Manual
====================

Target group: **Administrators**

Describes how to manage the extension from an administrator point of view.

General
*******
.. _general-configuration:

Configuration
-------------
Storage Page
^^^^^^^^^^^^
    #. Create a storage page
    #. Refer to it in the scheduler task
    #. Also refer to it in the plugin

Multi plugin use
^^^^^^^^^^^^^^^^
You can configure your scheduler task to save data to a specific storage page that you can later use in the plugin to access data.
There is also an option to display only specific rows. Please use the field "name" to do this.
In the plugin you can configure which "name" to use. To select the latest entry use the empty field.

Change timezone
^^^^^^^^^^^^^^^
Go into your TYPO3-Install tool and change the timezone under *All configuration* -> *System* -> *phpTimeZone*.
This will change the timezone

Current Weather (Weather Report)
********************************

Installation
------------
How do I get set up?
^^^^^^^^^^^^^^^^^^^^
    #. Create a new http://openweathermap.org account and copy your api key
    #. Download the extension from the TYPO3 extension repository
    #. Enable the scheduler extension in your TYPO3 installation if not already done
    #. Create a new scheduler task from type (class) :code:`Call openweathermap.org api (weather2)`
    #. Configure the scheduler by filling out the required fields. Please Note that the field "name" is later used to only display specific records
    #. Create a new content element with the weather extension plugin selected
    #. Select the measure units and city to display
    #. Add extension template file to your template
    #. Enjoy! ;)

.. figure:: ../Images/CurrentWeather/BackendPluginContentElement.gif
   :alt: Backend plugin content element

   This is how the content element plugin looks like


Weather Alerts
**************

Installation
------------

Get warn cells from Deutscher Wetterdienst
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    1. Go to the scheduler module.
    2. Add a new task and select :code:`Get regions from Deutscher Wetterdienst`.
    3. Set this task as single because you have to execute this only once.
    4. Save and exit
    5. Execute the task
    6. If the execution was successful you will see all the region records in your root page.

    .. figure:: ../Images/WeatherAlert/DwdWarnCellRecordList.jpg
       :alt: List of warn cell records

    7. Done

Take a look into `Get Weather Alerts from Deutscher Wetterdienst`_

Create warn cells manually
^^^^^^^^^^^^^^^^^^^^^^^
    1. Download the `warn cell ids csv <https://www.dwd.de/DE/leistungen/gds/help/warnungen/cap_warncellids_csv.csv?__blob=publicationFile&v=1>` from DWD and search for your city/location.
    2. Go into the page or list module.
    3. Select your root page (the one with the TYPO3 logo) on the page tree.
    4. Click on :code:`Create new record` and select :code:`Weather Alert Region`.

    .. figure:: ../Images/WeatherAlert/DwdWarnCellNewRecord.jpg
       :width: 350px
       :alt: Create new Weather Alert Region record

    5. Now you can enter the City name and additionally the district of your city.
    6. Done

    .. figure:: ../Images/WeatherAlert/DwdWarnCellRecord.jpg
       :alt: Create a DWD warn cell record

Take a look into `Get Weather Alerts from Deutscher Wetterdienst`_

.. _get-weather-alerts-from-dwd:

Get Weather Alerts from Deutscher Wetterdienst
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    1. Go to the scheduler module.
    2. Add a new task and select :code:`Get weather alerts from Deutscher Wetterdienst`.
    3. You should set recurring as type and e.g. 3600 as frequency to get each hour the latest alerts.
    4. Now you can search for your regions. Please make sure you added the warn cell records (cities/locations) or got them from Deutscher Wetterdienst. Don´t know? Take a look into `Get warn cells from Deutscher Wetterdienst`_ and/or `Create warn cells manually`_.
    5. You can add multiple cities to your selection.

    .. figure:: ../Images/WeatherAlert/WeatherAlertSchedulerSelectedCities.jpg
       :width: 350px
       :alt: List of selected cities (warn cell records)

    6. If you have a record stroage page you can select it additionally. This can be useful for `Multi plugin use`_. Otherwise the records will be saved on root page.

    .. figure:: ../Images/WeatherAlert/WeatherAlertSchedulerStoragePage.jpeg
       :width: 350px
       :alt: Record storage page

    7. Now you´re done and ready to execute the scheduler.

Read the :ref:`user manual <user-manual>` to get an output on your website.

Update from 1.x to 2.x
***********************

There are breaking changes if you´re updating weather2 from 1.x to 2.x.
This chapter is about how to fix those breaking changes.

    1. Update weather2 using composer or the extension manager.
    2. If you´re using composer you may need to disable and enable the extension using the extension manager or using the database analyzer to get the new database structure.
    3. Clear all caches.
    4. Open the scheduler module
    5. Edit all tasks from type :code:`Call openweathermap.org api`. Directly save them after the click on edit. You don´t need to change any fields inside here.
    6. Remove all tasks from type :code:`Get regions from Deutscher Wetterdienst`. They should have a red background because they no longer exist.
    7. Either create a `dwd weather cell record manually <Create warn cells manually_>`_ OR create a task from type `Get warn cell records from Deutscher Wetterdienst` set it as single and execute it one time.
    8. Edit all tasks from type `Get weather alerts from Deutscher Wetterdienst` and select the cities/locations you want to fetch. Then save those tasks.
    9. Edit all Plugins from list_type :code:`weather2_weatheralert` (Weather Alerts) and select the cities/locations you want to display. Also check out the new setting :code:`Show preliminary information`.
    10. Clear frontend caches.
