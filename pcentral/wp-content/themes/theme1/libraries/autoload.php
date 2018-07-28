<?php
/**
 * Autoload
 * 
 * Autoload that loads class files recursively starting in the directory
 * where this class resides.  Additional options can be provided to control the naming
 * convention of the class files.
 *
 * @package Autoload
 * @author  Jason Bronson
 */
class Autoload
{
    /**
     * File extension as a string. Defaults to ".php".
     */
    protected static $fileExt = '.php';

    /**
     * The top level directory where recursion will begin. Defaults to the current
     * directory.
     */
    protected static $pathTop = __DIR__;

    /**
     * A placeholder to hold the file iterator so that directory traversal is only
     * performed once.
     */
    protected static $fileIterator = null;

    /**
     * Autoload function for registration with spl_autoload_register
     *
     * Looks recursively through project directory and loads class files based on
     * filename match.
     *
     * @param string $className
     */
    public static function load($className)
    {
        
        $directory = new RecursiveDirectoryIterator(static::$pathTop, RecursiveDirectoryIterator::SKIP_DOTS);
        
        if (is_null(static::$fileIterator)) {

            static::$fileIterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);

        }

        $filename = $className . static::$fileExt;
        
        foreach (static::$fileIterator as $file) {

            $fileFound = strtolower($file->getFilename());
            $fileLoading = strtolower($filename);
            if(strpos($fileLoading, '\\') !== false){
                $fileWithNamespace = explode('\\', $fileLoading);
            }else{
                $fileWithNamespace = array();
            }
            
            if ( $fileFound === $fileLoading || end($fileWithNamespace) == $fileFound ) {
                if ($file->isReadable()) {
                    include_once $file->getPathname();
                }
                break;

            }

        }

    }

    /**
     * Loads all other files not class related.
     *
     * 
     */
    public static function loadAllProceduralFiles()
    {

        $directory = new RecursiveDirectoryIterator(static::$pathTop, RecursiveDirectoryIterator::SKIP_DOTS);
        
        if (is_null(static::$fileIterator)) {

            static::$fileIterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);

        }

        foreach (static::$fileIterator as $file )
        {
           
            include_once $file;
            
        }
        
    }

    /**
     * Sets the $fileExt property
     *
     * @param string $fileExt The file extension used for class files.  Default is "php".
     */
    public static function setFileExt($fileExt)
    {
        static::$fileExt = $fileExt;
    }

    /**
     * Sets the $path property
     *
     * @param string $path The path representing the top level where recursion should
     *                     begin. Defaults to the current directory.
     */
    public static function setPath($path)
    {
        static::$pathTop = $path;
    }

}
spl_autoload_register('Autoload::load');
//AutoLoad::loadAllProceduralFiles();