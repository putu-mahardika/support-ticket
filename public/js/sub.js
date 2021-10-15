var mqtt;
var reconnectTimeout = 2000;
var host = "192.168.100.143";
var port = 9001;
var userKey = "agung";
var baseTopic = "/mchelpdesk/";

function onConnect() {
    console.log("Connected");
    mqtt.subscribe(baseTopic + "#");
}

function onMessageArrived(msg){
    // if (msg.destinationName == (`${baseTopic}putu`)) {
    //     console.log(msg.destinationName);
    //     let message = JSON.parse(msg.payloadString);
    //     console.log(message);
    // }
    // else {
    //     console.log('WOW');
    // }
    console.log('MQTT : ' + msg.destinationName);
    console.log(`String : ${baseTopic}putu`);
    console.log(JSON.parse(msg.payloadString));
    console.log("-----------------------------");
}

function MQTTconnect() {
    console.log("connecting to " + host + " " + port);
    var x = Math.floor(Math.random() * 10000);
    var cname = "orderform-" + x;
    mqtt = new Paho.MQTT.Client(host, port, cname);
    mqtt.onMessageArrived = onMessageArrived
    var options = {
        timeout: 3,
        onSuccess: onConnect,
        userName: 'monster_sby',
        password: 'P@ssw0rd'
    };
    mqtt.connect(options);
}
