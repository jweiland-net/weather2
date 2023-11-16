..  include:: /Includes.rst.txt

=======
Upgrade
=======

If you upgrade EXT:weather2 to a newer version, please read this section
carefully!

Update from 4.x to 5.0
======================

Add TYPO3 12 Compatibility
Removed older version compatibility
Removed dependency with EXT:static_info_tables
Removed ObjectManager usage from scheduler tasks so after upgrading the
extension its better to remove the old tasks and recreate the tasks. Because
the old serialized version saved in the database.


Update from 3.x to 4.0.0
========================

Add TYPO3 11 compatibility
Remove TYPO3 9 compatibility

We have removed all TYPO3 columns from `ext_tables.sql`. Please execute
DB compare to update the database columns.

Execute Flush Cache in Maintenance section of TYPO3 to update dependency
injection cache.

We require `recordStoragePid` as INT in `weather2` scheduler task. It may
happen that a call to `setPid` will fail, because it is not an INT. That
happens because all scheduler tasks including their earlier variable types
are stored serialized in scheduler. While unserializing the old type (STRING)
will not match the current type (INT) anymore. So please delete that task
and create that task again. Sorry, no UpgradeWizard available for that
operation.

Update from 2.x to 3.0.0
========================

Add TYPO3 10 compatibility
Remove TYPO3 8 compatibility

Nothing to do.

Update from 2.x to 2.0.4
========================

Because of a security patch of TYPO3 all of our weather2 scheduler tasks can not be unserialized anymore. For
you it is not possible anymore to delete, modify or start any task as you can not open scheduler module. Further
no task will be executed anymore by Cronjob.
Please visit Upgrade module of TYPO3 and execute our UpgradeWizard to update our tasks in DB.

Update from 1.x to 2.x
======================

There are breaking changes if you´re updating weather2 from 1.x to 2.x.
This chapter is about how to fix those breaking changes.

#.  Update weather2 using composer or the extension manager.
#.  If you´re using composer you may need to disable and enable the extension using the extension manager or using the database analyzer to get the new database structure.
#.  Clear all caches.
#.  Open the scheduler module
#.  Edit all tasks from type :code:`Call openweathermap.org api`. Directly save them after the click on edit. You don´t need to change any fields inside here.
#.  Remove all tasks from type :code:`Get regions from Deutscher Wetterdienst`. They should have a red background because they no longer exist.
#.  Either create a `dwd weather cell record manually <create-warn-cells-manually>`_ OR create a task from type `Get warn cell records from Deutscher Wetterdienst` set it as single and execute it one time.
#.  Edit all tasks from type `Get weather alerts from Deutscher Wetterdienst` and select the cities/locations you want to fetch. Then save those tasks.
#.  Edit all Plugins from list_type :code:`weather2_weatheralert` (Weather Alerts) and select the cities/locations you want to display. Also check out the new setting :code:`Show preliminary information`.
#.  Clear frontend caches.
