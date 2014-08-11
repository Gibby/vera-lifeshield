; <?php exit(); __halt_compiler();
[AUTH]
USER = UPDATE_WITH_LIFESHIELD_USERNAME
PASS = UPDATE_WITH_LIFESHIELD_PASSWORD
LOGIN_URL = "https://apps.lifeshield.com/myingridStruts/login.do"
INST_URL = "https://apps.lifeshield.com/myingridStruts/setArmState.do?armstate=armstayinst"
AWAY_URL = "https://apps.lifeshield.com/myingridStruts/setArmState.do?armstate=armaway"
STAY_URL = "https://apps.lifeshield.com/myingridStruts/setArmState.do?armstate=armstay"
DISARM_URL = "https://apps.lifeshield.com/myingridStruts/setArmState.do?armstate=disarm"

[CONFIG]
BASE = "UPDATE_WITH_WHERE_YOU_WANT_TO_RUN_FROM_LIKE /var/www/html or something else"
SLEEP = 1
LOCK_FILE = lock
CSV_FILE = sensor_names
VERA_IP = "VERA_IP_ADDRESS_INTERNAL"
FAULTED_URL = "https://apps.lifeshield.com/myingridStruts/getFaultedItems.do"
DATE_FORMAT = md_His
TRIPPED_URL = "&serviceId=urn:micasaverde-com:serviceId:SecuritySensor1&Variable=Tripped&Value=1"
CLEAR_URL = "&serviceId=urn:micasaverde-com:serviceId:SecuritySensor1&Variable=Tripped&Value=0"
VERA_STATUS_ID = UPDATE_WITH_DEVICE_ID_IN_VERA_OF_VIRTUAL_CONTAINER_FOR_STATUS_UPDATES
VERA_STATUS_VAR = UPDATE_WITH_VARIABLE_NUMBER_OF_VIRTUAL_CONTAINER_FOR_STATUS_UPDATES_I_USE_1
VERA_STATUS_NAME = Status
VERA_STATUS_SERVICE_ID = "&serviceId=urn:upnp-org:serviceId:VContainer1"
VERA_FUTURE_STATUS_ID = UPDATE_WITH_DEVICE_ID_IN_VERA_OF_VIRTUAL_CONTAINER_FOR_STATUS_UPDATES
VERA_FUTURE_STATUS_VAR = UPDATE_WITH_VARIABLE_NUMBER_OF_VIRTUAL_CONTAINER_FOR_FUTURE_STATUS_UPDATES_I_USE_2
VERA_FUTURE_STATUS_NAME = FutureStatus
VERA_FUTURE_STATUS_SERVICE_ID = "&serviceId=urn:upnp-org:serviceId:VContainer1"
VERA_DATE_ID = UPDATE_WITH_DEVICE_ID_IN_VERA_OF_VIRTUAL_CONTAINER_FOR_STATUS_UPDATES_TIME_STAMP_CAN_BE_SAME_AS_ABOVE
VERA_DATE_VAR = UPDATE_WITH_VARIABLE_NUMBER_OF_VIRTUAL_CONTAINER_FOR_TIME_STAMP_UPDATES_I_USE_5
VERA_DATE_NAME = LastUpdate
VERA_DATE_SERVICE_ID = "&serviceId=urn:upnp-org:serviceId:VContainer1"
