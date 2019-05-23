.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt

.. _developer:

Developer Corner
================

Target group: **Developers**

The developer part of this documentation

.. _developer-api:

Add customer providers
**********************

Feel free to add custom api providers for weather reports or weather alerts.
You can add them using your own extension. Write your own task or command and use
the Models :code:`JWeiland\Weather2\Domain\Model\CurrentWeather` for weather reports and
:code:`JWeiland\Weather2\Domain\Model\WeatherAlert` for weather alerts. Add new records
using the extbase PersistenceManager or the TYPO3 DataHandler.
