#include <Arduino.h>
#include "touch.h"
#include "mqttclient.h"

bool ON = false;
bool FlagState = false; //Used in mqtt client to retrieve the state if arduino for some reason goes down

void DetectTouchSensor() //Detect if the touch sensor is pressed
{
  int ctsValue = analogRead(touchPin); //Get the voltage value from the touch sensor
  //Serial.println(ctsValue);
  
  if ((ctsValue > 85) && (!ON))        //If the voltage is over the threshold and the button was off
    {
     ON = true;
     TurnOnLightByTouch();             //Publish that the light was setted on
     delay(1000);
     return;  
    }
    
  if ((ctsValue > 85) && (ON))         //If the voltage is over the threshold and the button was on
    {
      ON = false;    
      TurnOffLightByTouch();           //Publish that the light was setted off
      delay(1000);
      return;  
    } 
}

