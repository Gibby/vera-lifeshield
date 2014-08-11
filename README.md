vera-lifeshield
===============


# In Vera
* Create a room for all plugin files you will use, I called mine Security
* Install the Variable Container plugin and/or create one and assign it to the room
* Install the Multiswitch plugin and/or create one and assign it to the room
* In the Multiswitch, update for the button names for Disarm, Arm, Stay, Instant and set them to pulse
* Install the Virtual Motion sensor from here http://forum.micasaverde.com/index.php/topic,23423.0.html
Thanks @RexBeckett
    Note: I removed lines 48 and 49 from I_VMotion.xml prior to uploading to vera
    Note: I have found how to pull the battery status from Lifeshield, will have to look how we can integrate that into the Virtual Motion sensor
* Add a Virtual Motion sensor for each door/window sensor in Lifeshield, naming them the same and keeping note of the device number
* Download the attached php files and place them in a folder on a server that has php and some sort of web server installed. You can even use a .htaccess file in apache to lock down who has permissions to hit these files via URL. My vera has a static private IP, so I just allow that in the .htaccess file



# On webserver
* Copy config.ini.php  do_lifeshield.php  lifeshield.php to a directory you will serve from the webserver
* Update the config.ini.php file, you must update any line with all CAPS after the = sign like, user, pass, base, vera_ip, etc...
* Create file in same directory as lifeshield.php called sensor_names (Or change it and update config.ini.php to match) Format of the file is csv, with first line being "name,dev", example below.
```bash
name,dev
Front Door, 19
Back Door, 20
Kitchen Window, 21
```

* Update permissions on config.ini.php to 0444, the webserver will serve it, but as long as you keep the first line, it will only return a ; in a browser
* Once that is done, just run lifeshield.php from the command line of the server,  If you are running something upstart based, like Ubuntu you can use lifeshield.conf file and place it in /etc/init/, updating the path the the php script, then run
```bash
cd /etc/init.d; ln -s /lib/init/upstart-job lifeshield
start lifeshield
```

# In Vera

Now add 4 triggers under Automation for disarm, arm stay, arm away and arm instant based off of the MultiSwitch toggles add the below luup event for each one, I have a scene called just triggers and put stuff in there.

*Disarm
```bash
luup.inet.wget('http://IP_OF_WEBSERVER/PATH/do_lifeshield.php?f=disarm')
```
*Stay
```bash
luup.inet.wget('http://IP_OF_WEBSERVER/PATH/do_lifeshield.php?f=arm_stay')
```
*Away
```bash
luup.inet.wget('http://IP_OF_WEBSERVER/PATH/do_lifeshield.php?f=arm_away')
```
*Instant
```bash
luup.inet.wget('http://IP_OF_WEBSERVER/PATH/do_lifeshield.php?f=arm_instant')
```

# To do
* Add function back for checking if login failed and after 2 attempts create lock file
* Add function to send alert if unable to login
* Add function to dynamically update sensor_names from Vera
* Add check, so that after pulling status from Vera, we will only send to vera if changed from last time
