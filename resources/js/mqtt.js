let mqtt = require('mqtt');
let host = '192.168.100.143';
let port = 9001;
let protocol = 'mqqt://';
let fullHost = `${protocol}${host}:${port}`;
let client = null;
window.mqttUserKey = '';
window.tableToReload = null;
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
    // console.log(packet);
});

client.on('error', (params) => {
    // console.log('Error', params)
})

client.on('message', function (topic, message) {
    if (isValidJson(message.toString())) {
        let data = JSON.parse(message.toString());
        if (topic == `${baseTopic}${mqttUserKey}/tickets`) {
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
