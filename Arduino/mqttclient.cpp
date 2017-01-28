#include "mqttclient.h"

//*** MAC & IP ADDR ***//
byte mac[]    = {  0xDE, 0xED, 0xBA, 0xFE, 0xFE, 0xAA };
IPAddress ip(192, 168, 1, 135);
IPAddress server(192, 168, 1, 254);

// Define the client object that permit to receive and send mqtt messages
EthernetClient ethClient;
PubSubClient client(server, 1883, callback, ethClient); 

long lastReconnectAttempt = 0;

// Callback function that permit to retrieve messages from the broker
void callback(char* topic, byte* payload, unsigned int length) {  
  char *message = (char*)malloc(sizeof(char)*length); // The message is a series of char (json string) of length retrived by the callback function
  
  int i=0;
  for (i=0; i<length; i++)
  {
    message[i]=(char)payload[i];
    Serial.print((char)payload[i]);
  }
  Serial.println();
  
  ReadMessage(message); // According to the json string perform the right function
  
  free(message);
}

boolean reconnect()
{
  if (client.connect("arduinoClient1"))                   //Try the connection
  {
      client.publish("Connections","Hello, I'm Arduino");
      
      client.subscribe(LightPublishStateTopic);           //Retrieve the State if arduino goes down (if there is something in retain)
      client.unsubscribe(LightPublishStateTopic);         //Need this topic only for the state
      
      client.subscribe(LightSubscriptionTopic, 1);        //Start the subscription on light topic 
      client.subscribe(TemperatureSubscriptionTopic, 1);  //Start the subscription on light topic
  }
  return client.connected();
}

void StartMqtt()
{
  Ethernet.begin(mac, ip);  //Set up ethernet
}

void RunClient()
{
  if (!client.connected())               // If the client is not yet connected or lost the connection
  {
    long now = millis();
    if (now-lastReconnectAttempt > 5000) // Performs an attempt of connection every 5 second
    {
      lastReconnectAttempt = now;
      if (reconnect())                   // Try the connection
      {
        lastReconnectAttempt = 0;
      }
    }
  }
  else
  {
   client.loop();   // If connected, continuously listen to the broker for new messages to handle
  }
  return; 
}

void ReadMessage(char *message)          // Decode the json string and perform the right function
{
  if (!FlagState)                        // This statement will be entered only once to control 
    {                                    // if the arduino had a past state saved on the broker
      if (ParseState(message))           // Control the state written in the retain message
      {
        ON = true;
        digitalWrite(relayPin, HIGH);
      }
      FlagState = true;                  //Permit to enter this statement only once
      return;
    }
  
    int command = ParseCommand(message); // Decode the json string
    switch(command)
    {
      //*** Turn on light ***//
      case 1:
            TurnOnLight();            
            break;
            
      //*** Turn off light ***//
      case 2:
            TurnOffLight();
            break;
            
      //*** Temperature Request ***//    
      case 3:
            TemperatureRequest();
            break;
            
      //*** TimeOn Request ***//
      case 4:
            TimeOnRequest();
            break;
            
      //*** Heating On Request ***//
      case 5:
            TurnOnHeating();
            break;  
            
      //*** Cooling On Request ***//
      case 6:
            TurnOnCooling();
            break;

     //*** Cooling Heatinf OFF Request ***//
      case 7:
            TurnOffCoolingHeating();
            break;  
      }
}

void TurnOnLight() // Turn on the light and acknoledge the operation
{
  ON = true;
  digitalWrite(relayPin, HIGH);
  char *msg= CreateResponse(1);
  client.publish(LightPublishStateTopic, msg, true);
  return;
}

void TurnOffLight() // Turn off the light and acknoledge the operation
{
  ON = false;
  digitalWrite(relayPin, LOW);
  char *msg= CreateResponse(2);
  client.publish(LightPublishStateTopic, msg, true);
  return;
}

void TemperatureRequest() //Get and send the temperature and humidity values
{
  char *msg= CreateResponse(3);
  delay(100);
  client.publish(TemperaturePublishTopic, msg);
  return;
}

void TimeOnRequest() //Got and sends how much time has passed from the the last motion detected
{
  char *msg = CreateResponse(4);
  client.publish(LightPublishTopic, msg);
  return;
}

void TurnOnHeating() //Turns on the heating system and acknoledge the operation
{
  digitalWrite(heatingPin, HIGH);
  char *msg= CreateResponse(5);
  client.publish(TemperaturePublishTopic, msg);
  return;  
}

void TurnOnCooling() //Turns on the cooling system and acknoledge the operation
{
  digitalWrite(coolingPin, HIGH);
  char *msg= CreateResponse(6);
  client.publish(TemperaturePublishTopic, msg);
  return;  
}

void TurnOffCoolingHeating() //Turns off the heating and cooling system and acknoledge the operation
{
  digitalWrite(coolingPin, LOW);
  digitalWrite(heatingPin, LOW);
  char *msg= CreateResponse(7);
  client.publish(TemperaturePublishTopic, msg);
  return;  
}

void TurnOnLightByTouch() //Send the json string State ON
{
  digitalWrite(relayPin, HIGH);
  char *msg= CreateResponse(1);
  client.publish(LightPublishStateTopic, msg, true);
  return;
}

void TurnOffLightByTouch() //Send the json string Staet OFF
{
  digitalWrite(relayPin, LOW);
  char *msg= CreateResponse(2);
  client.publish(LightPublishStateTopic, msg, true);
  return;
}

void MotionDetected() //Publish that the motion sensor has detected something
{
  char *msg= CreateResponse(10);
  client.publish(MotionDetectedPublishTopic, msg);
  return;
}








