# php-url-shortener-without-framework
php-url-shortener-without-framework

## Requirements ##
1. docker (24.0.x)
2. git
3. httpie (recommended) or curl

## Getting started ##
After clone repository fill .env file (see .env.example for sure).
Run `docker compose up`

Visit [http://localhost:8000/index.html](http://localhost:8000/index.html) via browser.

```http :8000/index.html```

Test short url

http --form :8000/api/urlshort originalUrl=https://yandex.ru
```json
{
    "data": {
        "originalUrl": "https://yandex.ru",
        "shortUrl": "http://localhost:8000/6laZJs"
    },
    "errId": "",
    "errMsg": "",
    "success": 1
}
```

Test redirect from short url to original

http :8000/api/urlshort/{short_url}
```
HTTP/1.1 301 Moved Permanently
Connection: close
Content-type: text/html; charset=UTF-8
Date: Sun, 10 Sep 2023 16:21:20 GMT
Host: localhost:8000
Location: https://yandex.ru
X-Powered-By: PHP/8.2.10
```
