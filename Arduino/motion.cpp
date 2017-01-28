#include <Arduino.h>
#include "motion.h"
#include "var.h"
#include "mqttclient.h"

//start detection, stop detection and interval detection
long unsigned int StartDetection,StopDetection, IntervalDetection;          
 
bool lockLow = true;
bool takeLowTime;  

void SetupMotionPir()
{
    pinMode(pirPin, INPUT); // The program starts listen on pin of the pir
    //Calibration
    Serial.begin(115200);
    Serial.print("calibrating sensor ");
    for(int i = 0; i < calibrationTime; i++)  // Take some time to set up the motion sensor
      {
        Serial.print(".");
        delay(1000);
      }
    Serial.println(" done");
    Serial.println("SENSOR ACTIVE");
    delay(50);
}

void MotionPir()                        // Check the status of the motion pir (if it has detected some movement)
{
     if(digitalRead(pirPin) == HIGH){
       if(lockLow){                     // This variable is used to not enter continuously in this statement but only once per motion detected
         MotionDetected();              // Publish on the broker that it has detected a motion
 
         lockLow = false;            
         Serial.println("---");
         Serial.print("motion detected at ");
         StartDetection = millis()/1000;
         Serial.print(StartDetection);
         Serial.println(" sec"); 
         delay(50);
         }         
         takeLowTime = true;
       }
     if(digitalRead(pirPin) == LOW){     // If a motion is not detected   
       if(takeLowTime){                  // Perform this statement only once
        takeLowTime = false;
        lockLow = true;
       
        StopDetection = millis()/1000;
                                
        Serial.print("motion ended at ");//output
        Serial.print(StopDetection);
        Serial.println(" sec");
        delay(50);        
        }

       }
    //Last Detection
    IntervalDetection = millis()/1000 - StopDetection ;

      
}

//Return how much time has passed from the last detection
long unsigned int GetLastIntervalDetection()
{
  return IntervalDetection;
}

