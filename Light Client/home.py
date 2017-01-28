from controllight import ControlLight 
import json
import MySQLdb
import sys
import time
from fileconfig import *
class Home():

	def __init__(self, id, email_owner): # Define the constructor method
		self.id=id
		self.nRooms=0
		self.ControlLight=[] # Array where will be saved all the instances of the various room
		self.email_owner=email_owner
		self.getCode() # Function that retrieve the code of the user by the email from the database 

	def AddRoom(self, nome, profilo, hon = None, mon = None, hoff = None, moff=None, threshold=None): # According to the profile perform the right "Add" in the system of the room already present in the database
		flag=0
		if profilo==0:
			try:
				self.AddRoom0(nome,profilo,flag)
			except:
				sys.exit("Error in adding room profile 0")
		if profilo==1:
			try:
				self.AddRoom1(nome,profilo,flag,hon,mon,hoff,moff)
			except:
				sys.exit("Error in adding room profile 1")
		if profilo==2:
			try:
				self.AddRoom2(nome,profilo,flag,threshold)
			except:
				sys.exit("Error in adding room profile 2")
		if profilo==3:
			try:
				self.AddRoom3(nome,profilo,flag,threshold)
			except:
				sys.exit("Error in adding room profile 3")

	def AddRoom0(self,nome, profilo, flag1): 			#Perform the add in the system of a room with profile 0
		roomnew = ControlLight(nome, profilo, flag1, self.code_owner)    # Create a new instance of the ControlLight class
		self.ControlLight.append(roomnew) 				# Append the instance in the array of the rooms
		self.nRooms=self.nRooms+1 						#Increment the numbers of the rooms in the array

	def AddRoom1(self,nome, profilo, flag1, hon, mon, hoff, moff):  #Perform the add in the system of a room with profile 1
		roomnew = ControlLight(nome, profilo, flag1, self.code_owner) 				# Create a new instance of the ControlLight class
		roomnew.AddParameters(hon, mon, hoff, moff) 				# Set the parameters for the profile 1
		self.ControlLight.append(roomnew) 							# Append the instance in the array of the rooms
		self.nRooms=self.nRooms+1 									#Increment the numbers of the rooms in the array

	def AddRoom2(self,nome, profilo, flag1, threshold): #Perform the add in the system of a room with profile 2
		roomnew = ControlLight(nome, profilo, flag1, self.code_owner) 	# Create a new instance of the ControlLight class
		roomnew.AddThreshold(threshold) 			 	# Set the threshold for the profile 2
		self.ControlLight.append(roomnew) 			 	# Append the instance in the array of the rooms
		self.nRooms=self.nRooms+1 					 	#Increment the numbers of the rooms in the array

	def AddRoom3(self,nome, profilo, flag1, threshold): #Perform the add in the system of a room with profile 3
		roomnew = ControlLight(nome, profilo, flag1, self.code_owner) 	# Create a new instance of the ControlLight class
		roomnew.AddThreshold(threshold) 				# Set the threshold for the profile 2
		self.ControlLight.append(roomnew) 				# Append the instance in the array of the rooms
		self.nRooms=self.nRooms+1 						#Increment the numbers of the rooms in the array

	def DeleteRoom(self,nameRoom,client): 													# Perform the delete in the system of a room and set the retain message to void because in the broker is still present
		i=self.SearchRoom(nameRoom) 														# Search the index of the room by the name in the array of the rooms
		topic = str(self.code_owner) + "/" + self.ControlLight[i].id + "/Light/State" 		# Identifies the topic to which send the retain's void message
		try:
			self.ControlLight.pop(i) 														# Delete the room from the system
			self.nRooms=self.nRooms-1
			client.publish(topic,"", qos=1, retain=True) 									# Send the retain void message
		except:
			sys.exit("Wrong Delete")

	def DeleteForUpdate(self,nameRoom): 	# Performs the delete from the system of the room that will be added back later
		i=self.SearchRoom(nameRoom) 		# Search the index of the room by the name in the array of the rooms
		try:
			self.ControlLight.pop(i) 		# Delete the room from the system
			self.nRooms=self.nRooms-1
		except:
			sys.exit("wrong delete")

	def handleRoom(self, num_room, client): 									# According to the profile of the room the right function is executed
		if self.ControlLight[num_room].profile == 0:
			return
		elif self.ControlLight[num_room].profile == 1:
			self.ControlLight[num_room].PresetTurnOn(client) 					#Control if is time to turn on the light
			self.ControlLight[num_room].PresetTurnOff(client) 					#Control if is time to turn off the light
		elif self.ControlLight[num_room].profile == 2:
			self.ControlLight[num_room].MonitorState(client) 					#Control if is time to turn off the light for the threshold
		elif self.ControlLight[num_room].profile == 3:
			self.ControlLight[num_room].MonitorState(client) 					#Control if is time to turn off the light for the threshold
		return

	def SearchRoom(self, nameRoom): 	# Search the index of the room in the rooms array by the name
		for i in range(self.nRooms):
			try:
				if self.ControlLight[i].id==nameRoom:
					return i
			except:
				sys.exit("Error in searching for the room")

	def ParseConfiguration(self, client, msg): 	# According to the type and the profile fields in the json string (sent by the web) perform the right function and the insert/update/delete in the database
		try:
			obj_m = json.loads(str(msg))
			if obj_m["Type"]=="AddRoom":
				if obj_m["Profile"]==0:
					self.AddRoom0(obj_m["Room"],obj_m["Profile"],0)
					self.addRoomindb(obj_m)
				elif obj_m["Profile"]==1:
					self.AddRoom1(obj_m["Room"],obj_m["Profile"],0,obj_m["Hour_On"],obj_m["Minute_On"], obj_m["Hour_Off"],obj_m["Minute_Off"])
					self.addRoomindb(obj_m)	
				elif obj_m["Profile"]==2:
					self.AddRoom2(obj_m["Room"],obj_m["Profile"],0,obj_m["Threshold"])
					self.addRoomindb(obj_m)	
				elif obj_m["Profile"]==3:
					self.AddRoom3(obj_m["Room"],obj_m["Profile"],0,obj_m["Threshold"])
					self.addRoomindb(obj_m)
			elif obj_m["Type"]=="Change profile":
				pos=self.SearchRoom(obj_m['Room'])
		 		self.ChangeProfile(obj_m)
		 		self.addRoomindb(obj_m)
			elif obj_m["Type"]=="Change parameters":
				pos=self.SearchRoom(obj_m['Room'])
		 		self.ChangeParameters(obj_m,pos)
				self.addRoomindb(obj_m)	
			elif obj_m["Type"]=="Change threshold":
				pos=self.SearchRoom(obj_m['Room'])
		 		self.ChangeThreshold(obj_m,pos)
				self.addRoomindb(obj_m)
			elif obj_m["Type"]=="Delete Room Light":
				print "delete room light"
				self.DeleteRoom(obj_m['Room'],client)
				self.DeleteRoomindb(obj_m)
		except:
			sys.exit("Error in Configuration")
		return
			
	def ParseResponse(self, client, msg): #Used for profile 2 and 3, retrieve the period of time since last movement detected and then take the eventual decision to turn off the light
		try:
			obj_m = json.loads(str(msg)) # Decode the json 
			pos=self.SearchRoom(obj_m["Room"])		
			if obj_m["Type"]=="PIR": 
				last_det=int(obj_m['LastDetection']) # Save the time passed from the last motion detection
				pos=self.SearchRoom(obj_m['Room'])
				self.ControlLight[pos].TakeDecision(client,last_det) # Take the decision
		except:
			sys.exit("Error in /Response")
		return

	def listen(self, client, msg): 	# Used for profile 3, retrieve the message due to a motion seen by the sensor
		try:
			obj_m = json.loads(str(msg)) # Decode the json 
			pos=self.SearchRoom(obj_m["Room"])
			if (obj_m["Type"]=="PIR" and self.ControlLight[pos].profile==3):
				if obj_m["Movement"] == "Yep!":
					try:
						self.ControlLight[pos].TurnOn(client) # Turn on the light 
					except:
						sys.exit("Error in switching the light when motion detected with profile 3")
		except:
			sys.exit("Error in /Observe")
		return


	def overAll(self, msg): # Used to detect a change of state in light (and update the lighton) from a retain message 
		try:
			obj_m = json.loads(str(msg)) # Decode the json 
			pos=self.SearchRoom(obj_m['Room'])
			if obj_m["State"]=="ON":
				try:			
					self.ControlLight[pos].SetOn(self.code_owner) # Set on the light (for the system)
				except:
					print "Error in setting on the light"
			elif obj_m["State"]=="OFF":
				try:
					self.ControlLight[pos].SetOff(self.code_owner) # Set off the light (for the system)
				except:
					sys.exit("Error in setting off the light")
		except:
			print "I'm in except but nothing" # The message is empty due to the retain of the delete function: it does not find any room matching with the obj[State]!
											  # This is not an error
		return

	def ChangeProfile(self,obj_m): # Perform the change profile in the system (deleting and then adding back)
		self.DeleteForUpdate(obj_m["Room"])
		if obj_m["Profile"]==0:
			self.AddRoom0(obj_m["Room"],obj_m["Profile"],0)
		if obj_m["Profile"]==1:
			self.AddRoom1(obj_m["Room"],obj_m["Profile"],0,obj_m["Hour_On"],obj_m["Minute_On"], obj_m["Hour_Off"],obj_m["Minute_Off"])
		elif obj_m["Profile"]==2:
			self.AddRoom2(obj_m["Room"],obj_m["Profile"],0,obj_m["Threshold"])
		elif obj_m["Profile"]==3:
			self.AddRoom3(obj_m["Room"],obj_m["Profile"],0,obj_m["Threshold"])

	def ChangeParameters(self,obj_m,i): #Update the parameters for profile 1
		try:
			self.ControlLight[i].UpdateParameters(obj_m["Hour_On"],obj_m["Minute_On"],obj_m["Hour_Off"],obj_m["Minute_Off"])
		except:
			sys.exit("Error in updating parameters")

	def ChangeThreshold(self,obj_m,i): #update the threshold for profile 2 or 3
		try:
			self.ControlLight[i].UpdateThreshold(obj_m["Threshold"])
		except:
			sys.exit("Error in changing the threshold")


	def addRoomindb(self,obj_m):
		try:
			db = MySQLdb.connect(hostdB,usernamedB,passworddB,dBname)
			cursor = db.cursor()
			name="'"+obj_m["Room"]+"'"
			sql = "SELECT * from rooms where rooms.Name = %s AND user_cod_user = %d"% (name, self.code_owner) # this query check if the room already exist (created eventually by the temperature system)
			time.sleep(0.3) 			# 300 ms to avoid simultaneously delete of both temp and light
			cursor.execute(sql)
			if cursor.rowcount==1:  	# If exist perform the UPDATE in db
				if obj_m["Profile"]==0:
					sql = "UPDATE rooms SET rooms.light=1, rooms.profile=0, rooms.Hour_On=NULL, rooms.Minute_On=NULL,rooms.Hour_Off=NULL, rooms.Minute_Off=NULL, rooms.Threshold=NULL WHERE rooms.Name=%s AND user_cod_user = %d "  % (name, self.code_owner) 
				elif obj_m["Profile"]==1:
					sql = "UPDATE rooms SET rooms.light=1, rooms.profile=1, rooms.Hour_On=%d, rooms.Minute_On=%d,rooms.Hour_Off=%d, rooms.Minute_Off=%d, rooms.Threshold=NULL WHERE rooms.Name=%s AND user_cod_user =%d"  % (obj_m["Hour_On"],obj_m["Minute_On"], obj_m["Hour_Off"],obj_m["Minute_Off"],name,self.code_owner)
				elif obj_m["Profile"]==2: 
					sql = "UPDATE rooms SET rooms.light=1, rooms.profile=2, rooms.Hour_On=NULL, rooms.Minute_On=NULL,rooms.Hour_Off=NULL, rooms.Minute_Off=NULL,rooms.Threshold=%d WHERE rooms.Name=%s AND user_cod_user = %d"  % (obj_m["Threshold"],name,self.code_owner) 
				elif obj_m["Profile"]==3:
					sql = "UPDATE rooms SET rooms.light=1, rooms.profile=3, rooms.Hour_On=NULL, rooms.Minute_On=NULL,rooms.Hour_Off=NULL, rooms.Minute_Off=NULL, rooms.Threshold=%d WHERE rooms.Name=%s AND user_cod_user = %d"  % (obj_m["Threshold"],name,self.code_owner) 
			else:
				if obj_m["Profile"]==0:	# If doesn't exist perform the INSERT in db
					sql = "INSERT INTO rooms(Cod_room,Name,Light,Temperature,Profile,User_cod_user) VALUES (NULL,%s,1,0,0,%d)" % (name,self.code_owner)
				elif obj_m["Profile"]==1:
					sql = "INSERT INTO rooms(Cod_room,Name,Light,Temperature,Profile,Hour_On,Minute_On,Hour_Off,Minute_Off,User_cod_user) VALUES (NULL,%s,1,0,1, %d, %d, %d, %d,%d)" % (name,obj_m["Hour_On"],obj_m["Minute_On"], obj_m["Hour_Off"],obj_m["Minute_Off"],self.code_owner)
				elif obj_m["Profile"]==2:
					sql = "INSERT INTO rooms(Cod_room,Name,Light,Temperature,Profile,Threshold,User_cod_user) VALUES (NULL,%s,1,0,2, %d,%d)" % (name,obj_m["Threshold"],self.code_owner)
				elif obj_m["Profile"]==3:
					sql = "INSERT INTO rooms(Cod_room,Name,Light,Temperature,Profile,Threshold,User_cod_user) VALUES (NULL,%s,1,0,3, %d,%d)" % (name,obj_m["Threshold"],self.code_owner)
			try:
				cursor.execute(sql)
				db.commit()
				print "Room added correctly in the db"
			except:
				sys.exit("Error in adding the room in the db")
				db.rollback()
			db.close()	
		except:
			sys.exit("Error in connecting to db")

	def DeleteRoomindb(self,obj_m):
		try:
			db = MySQLdb.connect(hostdB,usernamedB,passworddB,dBname)
			cursor = db.cursor()
			name="'"+obj_m["Room"]+"'"
			sql = "SELECT * FROM rooms where rooms.Name = %s AND user_cod_user = %d AND rooms.Temperature =1"% (name,self.code_owner) # This query check if the room has also the subscription to the temperature system
			time.sleep(0.3) #100 ms to avoid simultaneously delete of both temp and light
			cursor.execute(sql)
			sql1 = "DELETE FROM light_read WHERE rooms_Cod_room = (SELECT Cod_room FROM rooms WHERE user_cod_user=%d AND Name = %s)"% (self.code_owner, name) # Before deleting a room, it is mandatory to delete from the DB all the records about the light lectures
			if cursor.rowcount==1: # If the room has also a subscription to the temperature system
				sql = "UPDATE rooms SET rooms.Light=0  WHERE rooms.Name=%s AND rooms.user_cod_user=%d"  % (name, self.code_owner) # It should be not deleted but only updated setting to zero the subscription on the light system
			else: # If the room has no subscription to the temperature system
				sql = "DELETE FROM rooms WHERE rooms.Name=%s AND rooms.user_cod_user=%d "% (name, self.code_owner) # The room is deleted from the database
			try:
				cursor.execute(sql1)
				cursor.execute(sql)
				db.commit()
			except:
				sys.exit("Error in deleting the room from the db")
				db.rollback()
			db.close()	
		except:
			sys.exit("Error in connecting to db")
		
	def getCode(self): # This function get the unique code of the user in the database from the email
		try:
			db = MySQLdb.connect(hostdB,usernamedB,passworddB,dBname)
			cursor = db.cursor()
			email="'"+self.email_owner+"'"
			sql = "SELECT cod_user from user WHERE email = %s" % email
			try:
				cursor.execute(sql)
				data = cursor.fetchone()
				self.code_owner=data[0]
			except:
				sys.exit("Error in getting the code from the db")
				db.rollback()
			db.close()	
		except:
			sys.exit("Error in connecting to db")		
		