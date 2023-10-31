CREATE TABLE IF NOT EXISTS vncp_acl (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    ipaddress VARCHAR(15)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_forward_dns_domain (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT,
    domain VARCHAR(250)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_forward_dns_record (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT,
    domain VARCHAR(250),
    name VARCHAR(250),
    type VARCHAR(5),
    address VARCHAR(16),
    cname VARCHAR(250),
    preference INT,
    exchange VARCHAR(250),
    priority INT,
    weight INT,
    port INT,
    target VARCHAR(250),
    txtdata VARCHAR(250)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_groups (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(20),
    permissions TEXT
) DEFAULT COLLATE latin1_swedish_ci;
INSERT INTO vncp_groups (name, permissions)
VALUES ('Standard user', '');
INSERT INTO vncp_groups (name, permissions)
VALUES ('Administrator', '{"admin": 1}');
CREATE TABLE IF NOT EXISTS vncp_ipv6_assignment (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    hb_account_id INT,
    address VARCHAR(39),
    ipv6_pool_id INT
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_kvm_cloud (
    user_id INT,
    nodes VARCHAR(100),
    hb_account_id INT,
    pool_id VARCHAR(50),
    pool_password VARCHAR(200),
    memory INT,
    cpu_cores INT,
    cpu_type VARCHAR(10),
    disk_size INT,
    ip_limit INT,
    ipv4 VARCHAR(10000),
    avail_memory INT,
    avail_cpu_cores INT,
    avail_disk_size INT,
    avail_ip_limit INT,
    avail_ipv4 VARCHAR(10000),
    suspended INT
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_kvm_ct (
    user_id INT,
    node VARCHAR(50),
    os VARCHAR(255),
    hb_account_id INT,
    pool_id VARCHAR(100),
    pool_password VARCHAR(200),
    ip VARCHAR(20),
    suspended INT,
    allow_backups INT,
    fw_enabled_net0 INT,
    fw_enabled_net1 INT,
    has_net1 INT,
    onboot INT,
    cloud_account_id INT,
    cloud_hostname VARCHAR(100),
    from_template INT
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_kvm_isos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    friendly_name VARCHAR(100),
    volid VARCHAR(200),
    content VARCHAR(50)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_lxc_ct (
    user_id INT,
    node VARCHAR(50),
    os VARCHAR(255),
    hb_account_id INT,
    pool_id VARCHAR(100),
    pool_password VARCHAR(200),
    ip VARCHAR(20),
    suspended INT,
    allow_backups INT,
    fw_enabled_net0 INT,
    fw_enabled_net1 INT,
    has_net1 INT,
    tuntap INT,
    onboot INT,
    quotas INT
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_lxc_templates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    friendly_name VARCHAR(100),
    volid VARCHAR(200),
    content VARCHAR(50)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_nodes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hostname VARCHAR(50),
    username VARCHAR(10),
    password VARCHAR(200),
    realm VARCHAR(3),
    port INT,
    name VARCHAR(50),
    location VARCHAR(50),
    asn VARCHAR(10),
    cpu VARCHAR(50),
    mailing_enabled INT,
    backup_store VARCHAR(25)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_notes (id INT, notes VARCHAR(10000)) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_pending_deletion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    hb_account_id INT,
    cloud_account_id INT,
    code VARCHAR(6),
    date_created DATETIME
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_private_pool (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    hb_account_id INT,
    address VARCHAR(15),
    available INT,
    netmask VARCHAR(15),
    nodes VARCHAR(500)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_reverse_dns (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT,
    type VARCHAR(3),
    ipaddress VARCHAR(40),
    hostname VARCHAR(250)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_secondary_ips (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    hb_account_id INT,
    address VARCHAR(15)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100),
    username VARCHAR(100),
    password VARCHAR(64),
    salt VARCHAR(40),
    `tfa_enabled` INT DEFAULT 0,
    `tfa_secret` VARCHAR(16),
    `group` INT DEFAULT 1,
    locked INT,
    language VARCHAR(2)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_users_ip_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT,
    date DATETIME,
    ip VARCHAR(45),
    geoip_loc VARCHAR(150),
    geoip_coords VARCHAR(50)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_users_rebuild_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT,
    date VARCHAR(100),
    vmid INT,
    hostname VARCHAR(250)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_users_session (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    hash VARCHAR(64)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item VARCHAR(100),
    value VARCHAR(280)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_ipv6_pool (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subnet VARCHAR(45),
    nodes VARCHAR(500)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_log_admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    msg VARCHAR(100),
    severity INT,
    date DATETIME,
    username VARCHAR(100),
    ipaddress VARCHAR(45)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_log_error (
    id INT PRIMARY KEY AUTO_INCREMENT,
    msg VARCHAR(100),
    severity INT,
    date DATETIME,
    username VARCHAR(100),
    ipaddress VARCHAR(45)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_log_general (
    id INT PRIMARY KEY AUTO_INCREMENT,
    msg VARCHAR(100),
    severity INT,
    date DATETIME,
    username VARCHAR(100),
    ipaddress VARCHAR(45)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_dhcp (
    id INT PRIMARY KEY AUTO_INCREMENT,
    mac_address VARCHAR(17),
    ip VARCHAR(15),
    gateway VARCHAR(15),
    netmask VARCHAR(15),
    network VARCHAR(15),
    type INT
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_tuntap (
    id INT PRIMARY KEY AUTO_INCREMENT,
    node VARCHAR(50),
    password VARCHAR(200),
    port INT
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_dhcp_servers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hostname VARCHAR(100),
    password VARCHAR(200),
    port INT,
    dhcp_network VARCHAR(15)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_kvm_templates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vmid INT,
    friendly_name VARCHAR(100),
    type VARCHAR(7),
    node VARCHAR(50)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_pending_clone (
    id INT PRIMARY KEY AUTO_INCREMENT,
    node VARCHAR(50),
    upid VARCHAR(100),
    hb_account_id INT,
    data VARCHAR(1000)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_bandwidth_monitor (
    id INT PRIMARY KEY AUTO_INCREMENT,
    node VARCHAR(50),
    pool_id VARCHAR(100),
    hb_account_id INT,
    ct_type VARCHAR(4),
    current BIGINT,
    max BIGINT,
    reset_date DATETIME,
    suspended INT
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_api (
    id INT PRIMARY KEY AUTO_INCREMENT,
    api_id VARCHAR(32),
    api_key VARCHAR(32),
    api_ip VARCHAR(15)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_ipv4_pool (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    hb_account_id INT,
    address VARCHAR(15),
    available INT,
    netmask VARCHAR(15),
    nodes VARCHAR(500),
    gateway VARCHAR(15)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_kvm_isos_custom (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    fname VARCHAR(50),
    type VARCHAR(7),
    rname VARCHAR(150),
    upload_date DATETIME,
    status ENUM('created', 'uploaded', 'copying', 'active') NOT NULL,
    upload_key VARCHAR(64),
    download_key VARCHAR(64)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_nat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    node VARCHAR(50),
    publicip VARCHAR(15),
    natcidr VARCHAR(18),
    natnetmask VARCHAR(13),
    vmlimit INT
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_natforwarding (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    node VARCHAR(50),
    hb_account_id INT,
    avail_ports INT,
    ports VARCHAR(2000),
    avail_domains INT,
    domains VARCHAR(10000)
) DEFAULT COLLATE latin1_swedish_ci;
CREATE TABLE IF NOT EXISTS vncp_ct_backups (
    id INT PRIMARY KEY AUTO_INCREMENT,
    userid INT,
    hb_account_id INT,
    backuplimit INT
) DEFAULT COLLATE latin1_swedish_ci;
INSERT INTO vncp_settings (item, value)
VALUES ('app_name', 'ProxCP');
INSERT INTO vncp_settings (item, value)
VALUES ('enable_firewall', 'true');
INSERT INTO vncp_settings (item, value)
VALUES ('enable_forward_dns', 'false');
INSERT INTO vncp_settings (item, value)
VALUES ('enable_reverse_dns', 'false');
INSERT INTO vncp_settings (item, value)
VALUES ('enable_notepad', 'true');
INSERT INTO vncp_settings (item, value)
VALUES ('enable_status', 'true');
INSERT INTO vncp_settings (item, value)
VALUES ('enable_panel_news', 'true');
INSERT INTO vncp_settings (item, value)
VALUES (
        'support_ticket_url',
        'http://localhost.localdomain'
    );
INSERT INTO vncp_settings (item, value)
VALUES ('user_acl', 'true');
INSERT INTO vncp_settings (item, value)
VALUES ('cloud_accounts', 'false');
INSERT INTO vncp_settings (item, value)
VALUES ('vm_ipv6', 'false');
INSERT INTO vncp_settings (item, value)
VALUES ('private_networking', 'false');
INSERT INTO vncp_settings (item, value)
VALUES ('secondary_ips', 'true');
INSERT INTO vncp_settings (item, value)
VALUES (
        'panel_news',
        'Place any news/announcements here for all users to see.'
    );
INSERT INTO vncp_settings (item, value)
VALUES ('whm_url', '');
INSERT INTO vncp_settings (item, value)
VALUES ('whm_username', '');
INSERT INTO vncp_settings (item, value)
VALUES ('whm_api_token', '');
INSERT INTO vncp_settings (item, value)
VALUES ('forward_dns_domain_limit', '25');
INSERT INTO vncp_settings (item, value)
VALUES ('ipv6_limit', '16');
INSERT INTO vncp_settings (item, value)
VALUES ('enable_backups', 'false');
INSERT INTO vncp_settings (item, value)
VALUES ('backup_limit', '2');
INSERT INTO vncp_settings (item, value)
VALUES ('from_email', 'no-reply@localhost.localdomain');
INSERT INTO vncp_settings (item, value)
VALUES ('forward_dns_blacklist', 'in-addr.arpa;ip6.arpa');
INSERT INTO vncp_settings (item, value)
VALUES (
        'forward_dns_nameservers',
        'ns1.localhost.localdomain;ns2.localhost.localdomain'
    );
INSERT INTO vncp_settings (item, value)
VALUES ('bw_auto_suspend', 'false');
INSERT INTO vncp_settings (item, value)
VALUES ('enable_whmcs', 'false');
INSERT INTO vncp_settings (item, value)
VALUES ('whmcs_url', '');
INSERT INTO vncp_settings (item, value)
VALUES ('whmcs_id', '');
INSERT INTO vncp_settings (item, value)
VALUES ('whmcs_key', '');
INSERT INTO vncp_settings (item, value)
VALUES ('ipv6_mode', 'single');
INSERT INTO vncp_settings (item, value)
VALUES ('ipv6_limit_subnet', '1');
INSERT INTO vncp_settings (item, value)
VALUES ('default_language', 'en');
INSERT INTO vncp_settings (item, value)
VALUES ('user_iso_upload', 'false');
INSERT INTO vncp_settings (item, value)
VALUES ('from_email_name', 'ProxCP');
INSERT INTO vncp_settings (item, value)
VALUES ('smtp_host', '');
INSERT INTO vncp_settings (item, value)
VALUES ('smtp_port', '25');
INSERT INTO vncp_settings (item, value)
VALUES ('smtp_username', '');
INSERT INTO vncp_settings (item, value)
VALUES ('smtp_password', '');
INSERT INTO vncp_settings (item, value)
VALUES ('smtp_type', 'none');
INSERT INTO vncp_settings (item, value)
VALUES ('mail_type', 'sysmail');
