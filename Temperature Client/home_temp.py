from controltemp import ControlTemp 
import json
import MySQLdb
import datetime
import sys

class Home():

	def __init__(self, id, email_owner): 							# Define the constructor method
		self.id = id
		self.nRooms = 0
		self.ControlTemp = []  										# Array where will be saved all the instances of the various rooms
		self.email_owner = email_owner
		self.getCode()												# Function that retrieve the code of the user by the email from the database 

	def AddRoom(self, name, threshold_low, threshold_high): 		# Perform the add in the system of a room
		roomnew = ControlTemp(name, threshold_low, threshold_high) 	# Create a new instance of the ControlTemp class
		self.ControlTemp.append(roomnew)							# Append the instance in the array of the rooms
		self.nRooms = self.nRooms+1									# Increment the number of the rooms in the array

	def DeleteRoom(self, nameRoom):									# Perform the delete in the system of a room
		i = self.SearchRoom(nameRoom)								# Search the index of the room by the name in the array of the rooms
		self.ControlTemp.pop(i)										# Delete the room from the system
		self.nRooms = self.nRooms-1									# Decrease the number of the rooms in the array

	def SearchRoom(self, nameRoom):									# Search the index of the room in the rooms array by the name
		for i in range(self.nRooms):
			try:
				if self.ControlTemp[i].id==nameRoom:
					return i
			except:
				sys.exit("Error in searching for the room")
		return

	def ParseConfiguration(self, msg): # According to the type field in the json string (sent by the web) perform the right function and the insert/update/delete in the database
		try:
			obj_m = json.loads(str(msg))
		except:
			sys.exit("Error in retrieving the message from the web")
			
		if obj_m["Type"]=="Add Room":
			try:
				self.AddRoom(obj_m["Room"], float(obj_m["Threshold_Low"]), float(obj_m["Threshold_High"]))
				self.addRoomindb(obj_m)
			except:
				sys.exit("Error in adding the room")
			
		elif obj_m["Type"]=="Change thresholds":
			pos = self.SearchRoom(obj_m['Room'])
			try:
				self.ChangeThreshold(obj_m, pos)
				self.addRoomindb(obj_m)
			except:
				sys.exit("Error in changing the thresholds")
			
		elif obj_m["Type"]=="Delete Room":
			try:
				self.DeleteRoom(obj_m['Room'])		
				self.DeleteRoomindb(obj_m)
			except:
				sys.exit("Error in deleting the room")
			
		return
			
	def ParseResponse(self, client, msg): 			# Handle the json string where are written temperature and humidity values (coming from the sensor)
		obj_payload = str(msg)
		try:
			obj_msg = json.loads(obj_payload)		# Decode the json string
		except:
			sys.exit("Error in retrieving the message from sensor")
	
		if obj_msg["Type"] == "TempData": 			# Type where are written temperature and humidity values
			pos = self.SearchRoom(obj_msg["Room"])
			temp = float(obj_msg["Temperature"])
			hum = obj_msg["Humidity"]
			print temp
			print hum
			self.insertLecturedb(obj_msg) 									# Insert in the database the values of temperature and humidity
			self.ControlTemp[pos].turn_off(client,self.code_owner) 			# Turn off the led (that represent the air-conditioning)
			self.ControlTemp[pos].actuator(client,self.code_owner,temp,hum)	# According to temperature and humidity values actuate on the air-conditioning
		
		elif obj_msg["Type"] == "TempCommands":
			pass
			
		return
		
	def handleRoom(self, num_room, client): 								# For a given room, request temperature and humidity values
		string = str(self.code_owner) + "/" + self.ControlTemp[num_room].id + "/Temperature" # Identify the topic according to the room ID
		self.ControlTemp[num_room].get_temp(client, string) 				# Request the values
		return

	def ChangeThreshold(self, obj_m, i): 									# Update the thresholds sent by the web used by the actuator
		self.ControlTemp[i].UpdateThreshold(float(obj_m["Threshold_Low"]), float(obj_m["Threshold_High"]))

	def addRoomindb(self, obj_m):
		try:
			db = MySQLdb.connect("192.168.1.2","Fla","","iot")
			cursor = db.cursor()
			name="'"+obj_m["Room"]+"'"
			sql = "SELECT * FROM rooms WHERE rooms.Name = %s AND user_cod_user = %d"% (name, self.code_owner) # This query check if the room already exist (created eventually by the light system)
			cursor.execute(sql)
			if cursor.rowcount==1: # If exist perform the UPDATE in db
				sql = "UPDATE rooms SET rooms.Temperature=1, rooms.Temp_Threshold_Low=%f, rooms.Temp_Threshold_High=%f  WHERE rooms.Name=%s AND rooms.user_cod_user=%d"  % (float(obj_m["Threshold_Low"]), float(obj_m["Threshold_High"]), name, self.code_owner) 
			else: # If doesn't exist perform the INSERT in db
				sql = "INSERT INTO rooms(Cod_room, Name, Light, Temperature, Temp_Threshold_Low, Temp_Threshold_High, user_cod_user) VALUES (NULL, %s, 0, 1, %f, %f, %d)" % (name, float(obj_m["Threshold_Low"]), float(obj_m["Threshold_High"]), self.code_owner)
				
			try:
				cursor.execute(sql)
				db.commit()
			except:
				sys.exit("Error in adding the room into the db")
				db.rollback()
			db.close()	
		except:
			sys.exit("Connection to the db failed ")

	def DeleteRoomindb(self,obj_m):
		try:
			db = MySQLdb.connect("192.168.1.2","Fla","","iot")
			cursor = db.cursor()
			name="'"+obj_m["Room"]+"'"
			sql = "SELECT * from rooms where rooms.Light =1 AND user_cod_user = %d AND Name = %s" % (self.code_owner, name) # This query check if the room has also the subscription to the light system
			cursor.execute(sql)
			sql1 = "DELETE FROM temp_read WHERE rooms_Cod_room = (SELECT Cod_room FROM rooms WHERE user_cod_user=%d AND Name = %s)"% (self.code_owner, name) # Before deleting a room, it is mandatory to delete from the DB all the records about the temperature lectures
			if cursor.rowcount==1:  # If the room has also a subscription to the light system
				sql = "UPDATE rooms SET rooms.Temperature=0  WHERE rooms.Name=%s AND rooms.user_cod_user=%d"  % (name, self.code_owner) # It should be not deleted but only updated setting to zero the subscription on the temperature system
			else: 					# If the room has no subscription to the temperature system
				sql = "DELETE FROM rooms WHERE rooms.Name=%s AND rooms.user_cod_user=%d "% (name, self.code_owner) # The room is deleted from the database
			try:
				cursor.execute(sql1)
				cursor.execute(sql)
				db.commit()
			except:
				sys.exit("Error in deleting the room into the db")
				db.rollback()
			db.close()	
		except:
			sys.exit("Connection to the db failed ")
		
	def getCode(self): # This function get the unique code of the user in the database from the email
		try:
			db = MySQLdb.connect("192.168.1.2","Fla","","iot")
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
			sys.exit("Connection to the db failed ")

	def insertLecturedb(self, obj_m): # Insert lectures of light and humidity in the database
		try:
			db = MySQLdb.connect("192.168.1.2","Fla","","iot")
			cursor = db.cursor()
			name="'"+obj_m["Room"]+"'"
			now = datetime.datetime.now()
			sql = "INSERT INTO temp_read(Cod_read, Val_Temp, Val_Hum, Year, Month, Day, Hour, Minute, rooms_Cod_room) VALUES (NULL, %f, %f, %d, %d, %d, %d, %d, (SELECT Cod_room FROM rooms WHERE rooms.Name=%s AND rooms.user_cod_user = %d))" % (obj_m["Temperature"], obj_m["Humidity"], int(now.year), int(now.month), int(now.day), int(now.hour), int(now.minute), name, self.code_owner)
			try:
				cursor.execute(sql)
				db.commit()
			except:
				db.rollback()
			db.close()
		except:
			sys.exit("Connection to the db failed ")
		
		
