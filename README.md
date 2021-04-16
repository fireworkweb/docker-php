### fireworkweb/docker-php

# ATTENTION - THIS REPO MOVED TO [kool-dev/docker-php](https://github.com/kool-dev/docker-php)

> `fwd` is dead, long live [`kool` (check it out now!)](https://github.com/kool-dev/kool)

This repository is deprecated and archived in favour of Kool maintened PHP images: https://github.com/kool-dev/docker-php

Below you will find original `fireworkweb/docker-php` README for the sake of historic value.

---

![TravisCI](https://travis-ci.com/fireworkweb/docker-php.svg?branch=master)

Minimal PHP Docker image focused on Laravel applications. Its use is intended for [fwd](https://github.com/fireworkweb/fwd), but can fit in any other PHP use-case.

## Usage

With `docker run`:

```sh
docker run -it --rm fireworkweb/app:7.3-alpine php -v
```

You can also enable Xdebug:

```sh
docker run -it --rm -e ENABLE_XDEBUG=true fireworkweb/app:7.3-alpine php -v
```

With `docker-compose.yml`:

```yaml
app:
  build:
    context: ./docker/app
    dockerfile: Dockerfile
  ports:
    - "9773:9773"
  volumes:
    - ".:/app:cached"
    - "$HOME/.ssh/id_rsa:/home/developer/.ssh/id_rsa:cached"
  environment:
    ASUSER: "${$UID}"
```

### Available Tags

- 7.1-alpine
- 7.1-alpine-wkhtmltopdf
- 7.2-alpine
- 7.2-alpine-wkhtmltopdf
- 7.3-alpine
- 7.3-alpine-wkhtmltopdf

### Variables

**ASUSER**: Changes the user id that executes the commands
**ENABLE_XDEBUG**: Enables the Xdebug extension

### Contributing

You should only change `fwd-template.json` and `template` folder, after you do your changes run `fwd template` compile the template and generate all version folder / files.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
