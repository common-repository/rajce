<?php

namespace WPFW\Caching\Storages;

use WPFW;
use WPFW\Caching;
use WPFW\Caching\Cache;

/**
 * Cache file storage.
 */
class FileStorage implements WPFW\Caching\IStorage
{

    /** @internal cache file structure */
    const META_HEADER_LEN = 23, // 27b signature + 6b meta-struct size + serialized meta-struct + data
        META_TIME = 'time',
        META_EXPIRE = 'expire'; // expiration timestamp

    /** additional cache structure */
    const FILE = 'file',
        HANDLE = 'handle';

    /** @var string */
    private $dir;

    public function __construct($dir)
    {
        if (!is_dir($dir)) {
            //throw new \Exception("Directory '$dir' not found.");
            mkdir($dir, 0700);
        }
        $this->dir = $dir;
    }

    /**
     * Read from cache.
     * @param  string key
     * @return mixed|NULL
     */
    public function read($key)
    {
        $meta = $this->readMeta($this->getCacheFile($key));
        if ($meta && $this->verify($meta)) {
            return $this->readData($meta); // calls fclose()
        } else {
            return NULL;
        }
    }

    /**
     * Writes item into the cache.
     * @param  string key
     * @param  mixed  data
     * @param  array  dependencies
     * @return void
     */
    public function write($key, $data, array $dp)
    {
        $meta = [
            self::META_TIME => time(),
        ];
        if (isset($dp[Cache::EXPIRATION])) {
            $meta[self::META_EXPIRE] = strtotime( $dp[Cache::EXPIRATION] );
        }

        $head = serialize($meta) . '?>';
        $head = '<?php //WPFWcache' . str_pad((string) strlen($head), 6, '0', STR_PAD_LEFT) . $head;
        $headLen = strlen($head);
        $dataLen = strlen($data);


        $cacheFile = $this->getCacheFile($key);
        if (!is_dir($dir = dirname($cacheFile))) {
            @mkdir($dir); // @ - directory may already exist
        }
        $handle = fopen($cacheFile, 'c+b');

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

        if (fwrite($handle, str_repeat("\x00", $headLen), $headLen) !== $headLen) {
            return 1;
        }
        if (fwrite($handle, $data, $dataLen) !== $dataLen) {
            return 2;
        }
        fseek($handle, 0);
        if (fwrite($handle, $head, $headLen) !== $headLen) {
            return 3;
        }
        flock($handle, LOCK_UN);
        fclose($handle);
        return;
    }


    /**
     * Removes item from the cache.
     * @param  string key
     * @return void
     */
    public function remove($key)
    {
        $this->delete($this->getCacheFile($key));
    }

    /**
     * Get file name
     * @param  string
     * @return string
     */
    private function getCacheFile($key)
    {
        $file = urlencode($key);
        return $this->dir . '/_' . $file;
    }

    /**
     * Reads cached data
     * @param  string  file path
     * @return array|NULL
     */
    protected function readMeta($file)
    {
        $handle = @fopen($file, 'r+b'); // @ - file may not exist
        if (!$handle) {
            return NULL;
        }

        $head = stream_get_contents($handle, self::META_HEADER_LEN);
        if ($head && strlen($head) === self::META_HEADER_LEN) {
            $size = (int) substr($head, -6);
            $meta = stream_get_contents($handle, $size, self::META_HEADER_LEN);
            $meta = unserialize($meta);
            $meta[self::FILE] = $file;
            $meta[self::HANDLE] = $handle;
            return $meta;
        }
        fclose($handle);
        return NULL;
    }

    /**
     * Verify cahe expiration
     * @param  array
     * @return bool
     */
    private function verify($meta)
    {
        //print_r($meta);
        //echo "time: " . date("d.m.Y H:i:s", $meta["time"]) . " = ".$meta["time"]."<br/>";
        //echo "expire: " . date("d.m.Y H:i:s", $meta[self::META_EXPIRE]) . "<br/>";

        if (!empty($meta[self::META_EXPIRE]) && $meta[self::META_EXPIRE] < time()) {
            //invalidate cache file
            $this->delete($meta);
            return FALSE;
        } else {
            return TRUE;
        }

    }


    /**
     * Reads cache data from disk and closes cache file handle
     * @param  array
     * @return mixed
     */
    protected function readData($meta)
    {
        $data = stream_get_contents($meta[self::HANDLE]);
        fclose($meta[self::HANDLE]);

        return $data;
    }

    /**
     * Deletes cahe file
     * @param  string
     * @return void
     */
    private static function delete($meta)
    {
        fclose($meta[self::HANDLE]);
        if (@unlink($meta[self::FILE])) { // @ - file may not already exist
            return;
        }

    }
}

?>