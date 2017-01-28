#include "temperature.h"
#include "var.h"

// Define on which pin and what type of temperature measure should perform the temperature and humidity sensor
DHT dht(dhtPin, dhtType); 

float GetTemperature() // Return temperature value
{
  float t = dht.readTemperature();
  return t;
}

float GetHumidity() // Return humidity value
{
  float h = dht.readHumidity();
  return h;
}

void StartDht() // Start the temperature and humidity sensor
{
  dht.begin();
}

