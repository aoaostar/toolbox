const $ = layui.jquery;

const request = (url, data, params) => {

    return new Promise((resolve, reject) => {
        contentType = 'application/x-www-form-urlencoded';
        if (data !== null && data !== undefined && (data.length > 0 || Object.keys(data).length > 0) && typeof data == 'object') {
            data = JSON.stringify(data)
            contentType = 'application/json'
        }
        $.ajax({
            url: url,
            type: params && params.type || 'post',
            dataType: params && params.dataType || 'JSON',
            contentType: contentType,
            data: data,
            success: function (res) {
                resolve(res)
            },
            error: function (res) {
                reject(res.message)
            }
        });
    });

}
const httpGet = (url) => {
    return request(url, {}, {
        type: 'GET',
    }).then(res => {
        if (res.status !== 'ok') {
            $message.error(res.message);
        }
        return res
    })
}
const httpDelete = (url, data) => {
    return request(url, data, {
        type: 'DELETE',
    }).then(res => {
        if (res.status !== 'ok') {
            $message.error(res.message);
        }
        return res
    })
}
const httpPost = (url, data) => {
    return request(url, data, {
        type: 'POST',
    }).then(res => {
        if (res.status !== 'ok') {
            $message.error(res.message);
        }
        return res
    })
}
const httpPut = (url, data) => {
    return request(url, data, {
        type: 'PUT',
    }).then(res => {
        if (res.status !== 'ok') {
            $message.error(res.message);
        }
        return res
    })
}
const httpPatch = (url, data) => {
    return request(url, data, {
        type: 'PATCH',
    }).then(res => {
        if (res.status !== 'ok') {
            $message.error(res.message);
        }
        return res
    })
}

const categories_get = () => {
    return httpGet('/master/categories')
}


const category_update = (data) => {

    return httpPut('/master/category', data)
}

const category_create = (data) => {
    return httpPost('/master/category', data)
}
const category_get = (id) => {
    return httpGet('/master/category?id=' + id)
}

const category_delete = (id) => {
    return httpDelete('/master/category?id=' + id)
}
const plugin_update = (data) => {

    return httpPut('/master/plugin', data)
}

const plugin_get = (id) => {
    return httpGet('/master/plugin?id=' + id)
}


const plugin_create = (data) => {
    return httpPost('/master/plugin', data)
}

const plugin_delete = (id) => {
    return httpDelete('/master/plugin?id=' + id)
}

const cloud_plugins_get = () => {
    return httpGet('/master/cloud/plugins')
}
const cloud_plugin_get = (id) => {
    return httpGet('/master/cloud/plugin?id=' + id)
}
const cloud_plugin_install = (id) => {
    return httpGet('/master/cloud/plugin_install?id=' + id)
}

const cloud_categories_get = () => {
    return httpGet('/master/cloud/categories')
}
const cloud_releases_get = () => {
    return httpGet('/master/cloud/releases')
}
const user_update = (data) => {

    return httpPut('/master/user', data)
}

const user_get = (id) => {
    return httpGet('/master/user?id=' + id)
}
const user_delete = (id) => {
    return httpDelete('/master/user?id=' + id)
}
const system_update = (data) => {
    return httpPost('/master/system', data)
}
const system_get = () => {
    return httpGet('/master/system')
}
const templates_get = () => {
    return httpGet('/master/system/templates')
}
const ota_check = () => {
    return httpGet('/master/ota/check')
}
const ota_update = () => {
    return httpGet('/master/ota/update')
}
const ota_update_database = () => {
    return httpGet('/master/ota/database')
}
const ota_update_script = () => {
    return httpGet('/master/ota/script')
}


const plugin_total_request_count = () => {

    return httpGet('/master/analysis/plugin_total_request_count')
}

const plugin_max_request_count = () => {

    return httpGet('/master/analysis/plugin_max_request_count')
}

const plugin_request_count = () => {

    return httpGet('/master/analysis/plugin_request_count')
}
const user_active_count = () => {

    return httpGet('/master/analysis/user_active_count')
}
const user_increase_count = () => {

    return httpGet('/master/analysis/user_increase_count')
}


const system_info_get = () => {

    return httpGet('/master/system/info')
}

