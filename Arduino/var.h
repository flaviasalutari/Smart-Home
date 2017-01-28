// *** 
#define Room                          "Room1"

// *** Pin *** //
#define pirPin  6                                        //pir Pin
#define relayPin 7                                       //relay Pin
#define dhtPin 8                                         //temperature sensor Pin
#define heatingPin 9                                     //heating Pin
#define coolingPin 2                                     //cooling Pin
#define touchPin A0                                      //touch Pin

// *** Pir *** //
#define calibrationTime 10                                //CalibrationTime for Pir

// *** Dht *** //
#define dhtType DHT11                                     //TemperatureSensor Type

// *** Mqtt *** //
#define LightSubscriptionTopic        "8/Room1/Light"                   //LightSubscription
#define TemperatureSubscriptionTopic  "8/Room1/Temperature"             //TemperatureSubscription
#define LightPublishTopic             "8/Room1/Light/Response"          //LightPublish
#define LightPublishStateTopic        "8/Room1/Light/State"             //State
#define TemperaturePublishTopic       "8/Room1/Temperature/Response"    //TemperaturePublish

#define MotionDetectedPublishTopic    "8/Room1/Light/Observe"

#define ResponseToPirRequest          "PIR"
#define ResponseToCommandsOnOff       "OnOffResponse"
#define ResponseToTemp                "TempData"
#define ResponseToCommandsTemp        "TempCommands"

// *** Commands *** //
#define TurnOnLightCommand            "ON"                            //TurnOnLight
#define TurnOffLightCommand           "OFF"                           //TurnOffLight
#define TemperatureRequestCommand     "GetTemp"                       //TemperatureRequest
#define TimeOnLightCommand            "TimeOn"                        //TimeOnLight
#define StartHeatingCommand           "TempUp"                        //TurnHeatingOn
#define StartCoolingCommand           "TempDown"                      //TurnHeatingOff
#define StopHeatingCoolingCommand     "TempLedOff"                    //StopHeatingCooling

#define TurnOnLightNow                "SetOnNow"
#define TurnOffLightNow               "SetOffNow"

// *** For MQTT connection with password *** //
#define User                          "ciao"
#define Password                      "ciao"



