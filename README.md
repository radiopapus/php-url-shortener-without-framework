# Php url shortener without frameworks

## Requirements ##
1. docker (24.0.x or higher)
2. git (2.34.x or higher)
3. httpie (2.6.x recommended) or curl to test API.

## Getting started ##
After clone repository fill .env file (see .env.example for sure).
Run `docker compose up`

Visit [http://localhost:8000/index.html](http://localhost:8000/index.html) via browser.
or call ```http :8000/index.html``` in terminal.

Test short url creation```http --form :8000/api/urlshort originalUrl=https://yandex.ru```

```json
{
    "data": {
        "originalUrl": "https://yandex.ru",
        "shortUrl": "http://localhost:8000/6laZJ"
    },
    "errId": "",
    "errMsg": "",
    "success": 1
}
```

Test redirection from short url to original ```http :8000/api/urlshort/{short_url}```
```
HTTP/1.1 301 Moved Permanently
Connection: close
Content-type: text/html; charset=UTF-8
Date: Sun, 10 Sep 2023 16:21:20 GMT
Host: localhost:8000
Location: https://yandex.ru
X-Powered-By: PHP/8.2.10
```
