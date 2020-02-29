<?php

/**
 * Configuration file
 *
 * @author Frederico Cardoso <geral@fredcardoso.pt>
 *
 * @version v0.0.1
 *
 * @link https://github.com/fredcardoso/cPanel-Automated-Backups
 * @since v0.0.1
 */

define("VERSION", "0.0.1");
define("BACKUP_WAITING_TIME", "120"); //Defines the time the system waits for the backup to be completed in seconds. This must be adjusted according to the expected backup size and duration.
define("BACKUP_WAITING_TIMEOUT", "120"); //Defines the time the system waits to check for a succesful backup. After this time (in seconds), the system returns null and assumes a unsuccessful backup

// cPanel
define("CPANEL_HOST", "");
define("CPANEL_USER", "");
define("CPANEL_AUTH_TYPE", ""); //password or hash
define("CPANEL_PASS", "");

