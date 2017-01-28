import json
import sys

info = "configuration.txt" # Extract from the file the IP of rasperry and database
f = open(info, "r")
try:
	text = f.read()
	obj = json.loads(str(text))
	IPbroker = obj["IPbroker"]
	hostdB = obj["hostdB"]
	usernamedB=obj["usernamedB"]
	passworddB=obj["passworddB"]
	dBname=obj["dBname"]
	timesleep = obj["Timesleep"]
	checktimeON = obj["DelayForCheckingTimeON"]
	obj_email = obj["Email"]
	f.close()
except:
	sys.exit("Error in opening the file for retrieving the info")

msg_on='{"command": "ON"}'
msg_off= '{"command": "OFF"}'
msgTimeOn = '{"command": "TimeOn"}'
