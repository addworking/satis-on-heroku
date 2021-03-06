<?php

namespace Matthimatiker\SatisOnHeroku;

/**
 * Represents a repository URL.
 */
class RepositoryUrl
{
    /**
     * The original URL.
     *
     * @var string
     */
    protected $url = null;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return parse_url($this->getNormalizedUrl(), PHP_URL_HOST);
    }

    /**
     * Returns the path of the URL.
     *
     * @return string
     */
    public function getPath()
    {
        return parse_url($this->getNormalizedUrl(), PHP_URL_PATH);
    }

    /**
     * Returns the segments of the path.
     *
     * @return string[]
     */
    public function getPathSegments()
    {
        return explode('/', ltrim($this->getPath(), '/'));
    }

    /**
     * @return string The original URL.
     */
    public function __toString()
    {
        return $this->url;
    }

    /**
     * Returns a normalized URL that can be handled by the native parse_url() function.
     *
     * @return string
     */
    private function getNormalizedUrl()
    {
        if (strpos($this->url, 'https?://') === 0) {
            return 'https://' . substr($this->url, strlen('https?://'));
        }
        if (preg_match('/^[a-zA-Z]+@([^:]+):([^\/]+)(\/[^\/]+)*$/', $this->url)) {
            // Short SSH Url. Add the protocol and a slash to the path.
            return 'ssh://' . implode(':/', explode(':', $this->url, 2));
        }
        return $this->url;
    }
}
