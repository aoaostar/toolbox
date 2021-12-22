const plugins_get = (categoryId, star) => {
    return httpGet('/api/plugins', {
        categoryId: categoryId,
        star: star,
    })
}
const star = (pluginAlias, action = 'add') => {
    return httpGet('/api/plugin/star', {
        alias: pluginAlias,
        action: action,
    })
}
const install_api = {
    database: (params) => {
        return httpPost('/install/database', params)
    },
    oauth: (params) => {
        return httpPost('/install/oauth', params)
    },
    init_data: (params) => {
        return httpPost('/install/init_data', params)
    }
}