FROM phpdockerio/php72-fpm:latest
#@todo change php image in order to user rabbit mq

WORKDIR "/application"

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install php7.2-mysql php-xdebug php7.2-intl \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git flow
RUN apt-get update \
    && apt-get install git-flow


# @todo "Setup the Messenger Component" enable the AMQP PHP extension in the PHP Docker image to be able to dispatch messages.
# (for now I will use the normal EventDispatcher as with ubuntu and this image, it's difficult to setup..)

# attempt......
#RUN apt-get update && apt-get install -y --no-install-recommends \
 #...
 #        librabbitmq-dev \
 #...
 #    && pecl install amqp \
 #    && docker-php-ext-enable amqp

# attempt......

#FROM php:7.2-fpm-alpine
 #WORKDIR "/application"
 #
 #RUN apk add --no-cache wget
 #
 #RUN apk update \
 #    && apk add ca-certificates wget \
 #    && update-ca-certificates
 #
 ## Install selected extensions and other stuff
 #RUN apk update \
 #    && apk -y --no-install-recommends add php7.2-mysql php-xdebug php7.2-intl \
 #    && apk clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
 #
 ## Install git
 #RUN apk update \
 #    && apk -y add git \
 #    && apk clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*
 #
 ## Install git flow
 #RUN apk update \
 #    && apk add git-flow
 #
 #RUN apk update && apk add php-pear -y \
 #    && apk add -y php7.2-dev \
 #    && apk add -y --no-install-recommends \
 #    librabbitmq-dev \
 #    && pecl install amqp \
 #    && docker-php-ext-enable amqp