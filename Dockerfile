FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive

# Install stable runtime dependencies at build time. Runtime setup is limited
# to configuring services and initializing the mounted application volume.
RUN apt-get update && apt-get install -y --no-install-recommends \
    apache2 \
    build-essential \
    ca-certificates \
    cmake \
    cron \
    expect \
    fluxbox \
    fonts-dejavu-core \
    fonts-liberation \
    gcc-10 \
    g++-10 \
    iproute2 \
    libapache2-mod-php \
    libasound2t64 \
    libatk1.0-0t64 \
    libegl1 \
    libgl1 \
    libgles2 \
    libglib2.0-dev \
    libglu1-mesa \
    libgtk-3-0t64 \
    libgtk-3-dev \
    libgtk2.0-0 \
    libmysqlclient-dev \
    libnss3 \
    libpam-mysql \
    libx11-dev \
    libxcomposite1 \
    libxcursor1 \
    libxdamage1 \
    libxi6 \
    libxtst-dev \
    libxtst6 \
    mysql-client \
    mysql-server \
    nano \
    net-tools \
    openssl \
    php-mbstring \
    php-mysqli \
    psmisc \
    rsync \
    sudo \
    unzip \
    vsftpd \
    x11-apps \
    x11vnc \
    xvfb \
    && update-alternatives --install /usr/bin/gcc gcc /usr/bin/gcc-10 100 \
        --slave /usr/bin/g++ g++ /usr/bin/g++-10 \
    && rm -rf /var/lib/apt/lists/*

COPY sample_data/ /tmp/sampleData
COPY aqua2_cloud_website /tmp/aqua2_cloud_website
COPY aqua2_cloud_logic /tmp/aqua2_cloud_logic
COPY matlab_installer /tmp/matlab_installer
COPY containerSetupSettings.txt /containerSetupSettings.txt
COPY aqua2_cloud_entrypoint.sh /aqua2_cloud_entrypoint.sh
RUN sed -i 's/\r$//' /aqua2_cloud_entrypoint.sh
RUN chmod +x /aqua2_cloud_entrypoint.sh

ENTRYPOINT ["/aqua2_cloud_entrypoint.sh"]