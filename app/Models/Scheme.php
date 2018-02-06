<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scheme extends Model
{
    use SoftDeletes;

    public $proxies = [];

    public $proxy_uri = null;

    public $server_uri = null;

    public $results = [];

    public $server_urls = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'server_id',
        'server_uri_id',
        'proxy_ids',
        'proxy_uri_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * @return $this
     */
    public function formScheme()
    {
        // Get the URIs from their IDs
        if (!empty($this->server_uri_id)) $this->server_uri = Uri::findOrFail($this->server_uri_id);
        if (!empty($this->proxy_uri_id)) $this->proxy_uri = Uri::findOrFail($this->proxy_uri_id);
        // Loop over each Proxy ID
        if (!empty($this->proxy_ids)) {
            foreach (explode(',', $this->proxy_ids) as $pid) {
                // Add that Proxy to the proxies array
                $this->proxies[] = Proxy::findOrFail($pid);
            }
        }
        return $this;
    }

    /**
     * @param $ip
     * @param $hostname
     * @param $path
     * @param $port
     * @param $usesHTTPS
     * @return object
     */
    private function formUrl($ip, $hostname, $path, $port, $usesHTTPS, $id)
    {
        $port = intval($port);
        $usesHTTPS = boolval($usesHTTPS);
        $scheme = $usesHTTPS ? 'https://' : 'http://';
        $url = $scheme;
        $url = $url . $hostname;
        $url = $url . ':' . $port;
        $url = $url . $path;

        return (object)['url' => $url, 'masquerade' => "$hostname:$port:$ip", 'id' => $id];
    }

    /**
     * @return array
     */
    public function formUrls()
    {
        $urls = [$this->formUrl($this->server->ip, $this->server->hostname, $this->server_uri->path, $this->server->port, $this->server->uses_https, $this->server->id)];
        foreach ($this->proxies as $proxy) {
            $urls[] = $this->formUrl($proxy->ip, $this->server->hostname, $this->proxy_uri->path, $this->server->port, $this->server->uses_https, $proxy->id);
        }
        return $urls;
    }
}
