var mqttBrokerIp = "192.168.1.254";
var mqttBrokerPort = 9001;
var client = null;

$(document).ready(function () {
    try {
        client = new Paho.MQTT.Client(mqttBrokerIp, mqttBrokerPort, "JavaScriptWebPage");
        client.onConnectionLost = onConnectionLost;
        client.onMessageArrived = onMessageArrived;
        client.connect({ onSuccess: onConnect, onFailure: onFailure });

    } catch (e) {
        console.log(e.message);
    } 
});

// MQTT stuff
function onConnect() {
    // Once a connection has been made, make a subscription and send a message.
    console.log("Successfully connected to MQTT broker on " + mqttBrokerIp + ":" + mqttBrokerPort);
    client.subscribe("+/Light/State");
    var message = new Paho.MQTT.Message("Hello from web");
    message.destinationName = "motionsensor";
    client.send(message);
};
function onConnectionLost(responseObject) {
    if (responseObject.errorCode !== 0)
        console.log("onConnectionLost: " + responseObject.errorMessage);
};
function onMessageArrived(message) {
    console.log("onMessageArrived: " + message.payloadString);

    var json = JSON.parse(message.payloadString);

	try
	{
		if (json['State'] === "ON" )
	    {	
	    	$("[name='"  + json['Room'] + "']").bootstrapSwitch('state',true, true);
	    }

		if (json['State'] === "OFF" )
	    {
	    	$("[name='"  + json['Room'] + "']").bootstrapSwitch('state',false, true);
	    } 
	}
	catch (e) 
	{
        console.log(e.message);
    }        


};
function onFailure(responseObject) {
	alert("Impossible to connect to the Broker. Look the console log for more details. Please Try again later.");
    console.log("onFailure: " + message.payloadString);
}

$('input[type="checkbox"]').on('switchChange.bootstrapSwitch', function(event,state){

	//console.log(event);
	
	if (state)
	{
		var message = new Paho.MQTT.Message('{"command":"ON","Room":"' + this.name +  '"}');
 		message.destinationName =this.name + "/Light";
 		console.log(message.destinationName);
 		message.qos=1;
 		client.send(message);
	}
	else
	{
		var message = new Paho.MQTT.Message('{"command":"OFF","Room":"' + this.name +  '"}');
 		message.destinationName =this.name + "/Light";
 		message.qos=1;
 		client.send(message);
	}


});

