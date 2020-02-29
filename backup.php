<?php

use Gufy\CpanelPhp\Cpanel;

/**
 * cPanel/WHM API
 *
 * Provides easy to use class for calling some CPanel/WHM API Backup functions.
 *
 * @author Frederico Cardoso <geral@fredcardoso.pt>
 *
 * @version v0.0.1
 *
 * @link https://github.com/fredcardoso/cPanel-Automated-Backups
 * @since v0.0.1
 */

//TODO: Deal with possible exceptions

class Backup {
    /**
     * @var Cpanel cPanel object instance.
     *
     * @since v0.0.1
     */
    private $cpanel;

    /**
     * Class constructor. cPanel object instance with access must be passed as argument.
     *
     * @param Cpanel $cpanel cPanel object instance
     *
     * @return self
     * @since v0.0.1
     */
    public function __construct($cpanel)
    {
        $this->cpanel = $cpanel;
    }

    /**
     * Backups getter function. Gets the list of backups in the home directory.
     * Using API v2 since UAPI doesn't provide support for full backups in home dir.
     *
     * @return array of objects of type BackupFile
     * @throws Exception
     * @since v0.0.1
     */
    public function get_backups() {
        $result = $this->cpanel->execute_action('2', 'Backups', 'listfullbackups', 'arlcpt')['cpanelresult'];

        //Returns null if result not successful
        if(!$result['postevent']['result'] == 1) {
            return null;
        }

        $data = $result['data'];
        $backups = array();

        foreach ($data as $obj) {
            $backup = new BackupFile($obj['status'], $obj['file']);
            array_push($backups, $backup);
        }

        return $backups;
    }

    /**
     * Last backup getter function.
     *
     *
     * @return BackupFile object, represeting the latest backup available
     * @throws Exception
     * @since v0.0.1
     */
    public function get_latest_backup() {
        $backups = $this->get_backups();

        //TODO: Sort by date/timestamp
        if(sizeof($backups) > 0) {
            return $backups[sizeof($backups)-1];
        }

        return null;
    }

    /**
     * Function to request a backup from cPanel
     *
     * @return boolean, true if successful call, false if not.
     * @throws Exception
     * @since v0.0.1
     */
    public function generate_backup() {
        $result = $this->cpanel->execute_action('3', 'Backup', 'fullbackup_to_homedir', 'arlcpt')['result'];

        //Returns false if result not successful
        if(!$result['status'] == 1) {
            return false;
        }

        return true;
    }
}

/**
 * cPanel/WHM API
 *
 * Class to represent a Backup File.
 *
 * @author Frederico Cardoso <geral@fredcardoso.pt>
 *
 * @version v0.0.1
 *
 * @link https://github.com/fredcardoso/cPanel-Automated-Backups
 * @since v0.0.1
 */

class BackupFile {
    /**
     * @var string representing backup status.
     * Complete if done or Pending if not completed yet.
     */
    private $status;
    /**
     * @var string representing backup file name.
     *
     */
    private $file;

    /**
     * BackupFile constructor.
     * @param $status string
     * @param $file string
     */
    public function __construct($status, $file)
    {
        $this->status = $status;
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function get_status() {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function get_file() {
        return $this->file;
    }
}