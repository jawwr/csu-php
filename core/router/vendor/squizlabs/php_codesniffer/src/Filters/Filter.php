<?php
/**
 * A base filter class for filtering out files and folders during a run.
 *
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2015 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/PHPCSStandards/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */

namespace PHP_CodeSniffer\Filters;

use FilesystemIterator;
use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Ruleset;
use PHP_CodeSniffer\Util\Common;
use RecursiveDirectoryIterator;
use RecursiveFilterIterator;
use ReturnTypeWillChange;

class Filter extends RecursiveFilterIterator
{

    /**
     * A list of file paths we've already accepted.
     *
     * Used to ensure we aren't following circular symlinks.
     *
     * @var array
     */
    protected $acceptedPaths = [];

    /**
     * The top-level path we are filtering.
     *
     * @var string
     */
    protected $basedir = null;

    /**
     * The config data for the run.
     *
     * @var \PHP_CodeSniffer\Config
     */
    protected $config = null;

    /**
     * A list of ignore patterns that apply to directories only.
     *
     * @var array
     */
    protected $ignoreDirPatterns = null;

    /**
     * A list of ignore patterns that apply to files only.
     *
     * @var array
     */
    protected $ignoreFilePatterns = null;

    /**
     * TRUE if the basedir is actually a directory.
     *
     * @var boolean
     */
    protected $isBasedirDir = false;

    /**
     * The ruleset used for the run.
     *
     * @var \PHP_CodeSniffer\Ruleset
     */
    protected $ruleset = null;


    /**
     * Constructs a filter.
     *
     * @param \RecursiveIterator       $iterator The iterator we are using to get file paths.
     * @param string                   $basedir  The top-level path we are filtering.
     * @param \PHP_CodeSniffer\Config  $config   The config data for the run.
     * @param \PHP_CodeSniffer\Ruleset $ruleset  The ruleset used for the run.
     *
     * @return void
     */
    public function __construct($iterator, $basedir, Config $config, Ruleset $ruleset)
    {
        parent::__construct($iterator);
        $this->basedir = $basedir;
        $this->config  = $config;
        $this->ruleset = $ruleset;

        if (is_dir($basedir) === true || Common::isPharFile($basedir) === true) {
            $this->isBasedirDir = true;
        }

    }//end __construct()


    /**
     * Check whether the current element of the iterator is acceptable.
     *
     * Files are checked for allowed extensions and ignore patterns.
     * Directories are checked for ignore patterns only.
     *
     * @return bool
     */
    #[ReturnTypeWillChange]
    public function accept()
    {
        $filePath = $this->current();
        $realPath = Common::realpath($filePath);

        if ($realPath !== false) {
            // It's a real path somewhere, so record it
            // to check for circular symlinks.
            if (isset($this->acceptedPaths[$realPath]) === true) {
                // We've been here before.
                return false;
            }
        }

        $filePath = $this->current();
        if (is_dir($filePath) === true) {
            if ($this->config->local === true) {
                return false;
            }
        } else if ($this->shouldProcessFile($filePath) === false) {
            return false;
        }

        if ($this->shouldIgnorePath($filePath) === true) {
            return false;
        }

        $this->acceptedPaths[$realPath] = true;
        return true;

    }//end accept()


    /**
     * Returns an iterator for the current entry.
     *
     * Ensures that the ignore patterns are preserved so they don't have
     * to be generated each time.
     *
     * @return \RecursiveIterator
     */
    #[ReturnTypeWillChange]
    public function getChildren()
    {
        $filterClass = get_called_class();
        $children    = new $filterClass(
            new RecursiveDirectoryIterator($this->current(), (RecursiveDirectoryIterator::SKIP_DOTS | FilesystemIterator::FOLLOW_SYMLINKS)),
            $this->basedir,
            $this->config,
            $this->ruleset
        );

        // Set the ignore patterns so we don't have to generate them again.
        $children->ignoreDirPatterns  = $this->ignoreDirPatterns;
        $children->ignoreFilePatterns = $this->ignoreFilePatterns;
        $children->acceptedPaths      = $this->acceptedPaths;
        return $children;

    }//end getChildren()


    /**
     * Checks filtering rules to see if a file should be checked.
     *
     * Checks both file extension filters and path ignore filters.
     *
     * @param string $path The path to the file being checked.
     *
     * @return bool
     */
    protected function shouldProcessFile($path)
    {
        // Check that the file's extension is one we are checking.
        // We are strict about checking the extension and we don't
        // let files through with no extension or that start with a dot.
        $fileName  = basename($path);
        $fileParts = explode('.', $fileName);
        if ($fileParts[0] === $fileName || $fileParts[0] === '') {
            if ($this->isBasedirDir === true) {
                // We are recursing a directory, so ignore any
                // files with no extension.
                return false;
            }

            // We are processing a single file, so always
            // accept files with no extension as they have been
            // explicitly requested and there is no config setting
            // to ignore them.
            return true;
        }

        // Checking multi-part file extensions, so need to create a
        // complete extension list and make sure one is allowed.
        $extensions = [];
        array_shift($fileParts);
        foreach ($fileParts as $part) {
            $extensions[] = implode('.', $fileParts);
            array_shift($fileParts);
        }

        $matches = array_intersect($extensions, $this->config->extensions);
        if (empty($matches) === true) {
            return false;
        }

        return true;

    }//end shouldProcessFile()


    /**
     * Checks filtering rules to see if a path should be ignored.
     *
     * @param string $path The path to the file or directory being checked.
     *
     * @return bool
     */
    protected function shouldIgnorePath($path)
    {
        if ($this->ignoreFilePatterns === null) {
            $this->ignoreDirPatterns  = [];
            $this->ignoreFilePatterns = [];

            $ignorePatterns        = $this->config->ignored;
            $rulesetIgnorePatterns = $this->ruleset->getIgnorePatterns();
            foreach ($rulesetIgnorePatterns as $pattern => $type) {
                // Ignore standard/sniff specific exclude rules.
                if (is_array($type) === true) {
                    continue;
                }

                $ignorePatterns[$pattern] = $type;
            }

            foreach ($ignorePatterns as $pattern => $type) {
                // If the ignore pattern ends with /* then it is ignoring an entire directory.
                if (substr($pattern, -2) === '/*') {
                    // Need to check this pattern for dirs as well as individual file paths.
                    $this->ignoreFilePatterns[$pattern] = $type;

                    $pattern = substr($pattern, 0, -2).'(?=/|$)';
                    $this->ignoreDirPatterns[$pattern] = $type;
                } else {
                    // This is a file-specific pattern, so only need to check this
                    // for individual file paths.
                    $this->ignoreFilePatterns[$pattern] = $type;
                }
            }
        }//end if

        $relativePath = $path;
        if (strpos($path, $this->basedir) === 0) {
            // The +1 cuts off the directory separator as well.
            $relativePath = substr($path, (strlen($this->basedir) + 1));
        }

        if (is_dir($path) === true) {
            $ignorePatterns = $this->ignoreDirPatterns;
        } else {
            $ignorePatterns = $this->ignoreFilePatterns;
        }

        foreach ($ignorePatterns as $pattern => $type) {
            // Maintains backwards compatibility in case the ignore pattern does
            // not have a relative/absolute value.
            if (is_int($pattern) === true) {
                $pattern = $type;
                $type    = 'absolute';
            }

            $replacements = [
                '\\,' => ',',
                '*'   => '.*',
            ];

            // We assume a / directory separator, as do the exclude rules
            // most developers write, so we need a special case for any system
            // that is different.
            if (DIRECTORY_SEPARATOR === '\\') {
                $replacements['/'] = '\\\\';
            }

            $pattern = strtr($pattern, $replacements);

            if ($type === 'relative') {
                $testPath = $relativePath;
            } else {
                $testPath = $path;
            }

            $pattern = '`'.$pattern.'`i';
            if (preg_match($pattern, $testPath) === 1) {
                return true;
            }
        }//end foreach

        return false;

    }//end shouldIgnorePath()


}//end class
