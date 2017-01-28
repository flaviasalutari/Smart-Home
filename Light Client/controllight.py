import datetime
import MySQLdb
import json 
from fileconfig import *

class ControlLight():

	def __init__(self, id, profile,lighton, code): # Define the constructor method
		self.id = id
		self.profile = profile
		self.lighton = lighton
		self.topic = str(code) + "/" + str(self.id) + "/Light"
		self.seconds = 0 # This variable is used to count the time after which ask to Arduino if some movement was percieved in case of profile 2/3

	def AddParameters(self,hon, mon, hoff, moff): # Set the attribute for profile 1
		self.hon = hon
		self.mon = mon
		self.hoff = hoff
		self.moff = moff

	def  AddThreshold(self, threshold): # Set the threshold attribute
		self.threshold=threshold

	def  PresetTurnOn(self, client): 																# Define the moment when turn on the light with a predefined hour
		ctime=datetime.datetime.now() 																# Take the instant time
		if (int(ctime.hour) == self.hon and int(ctime.minute) == self.mon and self.lighton==0):		# If the time correspond to the predefined hour turn on the light
			self.TurnOn(client)
		return

	def  PresetTurnOff(self, client): 																# Define the moment when turn off the light with a predefined hour
		ctime=datetime.datetime.now() 																# Take the instant time
		if (int(ctime.hour) == self.hoff and int(ctime.minute) == self.moff and self.lighton==1): 	# If the time correspond to the predefined hour turn off the light		
			self.TurnOff(client) 																	# Insert a lecture in the database
		return

	def TurnOn(self, client): 								# Turn on the ligth from the system
		if self.lighton==0: 								# If is previously turned off
			client.publish(self.topic, msg_on, qos=1) 		# Send the mqtt message to the arduino to turn on the light
		return 

	def TurnOff(self, client): 				    		  # Turn off the ligth from the system
		if self.lighton==1: 						      # If is previously turned on
			client.publish(self.topic, msg_off, qos=1) 	  # Send the mqtt message to the arduino to turn off the light
		return 

	def SetOn(self,code): 		    # Save the state of the light in the system
		if self.lighton==0: 		# If it is off
			self.lighton=1 			# Save the state
			self.seconds=0
			print "Switching ON the light in room: " + self.id
			self.insertIndB(code)	# Insert a lecture in the database
		return

	def SetOff(self,code): 		    # Save the state of the light in the system
		if self.lighton==1: 		# If it is on
			self.lighton=0 			# Save the state
			self.seconds=0
			print "Switching OFF the light in room: " + self.id
			self.insertIndB(code) 	# Insert a lecture in the database
		return

	def MonitorState(self, client): # This method every amount of time request to the arduino the state of the light (for profile 2 and 3) if it is still on
		if self.lighton==1: # If the light is still on
			self.seconds = self.seconds + timesleep	
			if (self.seconds >= checktimeON):	# Request the time remaining every checktimeON (=10) seconds (it will wait 12 in reality since timesleep = 3: 3 +3 + 3>10)
				client.publish(self.topic, msgTimeOn, qos=1) # With a publish it request the arduino the amount of time since he last saw a movement, to turn off the light
				self.seconds = 0
			print "Seconds passed since I asked to Arduino about last movement: " + str(self.seconds)
		return 

	def TakeDecision(self,client,l_det): # If the time received from the arduino is higher that the threshold the light is turned off
		if(l_det >self.threshold and self.lighton==1): 	# Check if that time is higher than the threshold and the light is still on
			self.TurnOff(client)
		return

	def UpdateThreshold(self, threshold): # Set the updated threshold
		self.threshold=threshold

	def UpdateParameters(self,hon, mon, hoff, moff): # Set the updated parameters
		self.hon = hon
		self.mon = mon
		self.hoff = hoff
		self.moff = moff

	def insertIndB(self,code): # Insert in the DB the room's lectures of light
		now = datetime.datetime.now()
		try:
			db = MySQLdb.connect(hostdB,usernamedB,passworddB,dBname)
			cursor = db.cursor()
			name = "'"+ self.id + "'"
			sql = "INSERT INTO light_read(Cod_read,Status,Year,Month,Day,Hour,Minute,Profile,rooms_Cod_room) VALUES (NULL,%d,%d,%d,%d,%d,%d,%d,(SELECT Cod_room FROM rooms WHERE user_cod_user=%d AND Name = %s))" % (int(self.lighton),int(now.year),int(now.month),int(now.day),int(now.hour),int(now.minute),int(self.profile),code,name)
			try:
				cursor.execute(sql)
				db.commit()
			except:
				sys.exit("Error in inserting in db")
				db.rollback()
			db.close()		 
		except:
			sys.exit("Error in connecting do db")
		
