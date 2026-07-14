..  include:: /Includes.rst.txt

..  _upgrade:

=======
Upgrade
=======

If you upgrade :file:`EXT:weather2` to a newer version, please read this section
carefully!

..  _upgrade-5-to-6:

Update from 5.x to 6.0
======================

Added TYPO3 13 compatibility.
Removed all weather2 Scheduler task types and replaced them with Symfony console
commands. Your existing weather2 Scheduler tasks will no longer run and should be
deleted. For each one, create a new task of type :guilabel:`Execute console
commands` instead, and select the matching weather2 command
(`weather2:fetch:fromOpenWeatherAPI`, `weather2:fetch:warnCellsFromDeutscherWetterdienstAPI`
or `weather2:fetch:deutscherWetterdienstAPI`). See
:ref:`Console commands overview <admin-manual-configuration-commands>` for the
arguments of each command and whether it should be single or recurring.
There is an upgrade wizard available with this extension to convert the older
list type plugins to custom CType.

..  _upgrade-4-to-5:

Update from 4.x to 5.0
======================

Added TYPO3 12 compatibility.
Removed older version compatibility.
Removed dependency with :ext:`static_info_tables`.
Removed ObjectManager usage from scheduler tasks, so after upgrading the
extension it is better to remove the old tasks and recreate the tasks, because
the old serialized version is saved in the database.

..  _upgrade-3-to-4:

Update from 3.x to 4.0.0
========================

Added TYPO3 11 compatibility.
Removed TYPO3 9 compatibility.

We have removed all TYPO3 columns from :file:`ext_tables.sql`. Please execute
the database compare to update the database columns.

Execute :guilabel:`Flush Cache` in the maintenance section of TYPO3 to update the
dependency injection cache.

We require :php:`recordStoragePid` as :php:`int` in the weather2 scheduler task. It
may happen that a call to :php:`setPid()` will fail, because it is not an :php:`int`.
That happens because all scheduler tasks, including their earlier variable types,
are stored serialized in the scheduler. While unserializing, the old type (string)
will not match the current type (int) anymore. So please delete that task
and create that task again. Sorry, no upgrade wizard is available for that
operation.

..  _upgrade-2-to-3:

Update from 2.x to 3.0.0
========================

Added TYPO3 10 compatibility.
Removed TYPO3 8 compatibility.

Nothing to do.

..  _upgrade-2-to-2-0-4:

Update from 2.x to 2.0.4
========================

Because of a security patch of TYPO3, all of our weather2 scheduler tasks can no
longer be unserialized. For you it is not possible anymore to delete, modify or
start any task, as you can not open the scheduler module. Furthermore no task
will be executed anymore by cronjob.
Please visit the :guilabel:`Upgrade` module of TYPO3 and execute our upgrade
wizard to update our tasks in the database.

..  _upgrade-1-to-2:

Update from 1.x to 2.x
======================

There are breaking changes if you're updating weather2 from 1.x to 2.x.
This chapter is about how to fix those breaking changes.

#.  Update weather2 using Composer or the Extension Manager.
#.  If you're using Composer you may need to disable and enable the extension
    using the Extension Manager or using the database analyzer to get the new
    database structure.
#.  Clear all caches.
#.  Open the scheduler module.
#.  Edit all tasks of type :guilabel:`Call openweathermap.org api`. Directly save
    them after clicking edit. You don't need to change any fields inside here.
#.  Remove all tasks of type :guilabel:`Get regions from Deutscher Wetterdienst`.
    They should have a red background because they no longer exist.
#.  Either
    :ref:`create a warn cell record manually <create-warn-cells-manually>`
    OR create a task of type :guilabel:`Get warn cell records from Deutscher
    Wetterdienst`, set it as single and execute it one time.
#.  Edit all tasks of type :guilabel:`Get weather alerts from Deutscher
    Wetterdienst` and select the cities/locations you want to fetch. Then save
    those tasks.
#.  Edit all plugins with list type `weather2_weatheralert` (Weather Alerts) and
    select the cities/locations you want to display. Also check out the new
    setting :guilabel:`Show preliminary information`.
#.  Clear frontend caches.
