<?php

require __DIR__ . '/vendor/autoload.php';

use \Gufy\CpanelPhp\Cpanel;

include('config.php');

//TODO: Readme further details and instructions

/**
 * Class Automation
 * Main logic of the backup application is here.
 * To execute the backup automation call the execute() method.
 */
class Automation
{
    /**
     * @var Cpanel object
     */
    private $cpanel;

    /**
     * Automation constructor. Instantiates the required cPanel object, with API access.
     */
    public function __construct()
    {
        $this->cpanel = new Cpanel([
            'host' => CPANEL_HOST, // ip or domain complete with its protocol and port
            'username' => CPANEL_USER, // username of your server, it usually root.
            'auth_type' => CPANEL_AUTH_TYPE, // set 'hash' or 'password'
            'password' => CPANEL_PASS, // long hash or your user's password
        ]);
    }

    /**
     *
     * Main execution method, which has the main application logic.
     *
     * @return null
     * @throws Exception
     */
    public function execute()
    {
        $backup = new Backup($this->cpanel);

        $debug = false;

        if ($debug) {

            if (!$backup->generate_backup()) {
                return null;
            }

            print_r("Generating backup... \n");
            sleep(BACKUP_WAITING_TIME);

            $backup_status = false;
            $timeout_timer = time();

            while (!$backup_status) {
                $latest_backup = $backup->get_latest_backup();

                if ($latest_backup->get_status() == "complete") {
                    $backup_status = true;
                }

                sleep(2);

                if (time() - $timeout_timer > BACKUP_WAITING_TIMEOUT) {
                    print_r("Backup timeout! Exiting... \n");
                    return null;
                }
            }
        }

        print_r("Backup successful! Uploading... \n");



        //TODO: Upload to OneDrive
        //TODO: Upload to remote FTP Server
    }
}

$main = new Automation();
$main->execute();

