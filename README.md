# TYPO3 Weather Extension #

The weather extension is designed to display data received from http://openweathermap.org
However it is also possible to use any other weather API by creating your own scheduler that maps the API response to the database structure of the extension.

## How do I get set up? ##

1. Create a new http://openweathermap.org account 
1. Download the extension from the TYPO3 extension repository.
1. Create a new scheduler with the **Call openweathermap.org api** selected
1. Configure the scheduler by filling out the required fields
1. Create a new content element with the weather extension plugin selected
1. Select the desired measure units to display
1. Add extension template file to your template
1. Enjoy! ;)

## Configuration ##
### Storage Page ###
1. Create a storage page
1. Refer to it in the scheduler task
1. Also refer to it in the plugin

### Multi plugin use ###
You can configure your scheduler task to save data to a specific storage page that you can later use in the plugin to access data.
There is also an option to display only specific rows. Please use the field "name" to do this. In the plugin you can configure which "name" to use. To select the latest entry use the empty field.

### Change timezone ###
Go into your TYPO3-Install tool and change the timezone. Currently this will change the timezone for every plugin and your whole TYPO3-Installation. May be changed in the future

### Add own API-Provider ###
To add your own api weather service please use the template files provided in /Classes/Task/. The only you need to do is to map your values for our database. Please use our mapping array as guidance. Please note that values must be converted into the metric system for the extension to work properly. We recommend using our /Classes/Service/WeatherConverterService for this task.


### Who do I talk to? ###

Please use the Issue Tracker to submit bugs/features/etc

### Contact ###
#### Pascal Rinker ####
prinker@jweiland.net

projects@jweiland.net

#### Markus Kugler ####
mkugler@jweiland.net

projects@jweiland.net