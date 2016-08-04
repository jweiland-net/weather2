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
That relates to Page/User TSconfig, permissions, configuration etc.,
which administrator level users have access to.

Language should be non / semi-technical, explaining, using small examples.


.. _admin-installation:

Installation
------------
How do I get set up?
^^^^^^^^^^^^^^^^^^^^
    #. Create a new http://openweathermap.org account
    #. Download the extension from the TYPO3 extension repository.
    #. Create a new scheduler with the Call openweathermap.org api selected
    #. Configure the scheduler by filling out the required fields
    #. Create a new content element with the weather extension plugin selected
    #. Select the desired measure units to display
    #. Add extension template file to your template
    #. Enjoy! ;)


.. figure:: ../Images/AdministratorManual/ExtensionManager.png
   :alt: Extension Manager

   Extension Manager (caption of the image)

   List of extensions within the Extension Manager also shorten with "EM" (legend of the image)


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
Go into your TYPO3-Install tool and change the timezone.
Currently this will change the timezone for every plugin and your whole TYPO3-Installation.
May be changed in the future

.. _admin-faq:

FAQ
---

Possible subsection: FAQ

Subsection
^^^^^^^^^^

Some subsection

Sub-subsection
""""""""""""""

Deeper into the structure...
