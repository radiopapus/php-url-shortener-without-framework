################################################################################
# BASE IMAGE
################################################################################

FROM php:8.2-fpm as base

LABEL maintainer="Viktor Zharina"

RUN apt-get update && apt-get install -y tini git

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_mysql bcmath gmp

# ------------------------------------------------------------------------------
# Setup environment and workdir
# ------------------------------------------------------------------------------

ENV APP_HOME=/app

WORKDIR ${APP_HOME}

# ------------------------------------------------------------------------------
# Supply php with custom configs from the repo
# ------------------------------------------------------------------------------

COPY docker/config/php/php.ini /usr/local/etc/php/
COPY docker/config/php/php8.ini /usr/local/etc/php/conf.d/

# ------------------------------------------------------------------------------
# Copy source code to the workdir and setup required folders
# ------------------------------------------------------------------------------

COPY ./ ./

# ------------------------------------------------------------------------------
# Expose HTTP port
# ------------------------------------------------------------------------------

EXPOSE 8080

# ------------------------------------------------------------------------------
# Setup entrypoint
# ------------------------------------------------------------------------------
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
RUN ln -s usr/local/bin/docker-entrypoint.sh / # backwards compatibility
ENTRYPOINT ["/usr/bin/tini", "--", "docker-entrypoint.sh"]
