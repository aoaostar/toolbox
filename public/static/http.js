const instance = axios.create({
    baseURL: '/',
    // timeout: 1000,
    // headers: {'X-Custom-Header': 'foobar'}
});

const httpGet = (url, params = {}) => {
    return instance.get(url, {
        params
    }).then(res => {
        return res.data
    }).then(res => {
        if (res.status !== "ok") {
            $message.error(
                res.message
            );
        }
        return res
    })
}
const httpPost = (url, params) => {
    return instance.post(url, params)
        .then(res => {
            return res.data
        }).then(res => {
            if (res.status !== "ok") {
                $message.error(
                    res.message
                );
            }
            return res
        })
}
const httpPut = (url, params) => {
    return instance.put(url, params)
        .then(res => {
            return res.data
        }).then(res => {
            if (res.status !== "ok") {
                $message.error(
                    res.message
                );
            }
            return res
        })
}
const httpDelete = (url, params) => {
    return instance.delete(url, {
        params
    })
        .then(res => {
            return res.data
        }).then(res => {
            if (res.status !== "ok") {
                $message.error(
                    res.message
                );
            }
            return res
        })
}

const request = (opt)=>{
    return axios.request(opt)
}