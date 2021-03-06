import paho.mqtt.client as mqtt 		# Library for mqtt communication
from controltemp import ControlTemp
from home_temp import Home
import json 							# Library for handling json strings
import time 							# Library for the sleep function
import MySQLdb							# Library to handle the Database
import sys 								# Library to handle system exceptions

add = "file.txt" 						# Extract from the file the user's email
f = open(add, "r")
try:
	text = f.read()
except:
	sys.exit("Error in the acquisition of the user's email")
f.close()

obj_m = json.loads(str(text))

casa = Home("",obj_m["Email"]) 						# Create an instance of the Home class to handle the various operations

def on_connect(client, userdata, flags, rc):
	print("Connected with result code "+str(rc)) 	# Show the connection result
	client.subscribe("#") 							# Perform the subscription on every topic

def on_message(client, userdata, msg):
	print(msg.topic+" "+str(msg.payload))			# Print the messages coming from the broker for every topic
	
def conf_callback(client, userdata, msg):
	casa.ParseConfiguration(msg.payload)

def control_callback(client, userdata, msg):
	casa.ParseResponse(client, msg.payload)

	
def main():

	client = mqtt.Client() 							# Create the client mqtt instance to send and retrieve messages to and from the broker
	try:
		client.connect("192.168.1.254", 1883, 60) 	# Connection of the mqtt client instance to the broker (in our case the raspberry)
	except:
		sys.exit("Connection to the device failed")

	client.on_connect = on_connect 					# Call the function to show the connection result
	client.on_message = on_message 					# Call the function to print the message coming from the broker
	client.message_callback_add(str(casa.code_owner) + "/Configuration", conf_callback) 			# This topic is used to retrieve all messages coming from the web aplication
	client.message_callback_add(str(casa.code_owner) + "/+/Temperature/Response",control_callback)	# This topic is used to retrieve the response from the rooms about the values of temperature and humidity
	
	try:
		db = MySQLdb.connect("192.168.1.2","Fla","","iot") #Connect to the database to retrieve the room already saved
	except:
		sys.exit("Connection to the database failed")
	cursor = db.cursor()
	email = "'"+casa.email_owner+"'"
	sql = "SELECT * from rooms where rooms.Temperature =1 AND User_cod_user = (SELECT cod_user FROM user WHERE email=%s)" % email # The query search for rooms that have the subscription on temperature services
	cursor.execute(sql)
	for j in range(cursor.rowcount): 				# If there are rooms, they are added to the Home instance that will handle the operatio to do
		data = cursor.fetchone() 					# fetchone takes the data row by row
		casa.AddRoom(data[1], data[10], data[11]) 	# Add the room in the instance
	
	client.loop_start() 							# Enter in the loop state the mqtt client connection
	
	#Starts the infinite loop that check the eventual operations to perform for every room
	while True:
		if casa.nRooms>0:
			for i in range(casa.nRooms):
				casa.handleRoom(i,client)
			time.sleep(3600.0)
	
if __name__ == "__main__":
	main()
	
	
	
