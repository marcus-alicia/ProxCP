<?php
//  ProxCP - End-user Proxmox control panel for web hosting providers.
//  Copyright (c) 2021 ProxCP. All Rights Reserved.
//  Version 1.7
//
//  This software is furnished under a license and may be used and copied
//  only in accordance with the terms of such license and with the
//  inclusion of the above copyright notice. This software or any other
//  copies thereof may not be provided or otherwise made available to any
//  other person. No title to and ownership of the software is hereby
//  transferred.
//
//  You may not reverse engineer, decompile, defeat license encryption
//  mechanisms, or disassemble this software product or software product
//  license. ProxCP may terminate this license if you don't comply with any
//  of the terms and conditions set forth in our end user license agreement
//  (EULA). In such event, licensee agrees to destroy all copies of software
//  upon termination of the license.

//////////////////////////////////////////////
//     BEGIN USER CONFIGURATION SECTION     //
//////////////////////////////////////////////

$GLOBALS['config'] = array(
	// DATABASE CONFIGURATION
	'database' => array(
		'type' => 'mysql',
		'host' => '127.0.0.1',
		'username' => 'db.username.default',
		'password' => 'db.password.default',
		'db' => 'vncp'
	),
	'instance' => array(
		'base' => 'http://localhost.localdomain', // BASE DOMAIN OF THIS PROXCP INSTALLATION
		'installed' => false, // HAS PROXCP BEEN INSTALLED?
		'l_salt' => 'default', // DO NOT CHANGE OR SHARE THESE VALUES - SALT 1
		'v_salt' => 'default', // DO NOT CHANGE OR SHARE THESE VALUES - SALT 2
		'vncp_secret_key' => 'default' // DO NOT CHANGE OR SHARE THESE VALUES - SECRET KEY
	),
	'admin' => array(
		'base' => 'admin.base.default' // BASE ADMIN FILE NAME WITHOUT FILE EXTENSION
	),
	// REMEMBER ME LOGIN SETTINGS
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 604800
	),
	// LOGIN SESSION SETTINGS
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
	)
);

//////////////////////////////////////////////
//      END USER CONFIGURATION SECTION      //
//////////////////////////////////////////////
