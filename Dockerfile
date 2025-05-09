FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive

# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    net-tools \
    iproute2 \
    sudo \
    x11-apps \
    rsync

COPY sample_data/ /tmp/sampleData
COPY aqua2_cloud_website /tmp/aqua2_cloud_website
COPY aqua2_cloud_logic /tmp/aqua2_cloud_logic
COPY matlab_installer /tmp/matlab_installer
COPY containerSetupSettings.txt /containerSetupSettings.txt
COPY aqua2_cloud_entrypoint.sh /aqua2_cloud_entrypoint.sh
RUN chmod +x /aqua2_cloud_entrypoint.sh

ENTRYPOINT ["/aqua2_cloud_entrypoint.sh"]