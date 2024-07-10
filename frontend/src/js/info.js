function getScreenResolution() {
    return `${window.screen.width} x ${window.screen.height}`;
}

function getOS() {
    let userAgent = window.navigator.userAgent,
        platform = window.navigator.platform,
        macosPlatforms = ['Macintosh', 'MacIntel', 'MacPPC', 'Mac68K'],
        windowsPlatforms = ['Win32', 'Win64', 'Windows', 'WinCE'],
        iosPlatforms = ['iPhone', 'iPad', 'iPod'],
        os = null;

    if (macosPlatforms.indexOf(platform) !== -1) {
        os = 'Mac OS';
    } else if (iosPlatforms.indexOf(platform) !== -1) {
        os = 'iOS';
    } else if (windowsPlatforms.indexOf(platform) !== -1) {
        os = 'Windows';
    } else if (/Android/.test(userAgent)) {
        os = 'Android';
    } else if (!os && /Linux/.test(platform)) {
        os = 'Linux';
    }

    return os;
}

document.getElementById('info-button').onclick = function (event) {
    let user = getUser()
    event.preventDefault();
    // Get screen resolution
    document.getElementById('screenResolution').textContent = 'Screen Resolution: ' + getScreenResolution();

    // Get OS information
    document.getElementById('osInfo').textContent = 'Operating System: ' + getOS();


    document.getElementById('loggedInUsers').textContent = 'Logged-In Users: ' + user.name;

    // Open the modal
    document.getElementById('infoModal').style.display = 'block';
}

// Close the modal
document.querySelector('.close').onclick = function () {
    document.getElementById('infoModal').style.display = 'none';
}

// Close the modal when clicking outside of it
window.onclick = function (event) {
    if (event.target == document.getElementById('infoModal')) {
        document.getElementById('infoModal').style.display = 'none';
    }
}