#include "parseJson.h"
#include "var.h"

int ParseState(char* message) // Decode the retain message of the last state saved
{
  int r;

  StaticJsonBuffer<200> jsonBuffer;                  
  JsonObject& root = jsonBuffer.parseObject(message);
  const char* state = root["State"];

  if ( strcmp(state,"ON") == 0 ) // If the state was "ON" return a 1
  {
    return r=1;
  }
  else
  {
    return r=0;
  }
}

int ParseCommand(char* message) // Decode the json string
{
    int r;
    StaticJsonBuffer<200> jsonBuffer;                  
    JsonObject& root = jsonBuffer.parseObject(message);
    
    const char* command = root["command"];
    
        //***Turn on light, switch case 1***//
        if ( strcmp(command,TurnOnLightCommand) == 0 )
          {
            return r=1;
          }
        //*** Turn of light, switch case 2***//
        if ( strcmp(command,TurnOffLightCommand) == 0 )
          {
            return r=2;
          }

        //*** Temperature Request, switch case 3***//
        if ( strcmp(command,TemperatureRequestCommand) == 0 )
          {
            return r=3;
          }

        //*** TimeOn Request, switch case 4***//
        if ( strcmp(command,TimeOnLightCommand) == 0 )
          {
            return r=4;
          }

        //*** TurnOn Heating, switch case 5***//
        if ( strcmp(command,StartHeatingCommand) == 0 )
          {
            return r=5;
          }

        //*** TurnOn Cooling, switch case 6***//
        if ( strcmp(command,StartCoolingCommand) == 0 )
          {
            return r=6;
          }
       
        //*** TurnOff Cooling and Heating, switch case 7***//
        if ( strcmp(command,StopHeatingCoolingCommand) == 0 )
          {
            return r=7;
          }
          
    //*** Default case ***//
    return 0; 
}

char *CreateResponse(int value) //Create the json string
{
  StaticJsonBuffer<200> jsonBuffer;  
  JsonObject& root = jsonBuffer.createObject();
  
  switch (value)
    {  
    case 1:
    root["Room"] = Room;
    root["State"] = "ON";
    break;
    
    case 2:
    root["Room"] = Room;
    root["State"] = "OFF";
    break;

    case 3:
    root["Room"] = Room;
    root["Response"] = "OK";
    root["Type"] = ResponseToTemp;
    root["Temperature"] = GetTemperature();
    delay(50);
    root["Humidity"] = GetHumidity();   
    break;

    case 4:
    root["Room"] = Room;
    root["Response"] = "OK";
    root["Type"] = ResponseToPirRequest;
    root["LastDetection"] = GetLastIntervalDetection();
    break;

    case 5:
    root["Room"] = Room;
    root["Response"] = "OK";
    root["Type"] = ResponseToCommandsTemp;
    break;

    case 6:
    root["Room"] = Room;
    root["Response"] = "OK";
    root["Type"] = ResponseToCommandsTemp;
    break;

    case 7:
    root["Room"] = Room;
    root["Response"] = "OK";
    root["Type"] = ResponseToCommandsTemp;
    break;

    case 8:
    root["Room"] = Room;
    root["Type"] = TurnOnLightNow;
    break;

    case 9:
    root["Room"] = Room;
    root["Type"] = TurnOffLightNow;
    break;

    case 10:
    root["Room"] = Room;
    root["Type"] = ResponseToPirRequest;
    root["Movement"] = "Yep!";
    break;
    }

    char buffer[256];
    root.printTo(buffer, sizeof(buffer));
    
    return buffer;  
}





