module.exports = {
    // css: {
    //     loaderOptions: {
    //         sass: {
    //             data: `@import "@/styles/base.scss";`
    //         }
    //     }
    // },
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