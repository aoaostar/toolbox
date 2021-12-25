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
        MM: date.getMonth() < 9 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1,
        DD: date.getDate() < 10 ? '0' + date.getDate() : date.getDate(),
        HH: date.getHours() < 10 ? '0' + date.getHours() : date.getHours(),
        mm: date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes(),
        ss: date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds(),
    }
    for (const key in config) {
        format = format.replace(key, config[key])
    }
    return format
}

const randomString = (len = 32) => {
    let $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
    /****默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1****/
    let maxPos = $chars.length;
    let pwd = '';
    for (let i = 0; i < len; i++) {
        pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return pwd;
}
// 改变blob对象中的name为随机文件名
const changeBlobFileName = (blob) => {
    let fileType = blob.type || "";
    let filename =
        randomString(5) + "." +
        fileType.slice(fileType.lastIndexOf("/") + 1);

    return new File([blob], filename, {
        type: fileType,
    });
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
        return Swal.fire({
            icon: 'success',
            title: message,
        })
    },
    error: (message) => {
        return Swal.fire({
            icon: 'error',
            title: message,
        })
    },
    loading: (message) => {
        let index = Swal.fire({
            title: message,
            allowOutsideClick: false,
        })
        Swal.showLoading()
        return index
    },
}

//好兄弟，不要太过分了
const title = `
   _____   ________      _____   ________     ____________________   _____   __________ 
  /  _  \\  \\_____  \\    /  _  \\  \\_____  \\   /   _____/\\__    ___/  /  _  \\  \\______   \\
 /  /_\\  \\  /   |   \\  /  /_\\  \\  /   |   \\  \\_____  \\   |    |    /  /_\\  \\  |       _/
/    |    \\/    |    \\/    |    \\/    |    \\ /        \\  |    |   /    |    \\ |    |   \\
\\____|__  /\\_______  /\\____|__  /\\_______  //_______  /  |____|   \\____|__  / |____|_  /
        \\/         \\/         \\/         \\/         \\/                    \\/         \\/ 
`;
if (window.console && window.console.log) {
    console.log(title)
    console.log("%c 傲星工具箱 %c https://www.aoaostar.com ","color: #fff; margin: 1em 0; padding: 5px 0; background: #28b9be;","margin: 1em 0; padding: 5px 0; background: #efefef;");
    console.log("%c 作者：Pluto %c i@aoaostar.com ","color: #fff; margin: 1em 0; padding: 5px 0; background: #ffa0a0;","margin: 1em 0; padding: 5px 0; background: #efefef;");
    console.log("%c Github： %c https://github.com/aoaostar ","color: #fff; margin: 1em 0; padding: 5px 0; background: #535f6a;","margin: 1em 0; padding: 5px 0; background: #efefef;");
    console.log("%c Telegram： %c https://t.me/aoaostar ","color: #fff; margin: 1em 0; padding: 5px 0; background: #6190e8;","margin: 1em 0; padding: 5px 0; background: #efefef;");
}