..  include:: /Includes.rst.txt

..  _developer:

================
Developer Corner
================

Target group: **Developers**

The developer part of this documentation

..  _developer-api:

Add custom providers
=====================

Feel free to add custom API providers for weather reports or weather alerts.
You can add them using your own extension. Write your own task or command and use
the models :php:`\JWeiland\Weather2\Domain\Model\CurrentWeather` for weather reports and
:php:`\JWeiland\Weather2\Domain\Model\WeatherAlert` for weather alerts. Add new records
using the Extbase :php:`PersistenceManager` or the TYPO3 :php:`DataHandler`.
