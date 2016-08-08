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

.. _admin-installation:

Installation
------------
How do I get set up?
^^^^^^^^^^^^^^^^^^^^
    #. Create a new http://openweathermap.org account and copy your api key
    #. Download the extension from the TYPO3 extension repository
    #. Enable the scheduler extension in your TYPO3 installation if not already done
    #. Create a new scheduler with the Call openweathermap.org api selected with the scheduler module
    #. Configure the scheduler by filling out the required fields. Please Note that the field "name" is later used to only display specific records
    #. Create a new content element with the weather extension plugin selected
    #. Select the desired measure units to display
    #. If you want to select a display record to display only records with the same name that you specified earlier
    #. Add extension template file to your template
    #. Enjoy! ;)


.. figure:: ../Images/AdministratorManual/BackendPluginContentElement.gif
   :alt: Backend plugin content element

   This is how the content element plugin looks like

.. _admin-configuration:

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
This will change the timezone for every plugin and your whole TYPO3-Installation.