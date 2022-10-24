FROM amazonlinux:latest

RUN yum clean all
RUN sed -i 's/^\(timeout\s*=\s*\).*$/\1300/' /etc/yum.conf
RUN echo minrate=28 >> /etc/yum.conf
RUN yum --disableplugin=fastestmirror -y update && yum --disableplugin=fastestmirror -y install \
    amazon-linux-extras \
    deltarpm \
    gcc \
    gcc-c++ \
    autoconf \
    automake

RUN amazon-linux-extras enable php7.4
RUN yum clean metadata
RUN yum --disableplugin=fastestmirror -y update && yum --disableplugin=fastestmirror -y install \
    nginx \
    php \
    php-{intl,fpm,common,gd,pear,curl,mbstring,mysqlnd,pdo_mysql,xml,iconv,posix,devel}

RUN amazon-linux-extras install nginx1.12
RUN yum --disableplugin=fastestmirror -y update && yum --disableplugin=fastestmirror -y install \
    ImageMagick \
    ImageMagick-devel \
    ImageMagick-perl

RUN pecl install imagick

ADD config/memory_limit.ini /etc/php.d/
ADD config/ext-imagick.ini /etc/php.d/

ADD config/nginx.conf /etc/nginx/nginx.conf
ADD config/php-fpm.conf /etc/php-fpm.d/www.conf

ADD entrypoint.sh /tmp/
RUN chmod +x /tmp/entrypoint.sh

WORKDIR /app
EXPOSE 80 443

ENTRYPOINT ["/tmp/entrypoint.sh"]
