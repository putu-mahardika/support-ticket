let mqtt,
    reconnectTimeout = 2000,
    host     = "103.31.39.42",   //broker mario
    port     = 9001,
    topicKey = '/mchelpdesk/';

function onFailure(message) {
    // console.error(`Connection Attempt to Host ${host} Failed`);
    Toast.fire({
        icon: 'error',
        title: 'Connection Failed to Socket Host',
    });
    setTimeout(MQTTconnect(), reconnectTimeout);
}

function onMessageArrived(msg) {
    let content = JSON.parse(msg.payloadString);
    Toast.fire({
        icon: 'info',
        title: content.title ?? '',
        text: content.text ?? ''
    });
    if (msg.destinationName == `${topicKey}/tickets` && window.location.pathname == '/admin/tickets') {
        // Reload
    }
    else if (msg.destinationName == `${topicKey}/tickets` && window.location.pathname == '/admin/comments') {
        // Reload
    }
}

function onConnect() {
    // console.log("Connected");
    mqtt.subscribe(`${topicKey}#`);
}

function MQTTconnect(key) {
    topicKey += `${key}/`;
    // console.info(`Connecting to ${host}:${port}`);
    let clientName = `mchelpdesk-${Math.floor(Math.random() * 10000)}`;
    mqtt = new Paho.MQTT.Client(host, port, clientName);
    mqtt.onMessageArrived = onMessageArrived;
    mqtt.connect({
        timeout: 3,
        onSuccess: onConnect,
        onFailure: onFailure,
    });
}
