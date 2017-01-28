#include "mqttclient.h"
#include "temperature.h"
#include "motion.h"
#include "var.h"
#include "touch.h"

//BUONO

void setup() 
{
  pinMode(relayPin, OUTPUT);
  pinMode(heatingPin, OUTPUT);
  pinMode(coolingPin, OUTPUT);
  pinMode(touchPin, OUTPUT);

  SetupMotionPir();
  StartMqtt();
  StartDht();
}

void loop() 
{
  MotionPir();
  RunClient();
  DetectTouchSensor();
}
