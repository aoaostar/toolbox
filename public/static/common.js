const logout = () => {
    localStorage.clear()
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

const secToTime = s => {
    let t = '';
    if (s > -1) {
        let hour = Math.floor(s / 3600)
        let min = Math.floor(s / 60) % 60
        let sec = s % 60
        if (hour > 0) {
            if (hour < 10) {
                t += '0'
            }
            t = hour + "h"
        }
        if (hour > 0 || min > 0) {
            if (min < 10) {
                t += '0'
            }
            t += min + "m"
        }
        if (sec < 10) {
            t += '0'
        }
        t += sec + 's'
    }
    return t
}
// 文件大小格式转换
const changeFilesize = (filesize) => {
    filesize = parseInt(filesize);
    let size = "";
    if (filesize === 0) {
        size = "0.00 B"
    } else if (filesize < 1024) { //小于1KB，则转化成B
        size = filesize.toFixed(2) + " B"
    } else if (filesize < 1024 * 1024) { //小于1MB，则转化成KB
        size = (filesize / 1024).toFixed(2) + " KB"
    } else if (filesize < 1024 * 1024 * 1024) { //小于1GB，则转化成MB
        size = (filesize / (1024 * 1024)).toFixed(2) + " MB"
    } else { //其他转化成GB
        size = (filesize / (1024 * 1024 * 1024)).toFixed(2) + " GB"
    }
    return size;
}
// 下载速度格式转换
const changeDownloadSpeed = (filesize) => {
    filesize = changeFilesize(filesize);
    return filesize.replace(/\s([K|M|G|B]*)B{0,1}/, '$1/s')
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

const copy = (text) => {
    let oInput = document.createElement('textarea');
    oInput.value = text;
    document.body.appendChild(oInput);
    oInput.select(); // 选择对象
    document.execCommand("Copy"); // 执行浏览器复制命令
    oInput.className = 'oInput';
    oInput.style.display = 'none';
    oInput.remove();
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
    console.log("%c 傲星工具箱 %c https://www.aoaostar.com ", "color: #fff; margin: 1em 0; padding: 5px 0; background: #28b9be;", "margin: 1em 0; padding: 5px 0; background: #efefef;");
    console.log("%c 作者：Pluto %c i@aoaostar.com ", "color: #fff; margin: 1em 0; padding: 5px 0; background: #ffa0a0;", "margin: 1em 0; padding: 5px 0; background: #efefef;");
    console.log("%c Github： %c https://github.com/aoaostar ", "color: #fff; margin: 1em 0; padding: 5px 0; background: #535f6a;", "margin: 1em 0; padding: 5px 0; background: #efefef;");
    console.log("%c Telegram： %c https://t.me/aoaostar ", "color: #fff; margin: 1em 0; padding: 5px 0; background: #6190e8;", "margin: 1em 0; padding: 5px 0; background: #efefef;");
}