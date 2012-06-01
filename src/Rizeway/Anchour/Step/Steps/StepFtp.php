<?php

namespace Rizeway\Anchour\Step\Steps;

use Rizeway\Anchour\Step\Step;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StepFtp extends Step
{
    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array(
            'host',
            'username',
            'password'
        ));

        $resolver->setDefaults(array(
            'local_dir' => '',
            'remote_dir' => ''
        ));
    }

    public function run()
    {
        $connection = ftp_connect($this->options['host']);
        $login      = ftp_login($connection, $this->options['username'], $this->options['password']);

        if (!$connection || !$login) {
            throw new \Exception('FTP connection has failed');
        }

        $root = getcwd().'/'.$this->options['local_dir'].'/';
        $files = $this->getFilesInFolder($root);

        if (count($files)) {
            $this->createFtpFolderRecursively($connection, $this->options['remote_dir']);
            foreach ($files as $file) {
                $remote_file = str_replace($root, '', $file);
                $this->createFtpFolderRecursively($connection, dirname($remote_file));
                ftp_chdir($connection, $this->options['remote_dir']);
                $movefile = ftp_put($connection, $remote_file, $file, FTP_BINARY);

                if (!$movefile) {
                    throw new \Exception(sprintf('FTP upload has failed : %s', $file));
                }
            }
        }
    }

    /**
     * get All Files In a Folder
     * @param  string $folder Folder Path
     * @return string[]
     */
    protected function getFilesInFolder($folder)
    {
        $files = array();
        $handle = opendir($folder);
        while (($file = readdir($handle)) !== false) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($folder . $file)) {
                    $files = array_merge($files, $this->getFilesInFolder($folder . $file . '/'));
                } else {
                    $files[] = $folder . $file;
                }
            }
        }
        closedir($handle);

        return $files;
    }

    /**
     * Créate recursively an FTP Folder
     * @param  integer $con_id The FTP Stream
     * @param  string $path Folder path
     * @return bool
     */
    protected function createFtpFolderRecursively($con_id, $path)
    {
        $parts = explode("/",$path);
        $return = true;
        $fullpath = "";
        foreach($parts as $part){
            if(empty($part)){
                $fullpath .= "/";
                continue;
            }
            $fullpath .= $part."/";
            if(@ftp_chdir($con_id, $fullpath)){
               ftp_chdir($con_id, $fullpath);
            }else{
                if(@ftp_mkdir($con_id, $part)){
                    ftp_chdir($con_id, $part);
                }else{
                    $return = false;
                }
            }
        }
        return $return;
    }
}