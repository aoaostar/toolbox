const logout = () => {
    window.location.href = "/auth/logout"
}

const download = (user_id, filepath) => {
    window.open(`/download/${user_id}/${filepath}?t=${new Date().getTime()}`)
}
const open = (url) => {
    window.open(url)
}
const redirect = (url) => {
    window.location.href = url
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
    if (dom) {
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

const $message = {
    success: (message) => {
        Swal.fire({
            icon: 'success',
            title: message,
        })
    },
    error: (message) => {
        Swal.fire({
            icon: 'error',
            title: message,
        })
    },
}