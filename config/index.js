// config/index.js
module.exports = {
    dev: {
        proxyTable: {
            '/public/dataLoad.php': {
                target: 'https://allotment-diary.tim-barden.co.uk/',
                changeOrigin: true
            },
            '/public/dataSave.php': {
                target: 'https://allotment-diary.tim-barden.co.uk/',
                changeOrigin: true
            }
        },
    }
}