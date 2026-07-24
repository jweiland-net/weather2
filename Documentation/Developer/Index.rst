..  include:: /Includes.rst.txt

..  _developer:

================
Developer Corner
================

Target group: **Developers**

The developer part of this documentation

..  _developer-api:

Add custom providers
====================

Feel free to add custom API providers for weather reports or weather alerts.
You can add them using your own extension. Write your own Symfony console command,
following the pattern of weather2's own commands (see
:ref:`Console commands overview <admin-manual-configuration-commands>`), and use
the models :php:`\JWeiland\Weather2\Domain\Model\CurrentWeather` for weather reports and
:php:`\JWeiland\Weather2\Domain\Model\WeatherAlert` for weather alerts. Add new records
using the Extbase :php:`PersistenceManager` or the TYPO3 :php:`DataHandler`. Register
your command as a `console.command` service so it can be scheduled through TYPO3's
:guilabel:`Execute console commands` Scheduler task, the same way as weather2's own
commands.
