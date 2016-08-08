.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _introduction:

Introduction
============


.. _what-it-does:

What does it do?
----------------

This extension is mainly used to display weather data received from openweathermap.org. The extension however
is designed to work with every weather api by creating an own scheduler and map values to the database structure.
Moreover it is possible to organize records using storage pages and a custom identifier for each record.

.. important::

   Please don't forget to repeat your extension's version number in the
   :file:`Settings.yml` file, in the :code:`release` property. It will be
   automatically picked up on the cover page by the :code:`|release|`
   substitution.


.. _screenshots:

Screenshots
-----------
.. figure:: ../Images/FrontendView.gif
   :alt: Frontend view

     Displaying basic information

.. figure:: ../Images/FrontendViewExtended.gif
   :alt: Extended frontend view

       Displaying more information