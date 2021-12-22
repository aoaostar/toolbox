const aoaostar_table = {
    parseData: function (res) { //res 即为原始返回的数据
        return {
            "status": res.status, //解析接口状态
            "message": res.message, //解析提示文本
            "total": res.data.total, //解析数据长度
            "data": res.data.items //解析数据列表
        }
    },
    response: {
        statusName: 'status' //规定数据状态的字段名称，默认：code
        , statusCode: 'ok' //规定成功的状态码，默认：0
        , msgName: 'message' //规定状态信息的字段名称，默认：msg
        , countName: 'total' //规定数据总数的字段名称，默认：count
        , dataName: 'data' //规定数据列表的字段名称，默认：data
    },
}

const imagePreview = (url) => {
    layer.open({
        type: 1,
        title: false,
        closeBtn: 0,
        shadeClose: true,
        skin: 'layui-layer-molv',
        content: `<img width="100%" height="100%" src="${url}" alt="">`
    });
}
const getQueryString = (name) => {
    var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(r[2]);
    }
    return null;
}

const $message = {
    success: (message = 'success', callback = () => {
    }) => {
        layer.msg(message, {
            icon: 1,
        }, callback)
    },
    error: (message = 'error', callback = () => {
    }) => {
        layer.msg(message, {icon: 2}, callback);
    },

}

const dateFormat = (date, format = 'YYYY-MM-DD HH:mm:ss') => {
    const config = {
        YYYY: date.getFullYear(),
        MM: date.getMonth() + 1,//getMonth() 方法根据本地时间返回指定日期的月份（从 0 到 11）
        DD: date.getDate(),
        HH: date.getHours(),
        mm: date.getMinutes(),
        ss: date.getSeconds(),
    }
    for (const key in config) {
        format = format.replace(key, config[key])
    }
    return format
}


window.onscroll = function () {
    var scrollTop = document.documentElement.scrollTop ?
        document.documentElement.scrollTop :
        document.body.scrollTop;
    let dom = document.getElementById('back-to-top')
    if (dom){
        if (scrollTop > 200) {
            dom.style.visibility = "visible"
        } else {
            dom.style.visibility = "hidden"
        }
    }
}
const scrollTopSmooth = () => {
    const easeout = (position, destination, rate, callback) => {
        if (position === destination || typeof destination !== 'number') {
            return false;
        }
        destination = destination || 0;
        rate = rate || 2;

        // 不存在原生`requestAnimationFrame`，用`setTimeout`模拟替代
        if (!window.requestAnimationFrame) {
            window.requestAnimationFrame = function (fn) {
                return setTimeout(fn, 17);
            }
        }
        const step = function () {
            position = position + (destination - position) / rate;
            if (position < 1) {
                callback(destination, true);
                return;
            }
            callback(position, false);
            requestAnimationFrame(step);
        };
        step();
    }
    // 当前滚动高度
    const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    easeout(scrollTop, 0, 5, function (val) {
        window.scrollTo(0, val);
    });
}