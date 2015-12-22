AJAX_COMPLETE = 4;
STATUS_CODE_OK = 200;

function getXmlHttp() {
    var xmlhttp;
    try {
        xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (E) {
            xmlhttp = false;
        }
    }

    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
        xmlhttp = new XMLHttpRequest();
    }

    return xmlhttp;
}

function doShort() {
    var req = getXmlHttp();
    var resultElem = document.getElementById('result');
    var result = {};

    req.onreadystatechange = function () {
        if (parseInt(req.readyState, 10) === AJAX_COMPLETE) {
            if (req.status !== STATUS_CODE_OK) {
                alert('Connection Error!');
                return false;
            }

            if (req.responseText) {
                result = eval('(' + req.responseText + ')');
            } else {
                result.success = 0;
                result.errMsg = 'Internal service error';
                resultElem.innerHTML = '';
            }

            if (result.success) {
                resultElem.innerHTML = '<a href="' + result.shortUrl + '" target="_blank">' +
                    result.shortUrl + '</a>';
            } else {
                alert(result.errMsg);
                return false;
            }
        }
    }

    var params = 'url=' + document.getElementById('url').value;
    req.open('POST', 'urlshortener.php', true);
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    req.send(params);

    resultElem.innerHTML = 'Waiting answer ...';
    return false;
}
