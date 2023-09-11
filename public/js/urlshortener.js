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
    var result = {
        success: false,
        data: {
            originalUrl: "",
            shortUrl: ""
        },
        errMsg: "",
        errId: 0
    };

    req.onreadystatechange = function () {
        if (parseInt(req.readyState, 10) === AJAX_COMPLETE) {
            if (req.responseText) {
                result = JSON.parse(req.responseText);
            } else {
                result.success = 0;
                result.errMsg = 'Internal service error';
                resultElem.innerHTML = '';
            }

            if (result.success) {
                resultElem.innerHTML = '<a href="' + result.data.originalUrl + '" target="_blank">' +
                    result.data.shortUrl + '</a>';
            } else {
                alert("Message: " + result.errMsg + " ErrId: " + result.errId);
                resultElem.innerHTML = "";
                return false;
            }
        }
    }

    var params = 'originalUrl=' + encodeURIComponent(document.getElementById('url').value);
    req.open('POST', 'api/urlshort', true);
    req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    req.send(params);

    resultElem.innerHTML = 'Waiting answer ...';
    return false;
}
