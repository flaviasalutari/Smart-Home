#include <ArduinoJson.h>
#include "temperature.h"
#include "motion.h"

int ParseState(char* message);
int ParseCommand(char* message);
char *CreateResponse(int value);

extern bool ON;
