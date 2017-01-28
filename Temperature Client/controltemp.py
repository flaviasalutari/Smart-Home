import paho.mqtt.client as mqtt
import json

msg  = '{"command": "GetTemp"}'		# Request the temperature and the humidity values
msg1 = '{"command": "TempUp"}'   	# Heating needed
msg2 = '{"command": "TempDown"}'  	# Cooling needed
msg3 = '{"command": "TempOk"}'  	# Temperature in the range
msg4 = '{"command": "TempLedOff"}'  # Turn off the led
msg5 = '{"command": "HumWarning"}'  # Humidity value too low or too high

class ControlTemp():
	
	def __init__(self, id, threshold_low, threshold_high): 	# Define the constructor method
		self.id = id
		self.threshold_low = float(threshold_low)
		self.threshold_high = float(threshold_high)
		self.hum_low = 20.0 								# Humidity values are setted to a constant value because we have not an actuator for that
		self.hum_high = 80.0
		
	def get_temp(self, client, topic): 						# Through the publish, it requests temperature and humidity values to the sensor
		client.publish(topic, msg)
		
	def actuator(self, client, code_owner, temp, hum): 		# According to the thresholds set and the temperature sensed, perform the proper function
		print self.threshold_low
		print self.threshold_high
		print temp
		string_msg = str(code_owner) + "/" + self.id + "/Temperature"

		if temp < self.threshold_low: 
			client.publish(string_msg, msg1)
			print "Heating needed"
			
		elif temp > self.threshold_high:
			client.publish(string_msg, msg2)	
			print "Cooling needed"
			
		else:
			client.publish(string_msg, msg3)	
			print "Temperature in the range"
		
		# For humidity actually we have not the actuator
		if hum < self.hum_low:
			client.publish(string_msg, msg5)
			print "Humidity value too low"
		
		elif hum > self.hum_high:
			client.publish(string_msg, msg5)
			print "Humidity value too high"
			
		else:
			print "Humidity under control"
		
	def UpdateThreshold(self, threshold_low, threshold_high): 	# Set the new thresholds sent by the web
		self.threshold_low = threshold_low
		self.threshold_high = threshold_high
		return
		
	def turn_off(self, client, code_owner): 					# Turn off the led
		string_msg = str(code_owner) + "/" + self.id + "/Temperature"
		client.publish(string_msg, msg4)
		
