#!/bin/bash

# Extract Host ID (MAC) and login name
HOSTID=$(ip link show | awk '/ether/ {print $2; exit}')
USERNAME=$(whoami)

# Check if volume was mounted
if [ ! -d /opt/a2ud ]; then
    echo "ERROR: /opt/a2ud does not exist."
    echo "The Docker volume 'aqua2-cloud-data' was not mounted correctly."
    exit 1
fi

# Load container settings
if [ -f /containerSetupSettings.txt ]; then
    echo "Loading container setup settings from /containerSetupSettings.txt. Using automatic mode."
    source /containerSetupSettings.txt
    chmod 700 /containerSetupSettings.txt
else
    echo "Settings file /containerSetupSettings.txt not found. Proceeding with manual mode and hardcoded defaults."
    AUTOMATIC_SETUP=false
fi

MATLAB_FILES_SKIP=$(echo "$MATLAB_FILES_SKIP" | tr -d '\r')
if [ "$MATLAB_FILES_SKIP" = false ]; then
    # Check for MATLAB installer zip
    INSTALLER_DIR="/tmp/matlab_installer"

    ZIP_FILES=($(find "$INSTALLER_DIR" -maxdepth 1 -type f -iname "*.zip"))

    if [ ${#ZIP_FILES[@]} -eq 0 ]; then
        echo "========================================"
        echo "MATLAB Installer Zip Setup for AQuA2-Cloud"
        echo "========================================"
        echo "No installer zip file found in $INSTALLER_DIR"
        echo "========================================"
        echo "1). Go to: https://www.mathworks.com/download"
        echo "2). Login if required. Download latest release for Linux. (Example: matlab_R2024b_Linux.zip)"
        echo "3). Place the installer zip file into the matlab_installer folder in the aqua2-cloud project folder you downloaded."
        echo "4). Rebuild and re-run the container."
        echo "========================================"
        echo "You will need connect to the container via VNC later and follow the MATLAB GUI installer."
        echo "========================================"
        echo "The container will now stop. Please place the installer .zip as described and re-run the container."
        exit 0
    fi

    if [ ${#ZIP_FILES[@]} -gt 1 ]; then
        echo "Multiple zip files found in $INSTALLER_DIR. Please only place one installer zip file."
        echo "Found files:"
        printf ' - %s\n' "${ZIP_FILES[@]}"
        echo "The container will now stop. Remove extra zip files, rebuild, and re-run the container."
        exit 0
    fi

    ZIP_FILE="${ZIP_FILES[0]}"

    if [[ ! "$(basename "$ZIP_FILE")" =~ [Mm][Aa][Tt][Ll][Aa][Bb] ]]; then
        echo "Installer zip file must contain the word 'matlab' (case insensitive)."
        echo "Found file: $(basename "$ZIP_FILE")"
        echo "The container will now stop. Please rename the file appropriately or get the correct installer. Rebuild and re-run the container."
        exit 0
    fi

    echo "Found valid MATLAB installer zip: $(basename "$ZIP_FILE")"
else
    echo "installer .zip check skipped."
fi

STEP1_SETUP_FLAG="/var/.step1"
if [ ! -f "$STEP1_SETUP_FLAG" ]; then
    groupadd a2cloud

    mkdir -p /opt/a2ud/aqua2_cloud_website
    rm -rf /opt/a2ud/aqua2_cloud_website/* \
            /opt/a2ud/aqua2_cloud_website/.[!.]* \
            /opt/a2ud/aqua2_cloud_website/..?*
    cp -a /tmp/aqua2_cloud_website/. /opt/a2ud/aqua2_cloud_website/

    mkdir -p /opt/a2ud/aqua2_cloud_website_shellScripts
    rm -rf /opt/a2ud/aqua2_cloud_website_shellScripts/* \
            /opt/a2ud/aqua2_cloud_website_shellScripts/.[!.]* \
            /opt/a2ud/aqua2_cloud_website_shellScripts/..?*
    echo "Step 1 complete."
    touch "$STEP1_SETUP_FLAG"
else
    echo "Step 1 already completed. Skipping."
fi

chown root:a2cloud /opt
chown root:a2cloud /opt/a2ud
chmod 710 /opt
chmod 710 /opt/a2ud

mkdir -p /opt/a2ud/aqua2_cloud_ssl_certificate
chown -R root:a2cloud /opt/a2ud/aqua2_cloud_ssl_certificate
chmod -R 750 /opt/a2ud/aqua2_cloud_ssl_certificate
chmod g+s /opt/a2ud/aqua2_cloud_ssl_certificate

DEV_MODE=$(echo "$DEV_MODE" | tr -d '\r')

if [ "$DEV_MODE" = true ]; then
    apt-get install -y nano
fi

AUTOMATIC_SETUP=$(echo "$AUTOMATIC_SETUP" | tr -d '\r')
echo ""
echo "========================"
echo ""
echo "Root user password"
echo "========================"
ROOT_HASH_FILE="/var/.container_root_hash"
if [ "$AUTOMATIC_SETUP" = false ]; then
    echo "Manual mode: Setup root user password..."

    read -s -p "Enter new root password: " ROOT_PASS
    echo
    read -s -p "Confirm root password: " ROOT_PASS_CONFIRM
    echo

    if [ "$ROOT_PASS" != "$ROOT_PASS_CONFIRM" ]; then
        echo "Passwords do not match. Exiting."
        exit 1
    fi

    echo "Root password has been set manually."
    echo "root:$ROOT_PASS" | chpasswd

    # Store a hashed version for future checks (in-memory container file)
    echo -n "$ROOT_PASS" | sha256sum | awk '{print $1}' > "$ROOT_HASH_FILE"
else
    echo "Automatic setup mode: Set root password from configuration..."
    # Set root password directly
    ROOT_PASS=$(echo "$ROOT_PASS" | tr -d '\r')

    if [ -z "$ROOT_PASS" ]; then
        echo "ROOT_PASS is empty. Cannot proceed with automatic root password setup."
        exit 1
    fi

    echo "root:$ROOT_PASS" | chpasswd

    # Store hashed version for future checks
    echo -n "$ROOT_PASS" | sha256sum | awk '{print $1}' > "$ROOT_HASH_FILE"

    echo "Root password has been set automatically."
fi

chmod 600 "$ROOT_HASH_FILE"

echo ""
echo "========================"
echo ""
echo "Container SSH Server"
echo "========================"

# Create a flag file to indicate SSH has been set up before
# Store it in a container-local path, not in the mounted volume
SSH_SETUP_FLAG="/var/.ssh_configured"

# Better check for SSH installation and service status
# Only proceed with setup if SSH is not properly configured
if [ ! -f "$SSH_SETUP_FLAG" ] && (! dpkg -l | grep -q openssh-server || ! [ -f "/etc/ssh/sshd_config" ]); then
    echo "Setting up SSH server..."
    
    if [ "$AUTOMATIC_SETUP" = false ]; then
        # Prompt for SSH port
        read -p "Enter SSH port to use [Leave blank to use default of 32]: " SSH_PORT
        SSH_PORT=${SSH_PORT:-32}  # Default to 32 if empty
    else
        # Use value from settings file or fallback to default
        SSH_PORT=${OPENSSH_SERVER_PORT:-32}
        echo "Using SSH port from settings: $SSH_PORT"
    fi

    echo "Installing OpenSSH server..."
    apt-get update && apt-get install -y openssh-server


    # Ensure SSH runtime directory exists
    mkdir -p /var/run/sshd

    echo "Configuring SSH daemon..."
    # Configure SSH to listen on container's internal interface
    sed -i "s/^#Port .*/Port $SSH_PORT/" /etc/ssh/sshd_config
    sed -i "s/^Port .*/Port $SSH_PORT/" /etc/ssh/sshd_config
    
    # Remove any existing ListenAddress lines
    sed -i "/^ListenAddress/d" /etc/ssh/sshd_config
    
    # Allow root login
    sed -i "s/^#PermitRootLogin.*/PermitRootLogin yes/" /etc/ssh/sshd_config
    sed -i "s/^PermitRootLogin.*/PermitRootLogin yes/" /etc/ssh/sshd_config
    if ! grep -q "^PermitRootLogin" /etc/ssh/sshd_config; then
        echo "PermitRootLogin yes" >> /etc/ssh/sshd_config
    fi

    # Configure password authentication
    sed -i "s/^#PasswordAuthentication.*/PasswordAuthentication yes/" /etc/ssh/sshd_config
    sed -i "s/^PasswordAuthentication.*/PasswordAuthentication yes/" /etc/ssh/sshd_config
    
    echo "SSH configured to use port $SSH_PORT and accept connections via Docker port mapping."
    touch "$SSH_SETUP_FLAG"
else
    echo "SSH server is already configured."
fi

# Always ensure SSH is running (whether we just set it up or it was already configured)
if ! service ssh status &> /dev/null; then
    echo "Starting SSH server..."
    service ssh start
else
    echo "SSH server is already running."
    service ssh restart  # Restart to ensure it picked up any config changes
fi

echo ""
echo "SSH Connection Information"
echo "========================="
SSH_PORT=$(grep "^Port" /etc/ssh/sshd_config | awk '{print $2}')
CONTAINER_IP=$(hostname -I | awk '{print $1}')
echo "• Container SSH service is running on port: $SSH_PORT"
echo "• Container internal IP: $CONTAINER_IP"
echo ""
echo "IMPORTANT: To connect from the host running docker engine:"
echo "   1. Make sure you started Docker with port mapping: -p $SSH_PORT:$SSH_PORT"
echo "   2. In a local SSH client, connect to localhost:$SSH_PORT or 127.0.0.1:$SSH_PORT (not $CONTAINER_IP)"
echo "   3. If that doesn't work, try connecting to host.docker.internal:$SSH_PORT"
echo ""
echo "SSH Server status:"
service ssh status
echo ""
echo "SSH Config:"
grep -v "^#" /etc/ssh/sshd_config | grep -v "^$"
echo ""
echo "Listening ports:"
netstat -tulpn | grep LISTEN
echo "========================"

echo "========================="
echo ""
echo "MySQL Database Setup"
echo "========================="

# Create a flag file to indicate MySQL has been set up before
MYSQL_SETUP_FLAG="/var/.mysql_configured"
MYSQL_DATABASE_DIRECTORY=$(echo "$MYSQL_DATABASE_DIRECTORY" | tr -d '\r')
HOST_MYSQL_DATABASE_PATH="/opt/a2ud$MYSQL_DATABASE_DIRECTORY"
MYSQL_VSFTPD_CREDS_FILE="$HOST_MYSQL_DATABASE_PATH/vsftpd_credentials"

if [ ! -f "$MYSQL_SETUP_FLAG" ]; then
    echo "Setting up MySQL server..."
    Prexisting_DB_Data=false
    # Install MySQL server and client
    echo "Installing MySQL server and client..."
    DEBIAN_FRONTEND=noninteractive apt-get -y install mysql-server mysql-client expect

    mkdir -p /home/mysql
    chown mysql:mysql /home/mysql
    usermod -d /home/mysql mysql

    usermod -g a2cloud mysql #Set mysql user group to a2cloud group
    usermod -G a2cloud mysql #Set mysql user group to a2cloud group

    # Ensure MySQL data directory path exists
    mkdir -p "$HOST_MYSQL_DATABASE_PATH"
    chmod 770 "$HOST_MYSQL_DATABASE_PATH"
    chown -R mysql:mysql "$HOST_MYSQL_DATABASE_PATH"
    chmod g+s "$HOST_MYSQL_DATABASE_PATH"

    # Ensure MySQL socket directory exists
    mkdir -p /var/run/mysqld
    chown -R mysql:mysql /var/run/mysqld
    chmod -R 770 /var/run/mysqld
    chmod g+s /var/run/mysqld

    echo "Configuring mysqld.cnf for custom paths..."
    sed -i 's|^# pid-file.*|pid-file = /var/run/mysqld/mysqld.pid|' /etc/mysql/mysql.conf.d/mysqld.cnf
    sed -i 's|^# socket.*|socket = /var/run/mysqld/mysqld.sock|' /etc/mysql/mysql.conf.d/mysqld.cnf
    sed -i 's|^# datadir.*|datadir = /opt/a2ud/mysql|' /etc/mysql/mysql.conf.d/mysqld.cnf

    rm -rf /var/lib/mysql/*

    if [ -z "$(ls -A $HOST_MYSQL_DATABASE_PATH)" ]; then
        echo "Datadir is empty"
    else
        echo "Existing MySQL data detected."
        Prexisting_DB_Data=true
    fi

    echo "Reinitializing MySQL system tables..."
    mysqld --initialize-insecure --user=mysql --datadir="$HOST_MYSQL_DATABASE_PATH"
    sleep 5


    rm -f /var/run/mysqld/mysqld.pid
    echo "Starting MySQL service in skip-grant-tables mode..."
    nohup mysqld_safe --skip-grant-tables --skip-networking=0 > /var/log/mysql/skip.log 2>&1 &
    sleep 8
    echo "MySQL started in skip-grant-tables mode. Setting root password..."
    mysql <<EOF
FLUSH PRIVILEGES;
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '${ROOT_PASS}';
FLUSH PRIVILEGES;
EOF

    echo "Shutting down temporary MySQL..."
    killall -9 mysqld
    sleep 3
    rm -f /var/run/mysqld/mysqld.pid

    # Start MySQL service
    echo "Restarting MySQL service..."
    service mysql restart
    sleep 5

    if [ -S /var/run/mysqld/mysqld.sock ]; then
        echo "MySQL socket is available at /var/run/mysqld/mysqld.sock"
    else
        echo "MySQL socket not found. Startup may have failed."
    fi

    if [ -f "$ROOT_HASH_FILE" ]; then
        if [ "$AUTOMATIC_SETUP" = false ]; then
            echo "Re-enter root password to continue setup:"
            MAX_ATTEMPTS=3
            ATTEMPT=1
            while [ $ATTEMPT -le $MAX_ATTEMPTS ]; do
                read -s -p "Root password: " INPUT_PASS
                echo
                HASHED_INPUT=$(echo -n "$INPUT_PASS" | sha256sum | awk '{print $1}')
                STORED_HASH=$(cat "$ROOT_HASH_FILE")

                if [ "$HASHED_INPUT" = "$STORED_HASH" ]; then
                    echo "Password verified."
                    ROOT_PASS="$INPUT_PASS"
                    break
                else
                    echo "Incorrect password (attempt $ATTEMPT of $MAX_ATTEMPTS)"
                    ((ATTEMPT++))
                fi
            done

            if [ $ATTEMPT -gt $MAX_ATTEMPTS ]; then
                echo "Too many incorrect attempts. Exiting container."
                exit 1
            fi
        else
            echo "Automatic setup mode: Using root password from settings file."
            HASHED_INPUT=$(echo -n "$ROOT_PASS" | sha256sum | awk '{print $1}')
            STORED_HASH=$(cat "$ROOT_HASH_FILE")
            if [ "$HASHED_INPUT" = "$STORED_HASH" ]; then
                echo "Password verified."
            else
                echo "Automatic setup mode: Password verification failed. Exiting container."
                exit 1
            fi
        fi
    else
        echo "FATAL - Irrecoverable Error: root password set but no internal hash exists to validate it."
        exit 1
    fi


    echo "Verifying MySQL root password..."
    if mysql --user=root --password="$ROOT_PASS" -e "SELECT 1;" 2>/dev/null; then
        echo "MySQL root login succeeded."
    else
        echo "MySQL root login failed."
        echo "This error is fatal. Setup cannot continue."
        exec tail -f /dev/null
    fi

    # Perform secure installation steps manually without using mysql_secure_installation
    echo "Running MySQL security configuration..."
    mysql --user=root --password="$ROOT_PASS" << EOF
-- Remove anonymous users
DELETE FROM mysql.user WHERE User='';

-- Disallow root login remotely (restrict root to localhost only)
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');

-- Remove test database and access to it
DROP DATABASE IF EXISTS test;
DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';

-- Reload privilege tables
FLUSH PRIVILEGES;
EOF
    
    echo "MySQL security configured with remote root login disabled."

    echo "Creating MySQL vsftpd user and database..."
    # Generate a secure random password for vsftpd user
    VSFTPD_MYSQL_PASS=$(tr -dc A-Za-z0-9 </dev/urandom | head -c 16)

    # Save vsftpd password to persistent volume within the mysql directory
    # Only allow root to read it. Block all other users
    echo "VSFTPD_MYSQL_PASS=$VSFTPD_MYSQL_PASS" > "$MYSQL_VSFTPD_CREDS_FILE"
    chown root:root "$MYSQL_VSFTPD_CREDS_FILE"
    chmod 700 "$MYSQL_VSFTPD_CREDS_FILE"

    echo "Setting up MySQL vsftpd user and database..."
    mysql --user=root --password="$ROOT_PASS" << EOF
-- Create vsftpd user with random password
DROP USER IF EXISTS 'vsftpd'@'localhost';

CREATE USER 'vsftpd'@'localhost' IDENTIFIED BY '$VSFTPD_MYSQL_PASS';

-- Grant limited privileges to vsftpd user (only what it needs)
GRANT SELECT, INSERT, UPDATE, DELETE ON *.* TO 'vsftpd'@'localhost';

-- Update privileges
FLUSH PRIVILEGES;
EOF

   echo "Setting up aqua2_cloud_logic user and database..."
    mysql --user=root --password="$ROOT_PASS" << EOF
-- Create aqua2_cloud_logic user with random password
DROP USER IF EXISTS 'aqua2_cloud_logic'@'localhost';

-- Create aqua2_cloud_logic user with static password
CREATE USER 'aqua2_cloud_logic'@'localhost' IDENTIFIED BY 'aqua2_cloud_logic_pass';

-- Grant limited privileges to aqua2_cloud_logic user (only what it needs)
GRANT SELECT, INSERT, UPDATE, DELETE ON *.* TO 'aqua2_cloud_logic'@'localhost';

-- Update privileges
FLUSH PRIVILEGES;
EOF

    if [ "$Prexisting_DB_Data" = false ]; then
    mysql --user=root --password="$ROOT_PASS" << EOF
-- Create and initialize the database
CREATE DATABASE AQuA2_Cloud_Database;
USE AQuA2_Cloud_Database;

-- Create aqua_instances table
CREATE TABLE \`aqua_instances\` (
  \`id\` int unsigned NOT NULL AUTO_INCREMENT,
  \`username\` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  \`launched\` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  \`socket\` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  \`terminate\` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  \`running\` varchar(255) NOT NULL DEFAULT 'no',
  \`busy\` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  \`busyProgress\` varchar(255) NOT NULL DEFAULT '0',
  \`busyStatus\` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Undefined',
  \`exception\` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'None',
  PRIMARY KEY (\`id\`) USING BTREE,
  UNIQUE KEY \`id\` (\`id\`)
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create auth_tokens table
CREATE TABLE \`auth_tokens\` (
  \`id\` int unsigned NOT NULL AUTO_INCREMENT,
  \`user_email\` varchar(255) NOT NULL,
  \`auth_type\` varchar(255) NOT NULL,
  \`selector\` text NOT NULL,
  \`token\` longtext NOT NULL,
  \`created_at\` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  \`expires_at\` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (\`id\`),
  UNIQUE KEY \`id\` (\`id\`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create users table
CREATE TABLE \`users\` (
  \`id\` int unsigned NOT NULL AUTO_INCREMENT,
  \`username\` varchar(255) NOT NULL,
  \`email\` varchar(255) NOT NULL,
  \`password\` varchar(255) NOT NULL,
  \`first_name\` varchar(255) DEFAULT NULL,
  \`last_name\` varchar(255) DEFAULT NULL,
  \`gender\` char(1) DEFAULT NULL,
  \`headline\` varchar(255) DEFAULT NULL,
  \`bio\` text,
  \`profile_image\` varchar(255) NOT NULL DEFAULT '_defaultUser.png',
  \`user_data_directory\` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  \`verified_at\` timestamp NULL DEFAULT NULL,
  \`created_at\` timestamp NULL DEFAULT NULL,
  \`updated_at\` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  \`deleted_at\` timestamp NULL DEFAULT NULL,
  \`last_login_at\` timestamp NULL DEFAULT NULL,
  \`passwordFTP\` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  \`passwordFTPHold\` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (\`id\`),
  UNIQUE KEY \`username\` (\`username\`),
  UNIQUE KEY \`email\` (\`email\`),
  UNIQUE KEY \`id\` (\`id\`,\`username\`,\`email\`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
EOF

    if [ "$DEV_MODE" = true ]; then
    echo "Development mode is enabled. Adding test user to the database with password testP..."
    testU_passwordHash='$2y$10$ce/p6JVxYZG2cXpvtlz14uKBhFvgKDVxvxtUGzsS/gwZlurQWn9ai'
    mysql --user=root --password="$ROOT_PASS" << EOF
USE AQuA2_Cloud_Database;

INSERT IGNORE INTO users 
    (id, username, email, password, first_name, last_name, gender, headline, bio, profile_image, user_data_directory, verified_at, created_at, updated_at, deleted_at, last_login_at, passwordFTP, passwordFTPHold) 
VALUES 
    (1, 'testU', 'testE@testE.com', 
    '$testU_passwordHash',
    'testFN1', 'testLN2', NULL, NULL, NULL, '_defaultUser.png', 
    '/opt/a2ud/user_data/testU', 
    '2022-06-09 22:44:41', '2022-06-09 19:44:36', '2022-11-21 18:59:29', NULL, 
    '2022-11-21 18:59:29', 
    CONCAT('*', UPPER(SHA1(UNHEX(SHA1('testP'))))), 
    CONCAT('*', UPPER(SHA1(UNHEX(SHA1('testP')))))
    );
EOF
    fi

    if [ "$CREATE_GUEST_ACCOUNTS" = true ]; then
        echo "Creating guest accounts..."
        for i in {1..5}; do
            # Generate random 15 character password with alphanumeric chars
            GUEST_PASS=$(tr -dc A-Za-z0-9 </dev/urandom | head -c 15)
            GUEST_USER="guest$i"
            GUEST_EMAIL="guest$i@aqua2cloud.local"
            GUEST_DIR="/opt/a2ud/user_data/$GUEST_USER"
            
            echo "Creating guest account: $GUEST_USER with password: $GUEST_PASS"

            mysql --user=root --password="$ROOT_PASS" << EOF
USE AQuA2_Cloud_Database;

INSERT IGNORE INTO users 
    (username, email, password, first_name, last_name, gender, headline, bio, 
    profile_image, user_data_directory, verified_at, created_at, updated_at, 
    deleted_at, last_login_at, passwordFTP, passwordFTPHold) 
VALUES 
    ('$GUEST_USER', '$GUEST_EMAIL',
    CONCAT('*', UPPER(SHA1(UNHEX(SHA1('$GUEST_PASS'))))),
    'Guest', 'User $i', NULL, 'Guest Account', 'Temporary guest account', 
    '_defaultUser.png', '$GUEST_DIR',
    NOW(), NOW(), NOW(), NULL, NOW(),
    CONCAT('*', UPPER(SHA1(UNHEX(SHA1('$GUEST_PASS'))))),
    CONCAT('*', UPPER(SHA1(UNHEX(SHA1('$GUEST_PASS')))))
    );
EOF

            # Save guest credentials to a file for admin reference
            echo "Guest$i: $GUEST_USER / $GUEST_PASS" >> "/opt/a2ud/guest_accounts.txt"
        done
        
        # Secure the credentials file
        chmod 600 "/opt/a2ud/guest_accounts.txt"
        chown root:root "/opt/a2ud/guest_accounts.txt"
        
        echo "Guest accounts created. Credentials saved to /opt/a2ud/guest_accounts.txt"
    fi

    fi
    # Restart MySQL to apply changes
    echo "Restarting MySQL service to apply configuration changes..."
    service mysql restart
    
    echo "MySQL database setup complete."
    echo "IMPORTANT: MySQL root password should be the same as system root password"
    echo "IMPORTANT: vsftpd user password is '$VSFTPD_MYSQL_PASS'"
    
    # Create flag file to indicate MySQL was set up
    touch "$MYSQL_SETUP_FLAG"
else
    echo "MySQL server is already configured."
    
    # Make sure MySQL is running
    if ! service mysql status &> /dev/null; then
        echo "Starting MySQL service..."
        service mysql start
    else
        echo "MySQL service is already running."
    fi
fi

echo "========================="
echo ""
echo "VSFTPD Server Setup"
echo "========================="

CLOUD_SERVER_IP_DOMAIN=$(echo "$CLOUD_SERVER_IP_DOMAIN" | tr -d '\r')

# Create a flag file to indicate VSFTPD has been set up before
VSFTPD_SETUP_FLAG="/var/.vsftpd_configured"
if [ ! -f "$VSFTPD_SETUP_FLAG" ]; then
    echo "Setting up VSFTPD server..."
    
    # Create vsftpd system user
    echo "Creating vsftpd system user..."
    useradd --home /home/vsftpd --gid a2cloud -m --shell /bin/false vsftpd || {
        echo "User vsftpd may already exist, continuing..."
    }
    
    # Install vsftpd and pam-mysql
    echo "Installing vsftpd and libpam-mysql..."
    apt-get install -y vsftpd libpam-mysql
    
    # Ensure user data path exists
    echo "Setting up user data directory structure..."
    if [ ! -d "/opt/a2ud/user_data" ]; then
        mkdir -p /opt/a2ud/user_data
        echo "Created /opt/a2ud/user_data"
    else
        echo "Found existing /opt/a2ud/user_data"
    fi

    chown -R root:a2cloud /opt/a2ud/user_data
    chmod 730 /opt/a2ud/user_data
    chmod g+s /opt/a2ud/user_data

    # Prompt for PASV address (public IP or domain)
    echo ""
    echo "PASV FTP Configuration"
    echo "========================="
    echo "Enter the public IP address or domain name of the host machine/system that is running this docker container."
    echo "This is required for passive mode FTP clients to connect."
    echo "If you are hosting this on your local network for only LAN computers to connect, use the local IP address."
    echo "If you are using a cloud provider, use the public IP address or domain name."
    echo "If you want to only allow connections from the host machine, you can use 127.0.0.1 or localhost."
    if [ "$AUTOMATIC_SETUP" = false ]; then
        read -p "Enter PASV address (e.g. 123.45.67.89 or ftp.example.com): " PASV_ADDRESS
    else
        # Use value from settings file or fallback
        PASV_ADDRESS=${CLOUD_SERVER_IP_DOMAIN:-127.0.0.1}
        echo "Using PASV address from settings: $PASV_ADDRESS"
    fi

    # Prompt for FTP command port
    echo ""
    echo "FTP Command Port Configuration"
    echo "========================="
    echo "By default, AQuA2-Cloud FTP service uses port 33."
    echo "You may override this if you'd like to run vsftpd on another port."
    if [ "$AUTOMATIC_SETUP" = false ]; then
        read -p "Enter custom FTP command port (or press Enter to use default 33): " FTP_PORT
        FTP_PORT=${FTP_PORT:-33}  # Default to 33 if empty
    else
        # Use value from settings file or fallback to default
        FTP_PORT=${VSFTPD_SERVER_PORT:-33}
        echo "Using port from settings: $FTP_PORT"
    fi
    
    # Backup original config and create new one
    echo "Configuring vsftpd..."
    if [ -f /etc/vsftpd.conf ]; then
        cp /etc/vsftpd.conf /etc/vsftpd.conf_orig
    fi

    VSFTPD_SERVER_PSV_MIN_PORT=$(echo "$VSFTPD_SERVER_PSV_MIN_PORT" | tr -d '\r')
    VSFTPD_SERVER_PSV_MAX_PORT=$(echo "$VSFTPD_SERVER_PSV_MAX_PORT" | tr -d '\r')
    VSFTPD_SERVER_PSV_MIN_PORT=${VSFTPD_SERVER_PSV_MIN_PORT:-50000}
    VSFTPD_SERVER_PSV_MAX_PORT=${VSFTPD_SERVER_PSV_MAX_PORT:-50009}

    CERT_KEY_FILE="/opt/a2ud/aqua2_cloud_ssl_certificate/aqua2_cloud_certificate.key"
    CERT_CRT_FILE="/opt/a2ud/aqua2_cloud_ssl_certificate/aqua2_cloud_certificate.crt"

    SSL_COUNTRY=$(echo "$SSL_COUNTRY" | tr -d '\r')
    SSL_STATE=$(echo "$SSL_STATE" | tr -d '\r')
    SSL_LOCALITY=$(echo "$SSL_LOCALITY" | tr -d '\r')
    SSL_ORG=$(echo "$SSL_ORG" | tr -d '\r')
    SSL_CN=$(echo "$CLOUD_SERVER_IP_DOMAIN" | tr -d '\r')

    echo "Generating SSL certificate for AQuA2-Cloud..."
    if [ ! -f "$CERT_CRT_FILE" ]; then
        openssl req -x509 -nodes -days 3650 -newkey rsa:2048 \
            -keyout "$CERT_KEY_FILE" \
            -out "$CERT_CRT_FILE" \
            -subj "/C=$SSL_COUNTRY/ST=$SSL_STATE/L=$SSL_LOCALITY/O=$SSL_ORG/CN=$SSL_CN"
        echo "SSL certificate generated at:"
        echo "   - $CERT_CRT_FILE"
        echo "   - $CERT_KEY_FILE"
    else
        echo "Reusing existing SSL certificate:"
        echo "   - $CERT_CRT_FILE"
        echo "   - $CERT_KEY_FILE"
    fi

    chown root:a2cloud "$CERT_KEY_FILE"
    chmod 750 "$CERT_KEY_FILE"
    
    # Create vsftpd configuration
    cat > /etc/vsftpd.conf << EOF
listen=YES
listen_port=$FTP_PORT
anonymous_enable=NO
local_enable=YES
write_enable=YES
dirmessage_enable=YES
xferlog_enable=YES
nopriv_user=vsftpd
chroot_local_user=YES
secure_chroot_dir=/var/run/vsftpd
pam_service_name=vsftpd
guest_enable=YES
guest_username=vsftpd
local_root=/opt/a2ud/user_data/\$USER
user_sub_token=\$USER
virtual_use_local_privs=YES
user_config_dir=/etc/vsftpd_user_conf
pasv_enable=YES
pasv_min_port=$VSFTPD_SERVER_PSV_MIN_PORT
pasv_max_port=$VSFTPD_SERVER_PSV_MAX_PORT
pasv_address=$PASV_ADDRESS
port_enable=YES
allow_writeable_chroot=YES
local_umask=0007
file_open_mode=0660
virtual_use_local_privs=YES

# Turn on SSL
ssl_enable=YES

# Allow anonymous users to use secured SSL connections
allow_anon_ssl=YES

# All non-anonymous logins are forced to use a secure SSL connection in order to
# send and receive data on data connections.
force_local_data_ssl=YES

# All non-anonymous logins are forced to use a secure SSL connection in order to send the password.
force_local_logins_ssl=YES

# Permit TLS v1 protocol connections. TLS v1 connections are preferred
ssl_tlsv1=YES

# Permit SSL v2 protocol connections. TLS v1 connections are preferred
ssl_sslv2=NO

# permit SSL v3 protocol connections. TLS v1 connections are preferred
ssl_sslv3=NO

# Disable SSL session reuse (required by WinSCP)
require_ssl_reuse=NO

# Select which SSL ciphers vsftpd will allow for encrypted SSL connections (required by FileZilla)
ssl_ciphers=HIGH

rsa_cert_file=$CERT_CRT_FILE
rsa_private_key_file=$CERT_KEY_FILE
EOF
    
    echo "IMPORTANT: The vsftpd user password is stored in $MYSQL_VSFTPD_CREDS_FILE"
    source "$MYSQL_VSFTPD_CREDS_FILE"
    echo "IMPORTANT: vsftpd user password is '$VSFTPD_MYSQL_PASS'"

    # Configure PAM for MySQL authentication
    echo "Configuring PAM for MySQL authentication..."
    if [ -f /etc/pam.d/vsftpd ]; then
        cp /etc/pam.d/vsftpd /etc/pam.d/vsftpd_orig
    fi
    
    # Create the PAM configuration file
    cat > /etc/pam.d/vsftpd << EOF
auth required pam_mysql.so user=vsftpd passwd=$VSFTPD_MYSQL_PASS host=localhost db=AQuA2_Cloud_Database table=users usercolumn=username passwdcolumn=passwordFTP crypt=2
account required pam_mysql.so user=vsftpd passwd=$VSFTPD_MYSQL_PASS host=localhost db=AQuA2_Cloud_Database table=users usercolumn=username passwdcolumn=passwordFTP crypt=2
EOF
    
    # Set appropriate permissions for PAM config file (contains password)
    chmod 750 /etc/pam.d/vsftpd
    chown vsftpd:root /etc/pam.d/vsftpd
    
    # Start or restart vsftpd
    echo "Starting vsftpd service..."
    service vsftpd restart
    
    echo "VSFTPD setup complete."
    
    if [ -f "$HOST_MYSQL_DATABASE_PATH/vsftpd_credentials" ]; then
        echo "Deleting temporary vsftpd MySQL credentials file..."
        rm -f "$HOST_MYSQL_DATABASE_PATH/vsftpd_credentials"
    fi

    # Create flag file to indicate VSFTPD was set up
    touch "$VSFTPD_SETUP_FLAG"
else
    echo "VSFTPD server is already configured."
    
    # Make sure vsftpd is running
    if ! service vsftpd status &> /dev/null; then
        echo "Starting vsftpd service..."
        service vsftpd start
    else
        echo "VSFTPD service is already running."
    fi
fi

echo "========================="
echo ""
echo "Web Server Setup"
echo "========================="

mkdir -p "/var/empty"
chmod 750 "/var/empty"
chown root:a2cloud "/var/empty"

APACHE_SERVER_LOGS_DIRECTORY=$(echo "$APACHE_LOG_DIR" | tr -d '\r')
HOST_APACHE_SERVER_LOGS_DIRECTORY="/opt/a2ud$APACHE_SERVER_LOGS_DIRECTORY"

mkdir -p "$HOST_APACHE_SERVER_LOGS_DIRECTORY"
chmod 770 "$HOST_APACHE_SERVER_LOGS_DIRECTORY"
chown www-data:root "$HOST_APACHE_SERVER_LOGS_DIRECTORY"

# Create a flag file to indicate VSFTPD has been set up before
APACHE_SETUP_FLAG="/var/.apache_configured"

if [ ! -f "$APACHE_SETUP_FLAG" ]; then
    echo "Setting up Apache server..."
    
    # Install Apache
    echo "Installing Apache..."
    apt-get install -y apache2 libapache2-mod-php php-mysqli php-mbstring

    usermod -g a2cloud www-data #Set www-data user group
    usermod -G a2cloud www-data #Set www-data user group

    chown -R www-data:root /opt/a2ud/aqua2_cloud_website
    chmod -R 500 /opt/a2ud/aqua2_cloud_website

    #Increase memory limit for PHP so we can serve images
    TIMESTAMP=$(date +%Y%m%d_%H%M%S)
    # Look for all apache2 SAPI php.ini files under /etc/php
    find /etc/php -type f -path '*/apache2/php.ini' -print0 | while IFS= read -r -d '' PHP_INI; do
    # Backup original
    cp -p "$PHP_INI" "${PHP_INI}.bak.$TIMESTAMP"
    # Update memory_limit to 16G
    sed -ri 's|^\s*memory_limit\s*=.*$|memory_limit = 8G|' "$PHP_INI"
    echo "Updated $PHP_INI"
    done
    
    # Enable necessary Apache modules
    echo "Enabling Apache modules..."
    a2enmod rewrite
    a2enmod ssl

        # Configure Apache SSL virtual host
    echo "Creating Apache virtual host configurations..."
    cat > /etc/apache2/sites-available/redirect-http.conf << EOF
<VirtualHost *:80>
    ServerName $CLOUD_SERVER_IP_DOMAIN
    Redirect permanent / https://$CLOUD_SERVER_IP_DOMAIN/
    DocumentRoot /opt/a2ud/aqua2_cloud_website

    <IfModule mod_php.c>
      php_admin_flag engine Off
    </IfModule>
    <Directory />
      <FilesMatch "\.php$">
        SetHandler None
      </FilesMatch>
      Require all denied
    </Directory>
    Options -Indexes
    DirectoryIndex index.html
    <Directory />
        Require all denied
    </Directory>
</VirtualHost>
EOF

    cat > /etc/apache2/sites-available/aqua2-cloud.conf << EOF
<VirtualHost *:443>
    ServerName $CLOUD_SERVER_IP_DOMAIN
    ServerAdmin servermaster@aqua2-cloud
    DocumentRoot /opt/a2ud/aqua2_cloud_website
    ServerSignature Off

    # Default deny everything outside allowed folders
    <Directory />
        Require all denied
        Options -Indexes
        AllowOverride None
        <FilesMatch "\.php$">
            SetHandler None
        </FilesMatch>
    </Directory>

    <IfModule mod_php.c>
        php_admin_flag engine Off
    </IfModule>

    <Directory /opt/a2ud/aqua2_cloud_website>
        <IfModule mod_php.c>
            php_admin_flag engine On
            php_admin_flag file_uploads On
        </IfModule>
        <FilesMatch "\.php$">
            SetHandler application/x-httpd-php
        </FilesMatch>
        Options -Indexes
        DirectoryIndex index.php
        AllowOverride None
        Require all granted
    </Directory>

    <Directory /opt/a2ud/user_data/>
        <IfModule mod_php.c>
            php_admin_flag engine Off
            php_admin_flag file_uploads Off
        </IfModule>
        Require all granted
        Options -Indexes
        AllowOverride None
    </Directory>

    SSLEngine on
    SSLCertificateFile /opt/a2ud/aqua2_cloud_ssl_certificate/aqua2_cloud_certificate.crt
    SSLCertificateKeyFile /opt/a2ud/aqua2_cloud_ssl_certificate/aqua2_cloud_certificate.key

    ErrorLog $HOST_APACHE_SERVER_LOGS_DIRECTORY/error.log
    CustomLog $HOST_APACHE_SERVER_LOGS_DIRECTORY/access.log combined
</VirtualHost>
EOF

    # Enable the new site
    echo "Enabling Apache AQuA2 Cloud site..."
    # Remove the default site config if it exists
    if [ -f /etc/apache2/sites-available/000-default.conf ]; then
        echo "Removing default Apache site configuration..."
        a2dissite 000-default.conf
        rm -f /etc/apache2/sites-available/000-default.conf
    fi

    a2ensite aqua2-cloud.conf
    a2ensite redirect-http.conf

    # Ensure Apache listens on port 443
    echo "Ensuring Apache listens on port 443..."
    sed -i '/^Listen 443$/d' /etc/apache2/ports.conf

    echo "Creating apache mysqli user and database..."
    # Generate a secure random password for vsftpd user
    APACHE_MYSQL_PASS=$(tr -dc A-Za-z0-9 </dev/urandom | head -c 16)

    MYSQL_APACHE_CREDS_FILE="$HOST_MYSQL_DATABASE_PATH/apache_credentials"

    # Save apache mysql user password to persistent volume within the mysql directory
    # Only allow root to read it. Block all other users
    echo "APACHE_MYSQL_PASS=$APACHE_MYSQL_PASS" > "$MYSQL_APACHE_CREDS_FILE"
    chown root:root "$MYSQL_APACHE_CREDS_FILE"
    chmod 600 "$MYSQL_APACHE_CREDS_FILE"

    # Create the necessary mysql user for apache
    echo "Setting up apache mysql user..."
    mysql --user=root --password="$ROOT_PASS" << EOF
-- Create mysql user with random randomly generated password
DROP USER IF EXISTS 'apache'@'localhost';

CREATE USER 'apache'@'localhost' IDENTIFIED BY '$APACHE_MYSQL_PASS';

-- Grant limited privileges to apache user (only what it needs)
GRANT SELECT, INSERT, UPDATE, DELETE ON *.* TO 'apache'@'localhost';

-- Update privileges
FLUSH PRIVILEGES;
EOF

    DB_INC_FILE="/opt/a2ud/aqua2_cloud_website/assets/setup/env.php"

    # Replace 'REPLACEME' with the actual password, preserving surrounding quotes and formatting
    sed -i "s/\(define('DB_PASSWORD'[[:space:]]*,[[:space:]]*'\)[^']*\(')\)/\1$APACHE_MYSQL_PASS\2/" "$DB_INC_FILE"

    sed -i "s/REPLACEMEWITHFTPPORT/$VSFTPD_SERVER_PORT/g" /opt/a2ud/aqua2_cloud_website/home/index.php
    sed -i "s/REPLACEMEWITHSERVERIPDOMAIN/$CLOUD_SERVER_IP_DOMAIN/g" /opt/a2ud/aqua2_cloud_website/home/index.php

    PAGE_FILE="/opt/a2ud/aqua2_cloud_website/contact/index.php"

    # Remove any carriage returns from variables (in case they come from .txt)
    CONTACTPERSON1NAME=$(echo "$CONTACTPERSON1NAME" | tr -d '\r')
    CONTACTPERSON1INFO=$(echo "$CONTACTPERSON1INFO" | tr -d '\r')
    CONTACTPERSON1EMAIL=$(echo "$CONTACTPERSON1EMAIL" | tr -d '\r')
    CONTACTPERSON2NAME=$(echo "$CONTACTPERSON2NAME" | tr -d '\r')
    CONTACTPERSON2INFO=$(echo "$CONTACTPERSON2INFO" | tr -d '\r')
    CONTACTPERSON2EMAIL=$(echo "$CONTACTPERSON2EMAIL" | tr -d '\r')
    CONTACTPERSON3NAME=$(echo "$CONTACTPERSON3NAME" | tr -d '\r')
    CONTACTPERSON3INFO=$(echo "$CONTACTPERSON3INFO" | tr -d '\r')
    CONTACTPERSON3EMAIL=$(echo "$CONTACTPERSON3EMAIL" | tr -d '\r')

    # Replace placeholders
    sed -i \
    -e "s|REPLACEMECONTACTNAME1|$CONTACTPERSON1NAME|g" \
    -e "s|REPLACEMECONTACTINFO1|$CONTACTPERSON1INFO|g" \
    -e "s|REPLACEMECONTACTEMAIL1|$CONTACTPERSON1EMAIL|g" \
    -e "s|REPLACEMECONTACTNAME2|$CONTACTPERSON2NAME|g" \
    -e "s|REPLACEMECONTACTINFO2|$CONTACTPERSON2INFO|g" \
    -e "s|REPLACEMECONTACTEMAIL2|$CONTACTPERSON2EMAIL|g" \
    -e "s|REPLACEMECONTACTNAME3|$CONTACTPERSON3NAME|g" \
    -e "s|REPLACEMECONTACTINFO3|$CONTACTPERSON3INFO|g" \
    -e "s|REPLACEMECONTACTEMAIL3|$CONTACTPERSON3EMAIL|g" \
    "$PAGE_FILE"

    PAGE_FILE="/opt/a2ud/aqua2_cloud_website/reset-password/index.php"
    # Replace placeholders
    sed -i \
    -e "s|REPLACEMECONTACTNAME1|$CONTACTPERSON1NAME|g" \
    -e "s|REPLACEMECONTACTINFO1|$CONTACTPERSON1INFO|g" \
    -e "s|REPLACEMECONTACTEMAIL1|$CONTACTPERSON1EMAIL|g" \
    -e "s|REPLACEMECONTACTNAME2|$CONTACTPERSON2NAME|g" \
    -e "s|REPLACEMECONTACTINFO2|$CONTACTPERSON2INFO|g" \
    -e "s|REPLACEMECONTACTEMAIL2|$CONTACTPERSON2EMAIL|g" \
    -e "s|REPLACEMECONTACTNAME3|$CONTACTPERSON3NAME|g" \
    -e "s|REPLACEMECONTACTINFO3|$CONTACTPERSON3INFO|g" \
    -e "s|REPLACEMECONTACTEMAIL3|$CONTACTPERSON3EMAIL|g" \
    "$PAGE_FILE"
    
    VERIFY_PAGE_FILE="/opt/a2ud/aqua2_cloud_website/verify/index.php"

    sed -i \
    -e "s|REPLACEMECONTACTNAME1|$CONTACTPERSON1NAME|g" \
    -e "s|REPLACEMECONTACTEMAIL1|$CONTACTPERSON1EMAIL|g" \
    "$VERIFY_PAGE_FILE"

    cat > /usr/local/bin/verify_user.sh << 'EOF'
#!/bin/bash
#
# Usage: verify_user.sh <root_mysql_password> <username>
#

if [ "$#" -ne 2 ]; then
  echo "Usage: $0 <root_mysql_password> <username>"
  exit 1
fi

ROOT_PASS="$1"
USERNAME="$2"

if ! mysql -u root -p"$ROOT_PASS" -e "SELECT 1;" &>/dev/null; then
  echo "ERROR: MySQL root login failed. Check your root password."
  exit 1
fi

mysql -u root -p"$ROOT_PASS" -D AQuA2_Cloud_Database -e "
  UPDATE users
     SET verified_at = NOW(),
         passwordFTP   = passwordFTPHold
   WHERE username = '$USERNAME';
"
echo "'$USERNAME' verified."

EOF

    chmod 700 /usr/local/bin/verify_user.sh

    cat > /usr/local/bin/remove_user.sh << 'EOF'

#!/bin/bash
#
# Usage: remove_user.sh <root_mysql_password> <username>
#

set -euo pipefail

if [ "$#" -ne 2 ]; then
  echo "Usage: <root_mysql_password> <username>"
  exit 1
fi

ROOT_PASS="$1"
USERNAME="$2"
DB="AQuA2_Cloud_Database"
USERDIR="/opt/a2ud/user_data/$USERNAME"

if ! mysql -u root -p"$ROOT_PASS" -e "SELECT 1;" &>/dev/null; then
  echo "ERROR: MySQL root login failed. Check your root password."
  exit 1
fi
mysql -u root -p"$ROOT_PASS" -D "$DB" <<SQL
-- Delete all auth tokens belonging to this user
DELETE FROM auth_tokens
 WHERE user_email = (
   SELECT email FROM users WHERE username = '$USERNAME'
 );
-- Delete the user record
DELETE FROM users WHERE username = '$USERNAME';
SQL

echo "MySQL entries for '$USERNAME' removed."

if [ -d "$USERDIR" ]; then
  rm -rf "$USERDIR"
  echo "Removed user data directory: $USERDIR"
else
  echo "No directory found at $USERDIR"
fi

echo "User '$USERNAME' fully removed."
EOF

    chmod 700 /usr/local/bin/remove_user.sh

    cat > /usr/local/bin/change_user_password.sh << 'EOF'
#!/bin/bash
#
# Usage: change_user_password.sh <root_password> <username> <new_password>
#

set -euo pipefail

if [ "$#" -ne 3 ]; then
  echo "Usage: $0 <root_password> <username> <new_password>"
  exit 1
fi

ROOT_PASS="$1"
USERNAME="$2"
NEW_PASS="$3"
DB="AQuA2_Cloud_Database"

# Update the application user’s password and its FTP fields
mysql -u root -p"$ROOT_PASS" -D "$DB" <<SQL
UPDATE users
  SET
    password = '$NEW_PASS',
    passwordFTP = CONCAT('*', UPPER(SHA1(UNHEX(SHA1('$NEW_PASS'))))),
    passwordFTPHold = CONCAT('*', UPPER(SHA1(UNHEX(SHA1('$NEW_PASS')))))
  WHERE username = '$USERNAME';
FLUSH PRIVILEGES;
SQL

echo "Password for '\$USERNAME' updated."
EOF

    chmod 700 /usr/local/bin/change_user_password.sh
    echo "change_user_password.sh created and made executable."

    # Restart Apache to apply changes
    echo "Restarting Apache..."
    service apache2 stop

    echo "Apache setup complete."

    # Mark Apache as configured
    touch "$APACHE_SETUP_FLAG"
else
    echo "Apache server is already configured."
    service apache2 stop
fi

service apache2 start

echo "========================="
echo ""
echo "MATLAB executor system setup"
echo "========================="

apt-get install -y xvfb x11vnc fluxbox libnss3 libxcomposite1 libxcursor1 libxdamage1 libxi6 libxtst6 libatk1.0-0t64 libgtk-3-0t64 libasound2t64 libgl1 libglu1-mesa libgles2 libegl1 fonts-liberation fonts-dejavu-core libgtk2.0-0

echo "Configuring Xvfb and VNC..."
Xvfb :99 -screen 0 1280x1024x24 > /dev/null 2>&1 < /dev/null &
sleep 2
echo "Exporting DISPLAY variable..."
export DISPLAY=:99
sleep 2
echo "Starting fluxbox..."
fluxbox > /dev/null 2>&1 < /dev/null &
sleep 2
echo "Starting x11vnc..."
x11vnc -display :99 -forever -nopw -listen 0.0.0.0 > /dev/null 2>&1 < /dev/null &
sleep 2

# Create a flag file to indicate if the matlab executor system has been set up before
MATLAB_EXECUTOR_SETUP_FLAG="/var/.matlab_executor_configured"
if [ ! -f "$MATLAB_EXECUTOR_SETUP_FLAG" ]; then
    # Create matlab_executor system user
    echo "Creating matlab_executor system user..."
    useradd --home /home/matlab_executor --gid a2cloud -m --shell /bin/bash matlab_executor || {
        echo "User matlab_executor may already exist, continuing..."
    }

    mkdir -p /opt/a2ud/aqua2_matlab_instance_logs
    chown -R matlab_executor:a2cloud /opt/a2ud/aqua2_matlab_instance_logs
    chmod -R 770 /opt/a2ud/aqua2_matlab_instance_logs
    chmod g+s /opt/a2ud/aqua2_matlab_instance_logs

    UNPACK_DIR="/tmp/matlab_installer/unpacked"
    mkdir -p "$UNPACK_DIR"
    rm -rf "$UNPACK_DIR"/*
    rm -rf "$UNPACK_DIR"/.[!.]* "$UNPACK_DIR"/..?* 2>/dev/null || true
    INSTALLER_ZIP=$(find /tmp/matlab_installer -maxdepth 1 -type f -name "*.zip" | head -n 1)
    if [ -z "$INSTALLER_ZIP" ]; then
        echo "No installer .zip file found in /tmp/matlab_installer."
        exit 1
    fi
    unzip -q "$INSTALLER_ZIP" -d /tmp/matlab_installer/unpacked

    echo "Configuring libstdc++ for installer..."
    mkdir -p /tmp/matlab_installer/unpacked/sys/os/glnxa64/exclude
    mv /tmp/matlab_installer/unpacked/sys/os/glnxa64/libstdc++.so.6* /tmp/matlab_installer/unpacked/sys/os/glnxa64/exclude/

    mkdir -p /tmp/matlab_installer/unpacked/bin/glnxa64/exclude
    mv /tmp/matlab_installer/unpacked/bin/glnxa64/libstdc++.so.6* /tmp/matlab_installer/unpacked/bin/glnxa64/exclude/

    # Find the correct system libstdc++.so.6 version
    echo "Locating system libstdc++ library..."
    SYSTEM_LIBSTDCPP=$(find /usr/lib /lib -name "libstdc++.so.6*" -type f ! -name "*.py" | head -n 1)

    if [ -z "$SYSTEM_LIBSTDCPP" ]; then
        echo "Error: Could not find system libstdc++.so.6 library."
        exit 1
    else
        echo "Found system libstdc++: $SYSTEM_LIBSTDCPP"
        export LD_PRELOAD="$SYSTEM_LIBSTDCPP"
    fi
    export MATLAB_SHELL_WEBAPP_SANDBOXING=0
    export MATLAB_DISABLE_BROWSER_SANDBOX=1

    cd /tmp/matlab_installer/unpacked

    if [ -x "./install" ]; then
        echo "Running MATLAB installer..."
        ./install > /dev/null 2>&1 < /dev/null &
    else
        echo "MATLAB installer not found or not executable at /tmp/matlab_installer/unpacked/install"
        exit 1
    fi

    echo "=========================="
    echo "User manual interaction required. MATLAB GUI installation."
    echo "=========================="
    echo "The MATLAB GUI installer has been started and is attached to an internal display within the container."
    echo "Please use the VNC viewer to connect to the container and complete the installation via the MATLAB installer GUI."
    echo "Here are the settings for the installation prompts:"
    echo "1. Login to the mathworks account that has a MATLAB license that you are willing to use for AQuA2-Cloud. Accept the license agreement."
    echo "2. If you are prompted for a user, put 'matlab_executor' as the user."
    echo "3. The default installation directory of /usr/local/MATLAB/Rxxxxx is needed. Do not change it. AQuA2-Cloud will automatically detect and use this."
    echo "4. Please check the following toolboxes to install:"
    echo "   - Curve Fitting Toolbox"
    echo "   - Image Processing Toolbox"
    echo "   - Signal Processing Toolbox"
    echo "   - Statistics and Machine Learning Toolbox"
    echo "-- AQuA2_Cloud will not work without these toolboxes --"
    echo "5. Create symbolic links is not needed. Leave it unchecked."
    echo "6. Allow the installer to proceed to install MATLAB. When you have completed the installation, exit the installer then allow the container to continue."
    echo "The container will now idle until you press any key to continue."
    echo "==========================="
    read -r -n 1 -s
    while true; do
        echo "Confirm that you completed the MATLAB installation?"
        read -p "Press 'y' to continue: " input
        echo "==========================="
        if [[ "$input" == "y" || "$input" == "Y" ]]; then
            echo "Continuing..."
            break
        fi
    done
    pkill -9 -f matlab

    MATLAB_ROOT="/usr/local/MATLAB"
    MATLAB_VERSION=$(find "$MATLAB_ROOT" -maxdepth 1 -mindepth 1 -type d -printf "%f\n" | grep -E '^R[0-9]{4}[ab]' | sort -r | head -n 1)

    if [ -z "$MATLAB_VERSION" ]; then
        echo "No MATLAB version found in $MATLAB_ROOT."
        exit 1
    fi
    echo "Found MATLAB version: $MATLAB_VERSION"
    MATLAB_DIR="$MATLAB_ROOT/$MATLAB_VERSION"
    echo "Cleaning MATLAB sys/os/glnxa64..."
    mkdir -p "$MATLAB_DIR/sys/os/glnxa64/exclude"
    mv -v "$MATLAB_DIR/sys/os/glnxa64"/libstdc++.so.6* "$MATLAB_DIR/sys/os/glnxa64"/exclude/ 2>/dev/null || true
    mv -v "$MATLAB_DIR/sys/os/glnxa64"/libgcc_s.so* "$MATLAB_DIR/sys/os/glnxa64"/exclude/ 2>/dev/null || true
    mv -v "$MATLAB_DIR/sys/os/glnxa64"/libg2c.so* "$MATLAB_DIR/sys/os/glnxa64"/exclude/ 2>/dev/null || true
    echo "Cleaning MATLAB bin/glnxa64..."
    mkdir -p "$MATLAB_DIR/bin/glnxa64/exclude"
    mv -v "$MATLAB_DIR/bin/glnxa64"/libstdc++.so.6* "$MATLAB_DIR/bin/glnxa64"/exclude/ 2>/dev/null || true
    mv -v "$MATLAB_DIR/bin/glnxa64"/libgcc_s.so* "$MATLAB_DIR/bin/glnxa64"/exclude/ 2>/dev/null || true
    mv -v "$MATLAB_DIR/bin/glnxa64"/libg2c.so* "$MATLAB_DIR/bin/glnxa64"/exclude/ 2>/dev/null || true
    echo "MATLAB libraries cleaned."
    echo "Installing GCC 10 and G++ 10..."
    apt install -y gcc-10 g++-10 build-essential cmake libglib2.0-dev libgtk-3-dev libx11-dev libxtst-dev libmysqlclient-dev

    echo "Setting GCC/G++ default to version 10..."
    update-alternatives --install /usr/bin/gcc gcc /usr/bin/gcc-10 100 --slave /usr/bin/g++ g++ /usr/bin/g++-10

    JAR_PATH="/tmp/aqua2_cloud_logic/jar/matlab-websocket-1.6.jar"
    JAVACLASSPATH_FILE="$MATLAB_DIR/javaclasspath.txt"
    echo "Setting up MATLAB Java classpath..."

    echo "$JAR_PATH" >> "$JAVACLASSPATH_FILE"
    echo "Added $JAR_PATH to javaclasspath.txt"
    chown -R matlab_executor:a2cloud "$JAVACLASSPATH_FILE"

    touch "$MATLAB_EXECUTOR_SETUP_FLAG"
fi

MATLAB_SRC_DIR="/usr/local/MATLAB"

# Auto-detect MATLAB version folder
MATLAB_VERSION_DIR=$(find "$MATLAB_SRC_DIR" -maxdepth 1 -mindepth 1 -type d -printf "%f\n" | grep -E '^R[0-9]{4}[ab]' | sort -r | head -n 1)

if [ -z "$MATLAB_VERSION_DIR" ]; then
    echo "No installed MATLAB version found under $MATLAB_SRC_DIR."
    exit 1
fi

MATLAB_FULL_SRC="$MATLAB_SRC_DIR/$MATLAB_VERSION_DIR"
echo "MATLAB installation: $MATLAB_FULL_SRC"

echo "Copying MATLAB source to RAM disk..."
rsync -a $MATLAB_FULL_SRC /mnt/matlab_ramdisk
echo "Setting permissions for MATLAB source in RAM disk..."
chown -R matlab_executor:a2cloud /mnt/matlab_ramdisk/$MATLAB_VERSION_DIR
echo "Setting permissions for MATLAB source in RAM disk..."
chmod -R u+x /mnt/matlab_ramdisk/$MATLAB_VERSION_DIR/bin/


MATLAB_EXECUTOR_FIRSTTIMELOGIN_FLAG="/var/.matlab_executor_firsttimelogin_configured"
if [ ! -f "$MATLAB_EXECUTOR_FIRSTTIMELOGIN_FLAG" ]; then
    export DISPLAY=:99
    sudo -u matlab_executor /mnt/matlab_ramdisk/$MATLAB_VERSION_DIR/bin/matlab -desktop > /dev/null 2>&1 < /dev/null &

    echo "=========================="
    echo "User manual interaction required. MATLAB first time login"
    echo "=========================="
    echo "The MATLAB has been started and is attached to an internal display within the container."
    echo "MATLAB requires a first time login to complete the installation."
    echo "Please use the VNC viewer to connect to the container and login to MATLAB."
    echo "After you have logged in, please close MATLAB by typing quit in the MATLAB command window."
    echo "The container will now idle until you press any key to continue."
    echo "==========================="
    read -r -n 1 -s
    while true; do
        echo "=========================="
        echo "User manual interaction required. MATLAB first time login"
        echo "=========================="
        echo "The MATLAB has been started and is attached to an internal display within the container."
        echo "MATLAB requires a first time login to complete the installation."
        echo "Please use the VNC viewer to connect to the container and login to MATLAB."
        echo "After you have logged in, please close MATLAB by typing quit in the MATLAB command window."
        echo "==========================="
        echo "Confirm that you have logged into the the MATLAB program and then fully closed it by typing quit in the MATLAB command window?"
        read -p "Press 'y' to continue: " input
        echo "==========================="
        if [[ "$input" == "y" || "$input" == "Y" ]]; then
            echo "Continuing..."
            break
        fi
    done

    echo "Creating MATLAB launcher script..."
    cat > /opt/a2ud/aqua2_cloud_website_shellScripts/launch_matlab_instance.sh << 'EOF'
# Paths
LOG="/opt/a2ud/aqua2_cloud_website_shellScripts/launch_matlab_instance_log.log"
MATLAB_VERSION_DIR=$(find /mnt/matlab_ramdisk -maxdepth 1 -mindepth 1 -type d -printf "%f\n" | grep -E '^R[0-9]{4}[ab]' | sort -r | head -n 1)
MATLAB_EXEC="/mnt/matlab_ramdisk/$MATLAB_VERSION_DIR/bin/matlab"
SOURCE_LOGIC_DIR="/tmp/aqua2_cloud_logic"
LOG_DIR="/opt/a2ud/aqua2_matlab_instance_logs"

# Start logging
echo "[$(date '+%Y-%m-%d %H:%M:%S')] Starting launch_matlab_instance.sh" >> "$LOG"

USERNAME="$1"

if [ -z "$USERNAME" ]; then
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] Error: No username argument provided." >> "$LOG"
    exit 1
fi

if [[ ! "$USERNAME" =~ ^[A-Za-z0-9_-]+$ ]]; then
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] Error: Invalid username format." >> "$LOG"
    exit 1
fi

echo "[$(date '+%Y-%m-%d %H:%M:%S')] Username validated: $USERNAME" >> "$LOG"

if [ -z "$MATLAB_VERSION_DIR" ]; then
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] Error: No MATLAB version detected in /mnt/matlab_ramdisk/" >> "$LOG"
    exit 1
fi

echo "[$(date '+%Y-%m-%d %H:%M:%S')] MATLAB version found: $MATLAB_VERSION_DIR" >> "$LOG"
echo "[$(date '+%Y-%m-%d %H:%M:%S')] Using MATLAB executable: $MATLAB_EXEC" >> "$LOG"

# Prepare log file for MATLAB
DATE=$(date +%Y-%m-%d_%H-%M-%S)
LOG_FILE="$LOG_DIR/aqua2_instance_${USERNAME}_${DATE}.log"

echo "[$(date '+%Y-%m-%d %H:%M:%S')] MATLAB session log will be written to: $LOG_FILE" >> "$LOG"

# Export X11 display
export DISPLAY=:99

echo "[$(date '+%Y-%m-%d %H:%M:%S')] Launching MATLAB as user matlab_executor..." >> "$LOG"

export XDG_RUNTIME_DIR="/tmp/xdg-runtime-matlab_executor"
mkdir -p "$XDG_RUNTIME_DIR"
chmod 700 "$XDG_RUNTIME_DIR"
chown matlab_executor:a2cloud "$XDG_RUNTIME_DIR"

# Launch MATLAB
sudo -u matlab_executor env XDG_RUNTIME_DIR="$XDG_RUNTIME_DIR" DISPLAY="$DISPLAY" "$MATLAB_EXEC" -nosplash -desktop -logfile "$LOG_FILE" -sd "$SOURCE_LOGIC_DIR" -r "try, AQuA2_Cloud_Main('$USERNAME'); catch e, disp(getReport(e)), exit(1); end; exit(0);" >> "$LOG" 2>&1

# Confirm finished
echo "[$(date '+%Y-%m-%d %H:%M:%S')] Finished launch_matlab_instance.sh" >> "$LOG"
EOF
    chmod +x /opt/a2ud/aqua2_cloud_website_shellScripts/launch_matlab_instance.sh
    echo "MATLAB instance launcher script created."

    echo "Setting up sudo permissions for MATLAB launcher..."

    # Define paths
    SUDOERS_FILE="/etc/sudoers.d/aqua2_matlab_launcher"
    LAUNCHER_SCRIPT="/opt/a2ud/aqua2_cloud_website_shellScripts/launch_matlab_instance.sh"

    # Ensure script exists first
    if [ ! -f "$LAUNCHER_SCRIPT" ]; then
        echo "Launcher script not found at $LAUNCHER_SCRIPT, cannot configure sudoers."
        exit 1
    fi

    # Secure the launcher script itself
    chown root:root "$LAUNCHER_SCRIPT"
    chmod 750 "$LAUNCHER_SCRIPT"

    # Create sudoers file
    cat > "$SUDOERS_FILE" <<EOF
    www-data ALL=(ALL) NOPASSWD: $LAUNCHER_SCRIPT
EOF

    # Secure sudoers file
    chmod 440 "$SUDOERS_FILE"
    echo "Sudo permissions configured for www-data to launch MATLAB safely."

    touch "$MATLAB_EXECUTOR_FIRSTTIMELOGIN_FLAG"
fi

MAINTENANCE_SETUP_FLAG="/var/.maintenance_configured"
if [ ! -f "$MAINTENANCE_SETUP_FLAG" ]; then
    # Setup maintenance script and scheduler
    echo "Setting up automated maintenance..."
    apt-get install -y cron

    # Copy maintenance script to the right location
    cat > /usr/local/bin/aqua_maintenance.sh << 'EOF'
LOG_FILE="/opt/a2ud/maintenance.log"
MAX_AGE_DAYS=2

echo "$(date): Starting scheduled maintenance check" >> "$LOG_FILE"

# Get root MySQL password
if [ -f "/var/.container_root_hash" ]; then
    ROOT_PASS=$(grep -o "ROOT_PASS=.*" /containerSetupSettings.txt 2>/dev/null | cut -d= -f2)
    if [ -z "$ROOT_PASS" ]; then
        echo "$(date): ERROR - Unable to retrieve MySQL root password" >> "$LOG_FILE"
        exit 1
    fi
else
    echo "$(date): ERROR - Root password hash file not found" >> "$LOG_FILE"
    exit 1
fi

ROOT_PASS=$(grep -o "ROOT_PASS=.*" /containerSetupSettings.txt | cut -d= -f2 | tr -d '[:space:]')
# Query current instances
CURRENT_INSTANCES=$(mysql --user=root --password="$ROOT_PASS" -N -e "SELECT socket, TIMESTAMPDIFF(HOUR, launched, NOW()) as age_hours FROM AQuA2_Cloud_Database.aqua_instances;" 2>/dev/null)

if [ $? -ne 0 ]; then
    echo "$(date): ERROR - Failed to query MySQL database" >> "$LOG_FILE"
    exit 1
fi

# Count active instances and check age
INSTANCE_COUNT=$(echo "$CURRENT_INSTANCES" | grep -v "^$" | wc -l)
HAS_RECENT_INSTANCE=false

while read -r line; do
    if [ -n "$line" ]; then
        AGE_HOURS=$(echo "$line" | awk '{print $2}')
        if [ "$AGE_HOURS" -lt $((MAX_AGE_DAYS * 24)) ]; then
            HAS_RECENT_INSTANCE=true
            break
        fi
    fi
done <<< "$CURRENT_INSTANCES"

echo "$(date): Found $INSTANCE_COUNT instances. Recent instances: $HAS_RECENT_INSTANCE" >> "$LOG_FILE"

# Determine if we should restart
SHOULD_RESTART=false

if [ "$INSTANCE_COUNT" -eq 0 ]; then
    echo "$(date): No active instances, safe to restart" >> "$LOG_FILE"
    SHOULD_RESTART=true
elif [ "$HAS_RECENT_INSTANCE" = false ]; then
    echo "$(date): Only old instances (>$MAX_AGE_DAYS days) found, proceeding with restart" >> "$LOG_FILE"
    SHOULD_RESTART=true
else
    echo "$(date): Recent active instances found, skipping restart" >> "$LOG_FILE"
fi

# Perform restart if needed
if [ "$SHOULD_RESTART" = true ]; then
    echo "$(date): Preparing for container restart" >> "$LOG_FILE"
    
    # Clear the aqua_instances table
    mysql --user=root --password="$ROOT_PASS" -e "TRUNCATE TABLE AQuA2_Cloud_Database.aqua_instances;" 2>/dev/null
    
    # Stop services gracefully
    echo "$(date): Stopping Apache and MySQL services..." >> "$LOG_FILE"
    timeout 10s service apache2 stop || {
    echo "Apache failed to stop gracefully. Forcing..." >> "$LOG_FILE"
    killall -9 apache2
    }
    timeout 10s service mysql stop || {
    echo "MySQL failed to stop gracefully. Forcing..." >> "$LOG_FILE"
    killall -9 mysqld
    }
    timeout 10s service vsftpd stop || {
    echo "vsftpd failed to stop gracefully. Forcing..." >> "$LOG_FILE"
    killall -9 vsftpd
    }
    
    echo "$(date): Sending restart signal" >> "$LOG_FILE"
    touch /tmp/RESTART_CONTAINER
else
    echo "$(date): No restart needed at this time" >> "$LOG_FILE"
fi
EOF

    # Make script executable
    chmod 700 /usr/local/bin/aqua_maintenance.sh

    # Set up cron job to run at 2 AM
    echo "0 2 * * * /usr/local/bin/aqua_maintenance.sh" > /etc/cron.d/aqua-maintenance
    chmod 0644 /etc/cron.d/aqua-maintenance

    # Start cron service
    service cron start

    echo "Maintenance script installed and scheduled for 2 AM daily"
    touch "$MAINTENANCE_SETUP_FLAG"
fi

echo "Cleaning aqua_instances table"
if mysql --user=root --password="$ROOT_PASS" -e "TRUNCATE TABLE AQuA2_Cloud_Database.aqua_instances;" 2>/dev/null; then
    echo "Successfully cleared aqua_instances table."
else
    echo "Warning: Failed to clear aqua_instances table. This may happen if the database isn't fully initialized yet."
fi

# Now start apache server
if ! service apache2 status &> /dev/null; then
    echo "Starting web server"
    service apache2 start
else
    echo "Web server running"
fi

echo "== AQuA2-Cloud ready to use =="
# Keep the container running indefinitely
while true; do
    # Check for restart signal
    if [ -f /tmp/RESTART_CONTAINER ]; then
        echo "$(date): Restart flag found. Cleaning up and exiting..."
        rm -f /tmp/RESTART_CONTAINER
        exit 1  # Docker restart policy will handle the rest
    fi
    sleep 10
done