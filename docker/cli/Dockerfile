FROM amazonlinux:2018.03

RUN yum clean all
RUN sed -i 's/^\(timeout\s*=\s*\).*$/\1300/' /etc/yum.conf
RUN echo minrate=28 >> /etc/yum.conf
RUN yum --disableplugin=fastestmirror -y update && yum --disableplugin=fastestmirror -y install \
    deltarpm \
    gcc \
    gcc-c++ \
    curl \
    php73 \
    php73-curl \
    php73-xml \
    php73-pdo \
    php73-mysqlnd \
    php73-pdo_mysql \
    php73-phar \
    php73-json \
    php73-mbstring \
    php73-yaml \
    php73-pear \
    git \
    unzip \
    openssh

RUN yum -y install \
    php73-soap \
    php73-gd \
    php73-mcrypt \
    php73-tokenizer \
    php73-fileinfo \
    php73-openssl

RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/bin/

ADD memory_limit.ini /etc/php-7.3.d/

ARG USERNAME_LOCAL
ARG UID_LOCAL
ARG GID_LOCAL

RUN groupadd -g $GID_LOCAL $USERNAME_LOCAL || echo "This group already exists!"
RUN useradd -u $UID_LOCAL -g $GID_LOCAL $USERNAME_LOCAL

WORKDIR /app
