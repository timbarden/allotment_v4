module.exports = {
    devServer: {
        proxy: {
            '/public': {
                target: 'https://allotment-diary.tim-barden.co.uk',
                changeOrigin: true,
                pathRewrite: {
                    '/public': ''
                }
            }
        }
    }
}