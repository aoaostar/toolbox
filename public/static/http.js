const instance = axios.create({
    baseURL: '/',
    // timeout: 1000,
    headers: {'X-Requested-With': 'XMLHttpRequest'}
});
instance.interceptors.response.use(function (response) {
    // 2xx 范围内的状态码都会触发该函数。
    // 对响应数据做点什么
    if (response.data.status !== "ok") {
        $message.error(
            response.data.message
        );
        return Promise.reject(response.data.message)
    }
    return response.data
}, function (error) {
    // 超出 2xx 范围的状态码都会触发该函数。
    // 对响应错误做点什么
    if (error && error.response && error.response.status) {
        if (error.response.status === 401) {
            if (error.response.data.message) {
                $message.error(error.response.data.message);
            } else {
                $message.error('请登录');
            }
        }
    }
    return Promise.reject(error);
});
const httpGet = (url, params = {}) => {
    return instance.get(url, {
        params
    })
}
const httpPost = (url, params) => {
    return instance.post(url, params)
}
const httpPut = (url, params) => {
    return instance.put(url, params)
}
const httpDelete = (url, params) => {
    return instance.delete(url, {
        params
    })
}

const request = (opt) => {
    return axios.request(opt)
}