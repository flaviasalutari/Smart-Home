#include <SPI.h>
#include <Ethernet.h>
#include <PubSubClient.h>

#include "var.h"

#include "parseJson.h"

// *** Functions *** //
void callback(char* topic, byte* payload, unsigned int length);
void StartMqtt();
void RunClient();

void ReadMessage(char *message);
void TurnOnLight();
void TurnOffLight();
void TemperatureRequest();
void TimeOnRequest();

void TurnOnHeating();
void TurnOnCooling();
void TurnOffCoolingHeating();
void TurnOnLightByTouch();
void TurnOffLightByTouch();
void MotionDetected();
boolean reconnect();

extern bool ON;
extern bool FlagState;



