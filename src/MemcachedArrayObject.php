<?
/**
 * Memcached PHP Wrapper
 *
 * @date: 2011.1.26
 * @author: cybaek@naver.com, cybaek@gmail.com
 * @memo: Seed code from 'Jiri Kupiainen' (http://jirikupiainen.com/)
 *
 **/
class MemcachedArrayObject extends ArrayObject {
        var $connected = false;
        var $memcache = null;
        var $servers;
	var $timeoutSecond;

	function MemcachedArrayObject($servers = array("127.0.0.1:11211"), $timeoutSecond = 60) {
		$this->servers = $servers;
		$this->connect();
	}

        private function connect() {
                if (defined('DISABLE_CACHE')) {
                        return false;
                }

                $this->memcache =& new Memcache();

                foreach ($this->servers as $server) {
                        $parts = explode(':', $server);

                        $host = $parts[0];
                        $port = $parts[1];

                        if ($this->memcache->addServer($host, $port)) {
                                $this->connected = true;
                        }
                }

                return $this->connected;
        }

	public function offsetGet($i) {
		return $this->get($i);
	}

	public function offsetSet($i, $v) {
		$this->set($i, $v, $this->timeoutSecond);
	}

        function set($key, $var, $expires = 60) {
                if (defined('DISABLE_CACHE') || !$this->connected) {
                        return false;
                }

                if (!is_numeric($expires)) {
                        $expires = strtotime($expires);
                }
                if ($expires < 1) {
                        $expires = 1; // don't allow caching infinitely
                }

                return $this->memcache->set($key, $var, 0, time()+$expires);
        }

        /**
         * Get a value from cache
         */
        function get($key) {
                if (defined('DISABLE_CACHE') || !$this->connected) {
                        return false;
                }

                return $this->memcache->get($key);
        }

        /**
         * Remove value from cache
         */
        function delete($key) {
                if (defined('DISABLE_CACHE') || !$this->connected) {
                        return false;
                }

                return $this->memcache->delete($key);
        }
} 

?>
