<?php
namespace PostSnippets;

/**
 * Post Snippets I/O.
 *
 * Class to handle import and export of Snippets.
 *
 */
class ImportExport
{
    protected static $FILE_ZIP;
    const FILE_CFG = 'post-snippets-export.cfg';

    private $downloadUrl;

    /**
     * Export Snippets.
     *
     * Check if an export file shall be created, or if a download url should be
     * pushed to the footer. Also checks for old export files laying around and
     * deletes them (for security).
     *
     * @return void
     */
    public function exportSnippets()
    {
        self::set_export_name();
        if (isset($_POST['postsnippets_export'])) {
            $url = $this->createExportFile();
            if ($url) {
                $this->downloadUrl = $url;
                add_action(
                    'admin_footer',
                    array(&$this, 'psnippetsFooter'),
                    10000
                );
            } else {
                echo __('Error: ', 'post-snippets').$url;
            }
        } else {
            // Check if there is any old export files to delete
            $dir = wp_upload_dir();
            $upload_dir = $dir['basedir'] . '/';
            chdir($upload_dir);
            if (file_exists('./'.self::$FILE_ZIP)) {
                unlink('./'.self::$FILE_ZIP);
            }
        }
    }

    /**
     * Handles uploading of post snippets archive and import the snippets.
     *
     * @uses   wp_handle_upload() in wp-admin/includes/file.php
     * @return string HTML to handle the import
     */
    public function importSnippets()
    {
        $import =
        '<br/><br/><strong>'.
        __('Import', 'post-snippets').
        '</strong><br/>';
        if (!isset($_FILES['postsnippets_import_file'])
            || empty($_FILES['postsnippets_import_file'])
        ) {
            $import .=
            '<p>'.__('Import snippets from a post-snippets-export.zip file. Importing overwrites any existing snippets.', 'post-snippets').
            '</p>';
            $import .= '<form method="post" enctype="multipart/form-data">';
            $import .= '<input type="file" name="postsnippets_import_file"/>';
            $import .= '<input type="hidden" name="action" value="wp_handle_upload"/>';
            $import .=
            '<input type="submit" class="button" value="'.
            __('Import Snippets', 'post-snippets').'"/>';
            $import .= '</form>';
        } else {
            $file = wp_handle_upload($_FILES['postsnippets_import_file']);

            if (isset($file['file']) && !is_wp_error($file)) {
                require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');
                $zip = new \PclZip($file['file']);
                $dir = wp_upload_dir();
                $upload_dir = $dir['basedir'] . '/';
                chdir($upload_dir);
                $unzipped = $zip->extract();

                if ($unzipped[0]['stored_filename'] == self::FILE_CFG
                    && $unzipped[0]['status'] == 'ok'
                ) {
                    // Delete the uploaded archive
                    unlink($file['file']);

                    $snippets = file_get_contents(
                        $upload_dir.self::FILE_CFG
                    );

                    if ($snippets) {
                        $snippets = apply_filters(
                            'post_snippets_import',
                            $snippets
                        );
                        update_option(
                            \PostSnippets::OPTION_KEY,
                            unserialize($snippets)
                        );
                    }

                    // Delete the snippet file
                    unlink('./'.self::FILE_CFG);

                    $import .=
                    '<p><strong>'.
                    __('Snippets successfully imported.', 'post-snippets').
                    '</strong></p>';
                } else {
                    $import .=
                    '<p><strong>'.
                    __('Snippets could not be imported:', 'post-snippets').
                    ' '.
                    __('Unzipping failed.', 'post-snippets').
                    '</strong></p>';
                }
            } else {
                if ($file['error'] || is_wp_error($file)) {
                    $import .=
                    '<p><strong>'.
                    __('Snippets could not be imported:', 'post-snippets').
                    ' '.
                    $file['error'].'</strong></p>';
                } else {
                    $import .=
                    '<p><strong>'.
                    __('Snippets could not be imported:', 'post-snippets').
                    ' '.
                    __('Upload failed.', 'post-snippets').
                    '</strong></p>';
                }
            }
        }
        return $import;
    }

    /**
     * Create a zipped filed containing all Post Snippets, for export.
     *
     * @return string URL to the exported snippets
     */
    private function createExportFile()
    {
        $snippets = serialize(get_option(\PostSnippets::OPTION_KEY));
        $snippets = apply_filters('post_snippets_export', $snippets);
        $dir = wp_upload_dir();
        $upload_dir = $dir['basedir'] . '/';
        $upload_url = $dir['baseurl'] . '/';

        // Open a file stream and write the serialized options to it.
        if (!$handle = fopen($upload_dir.'./'.self::FILE_CFG, 'w')) {
            die();
        }
        if (!fwrite($handle, $snippets)) {
            die();
        }
        fclose($handle);

        // Create a zip archive
        require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');
        chdir($upload_dir);
        $zip = new \PclZip('./'.self::$FILE_ZIP);
        $zipped = $zip->create('./'.self::FILE_CFG);

        // Delete the snippet file
        unlink('./'.self::FILE_CFG);

        if (!$zipped) {
            return false;
        }

        return $upload_url.'./'.self::$FILE_ZIP;
    }

    /**
     * Set export file name
     */
    public static function set_export_name(){
        $date_part = date('Y-m-d');
        self::$FILE_ZIP =  "post-snippets-export-{$date_part}.zip";
    }

    /**
     * Generates the javascript to trigger the download of the file.
     *
     * @return void
     */
    public function psnippetsFooter()
    {
        $export = '<script type="text/javascript">
                        document.location = \''.$this->downloadUrl.'\';
                   </script>';
        echo $export;
    }
}
