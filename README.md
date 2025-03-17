# HTTP error pages

This was quickly hacked together so I could generate custom error pages for 4xx &amp; 5xx errors on NGINX web server (and others) and store them in a centralized location.

## How to use

To use the static files as-is, follow the steps below.

### Clone repository

Clone this repository into `/usr/local/share` and use the files in `web` for your custom error pages.

```sh
cd /usr/local/share
git clone https://github.com/curtistinkers/http-error-pages.git
```

### Modify web server

#### NGINX

Link the config snippet and use it in your configs.

```sh
ln -s /usr/local/share/http-error-pages/nginx-error-pages.conf /etc/nginx/snippets/custom-error-pages.conf
```

Add `include snippets/custom-error-pages.conf;` to your `server` block as follows:

```nginx
server {
    server_name example.com
    …
    ## Include custom error pages
    include snippets/custom-error-pages.conf;
    …
}
```

#### Others

If you'd like to add other web servers, submit a pull request.

## Development

This should probably be made into a Symfony (or similar) console app, but for now this serves its purpose.
