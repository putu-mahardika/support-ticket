let mqtt = require('mqtt');
let host = 'mqtt.hjex.co.id';
let port = 443;
let protocol = 'wss://';
let basepath = 'mqtt';
let fullHost = `${protocol}${host}:${port}/${basepath}`;
let client = null;
window.mqttUserKey = '';
window.tableToReload = null;
window.viewToReload = null;
window.customFunctionReload = null;
let baseTopic = '/mchelpdesk/';
const option = {
    username: 'monster_sby',
    password: 'P@ssw0rd'
}

try {
    client = mqtt.connect(fullHost, option);
    // console.clear();
    // console.log(`Conneting to host ${host}:${port}....`);
} catch (error) {
    // console.log('Connection error', error);
}

client.on('connect', (connect) => {
    console.log('Connected');
    client.subscribe(`${baseTopic}#`);
})

client.on('disconnect', (packet) => {
    console.log('Disconnected');
});

client.on('error', (params) => {
    console.log('Error')
})

client.on('message', function (topic, message) {
    if (isValidJson(message.toString())) {
        let data = JSON.parse(message.toString());
        if (topic == `${baseTopic}${mqttUserKey}/tickets` || topic == `${baseTopic}${mqttUserKey}/comments`) {
            playNotifSound();
            reloadNotification();
            Toast.fire({
                icon: 'info',
                title: data.title,
                text: data.text
            });
            if (tableToReload != null) {
                tableToReload.ajax.reload();
            }
            if (typeof viewToReload === "function") {
                viewToReload();
            }
            if (typeof customFunctionReload === "function") {
                customFunctionReload();
            }
        }
    }
})

function isValidJson(string) {
    try {
        JSON.parse(string);
        return true;
    } catch (e) {
        return false;
    }
}
