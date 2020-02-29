<?php

use Gufy\CpanelPhp\Cpanel;

/**
 * cPanel/WHM API
 *
 * Provides easy to use class for calling some CPanel/WHM API Fileman functions.
 *
 * @author Frederico Cardoso <geral@fredcardoso.pt>
 *
 * @version v0.0.1
 *
 * @link https://github.com/fredcardoso/cPanel-Automated-Backups
 * @since v0.0.1
 */
class Files
{

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

    public function get_files()
    {
        $result = $this->cpanel->execute_action('3', 'Fileman', 'list_files', 'arlcpt')['result'];

        // If request not successful returns null
        if (!$result['status'] == 1) {
            return null;
        }

        $files_and_dirs = $result['data'];
        $files = array();

        foreach ($files_and_dirs as $obj) {
            if ($obj['type'] == "file") {
                $file = new File($obj['humansize'], $obj['ctime'], $obj['file'], $obj['fullpath'], $obj['path']);
                array_push($files, $file);
            }
        }

        print_r($files);
    }
}

class File
{
    private $size;
    private $created_at;
    private $file_name;
    private $full_path;
    private $path;

    public function __construct($size, $created_at, $file_name, $full_path, $path)
    {
        $this->size = $size;
        $this->created_at = $created_at;
        $this->file_name = $file_name;
        $this->full_path = $full_path;
        $this->path = $path;
    }

    public function get_size() {
        return $this->size;
    }

    public function get_created_at() {
        return $this->created_at;
    }

    public function get_file_name() {
        return $this->file_name;
    }

    public function get_full_path() {
        return $this->full_path;
    }

    public function get_path() {
        return $this->path;
    }
}

