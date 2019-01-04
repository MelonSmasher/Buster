<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client;
use App\Models\Scheme;
use App\Models\Purge;

class BustPath implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $PurgeData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $PurgeData)
    {
        $this->PurgeData = $PurgeData;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {

        $data = $this->PurgeData;

        $scheme = Scheme::findOrFail($data['scheme_id'])->formScheme();
        $scheme->server_urls = $scheme->formUrls();

        $http_scheme = ($scheme->server->use_https) ? 'https' : 'http';
        $path = $data['path'];
        $method = (empty($data['method'])) ? 'GET' : $data['method'];

        $result = (object)[];
        $result->input_data = $data;
        $result->buster_scheme = $scheme;
        $result->actions = [];
        $resolvePrefix = [];

        foreach ($scheme->server_urls as $action) {
            $resolvePrefix[] = '-' . $action->masquerade;
        }

        foreach ($scheme->server_urls as $action) {
            $client = new Client();
            $action->buster_key = $http_scheme . $method . $scheme->server->hostname . $path;
            $resolveParam = $resolvePrefix;
            $resolveParam[] = $action->masquerade;
            $response = $client->request(
                $method,
                $action->url . $path,
                [
                    'connect_timeout' => 10.00,
                    'debug' => false,
                    'http_errors' => false,
                    'headers' =>
                        [
                            'Host' => $scheme->server->hostname,
                            'x-buster-mode' => 'live',
                            'x-buster-key' => $action->buster_key
                        ],
                    'curl' => [
                        CURLOPT_DNS_CACHE_TIMEOUT => 0,
                        CURLOPT_RESOLVE => $resolveParam
                    ]
                ]
            );
            $action->response = (object)[];
            $action->response->code = $response->getStatusCode();
            $action->response->headers = $response->getHeaders();
            $action->response->body = $response->getBody();
            $action->response->reason = $response->getReasonPhrase();
            $result->actions[] = $action;


            Purge::create([
                'scheme_id' => $scheme->id,
                'server_id' => $scheme->server->id,
                'url' => $action->masquerade,
                'path' => $path,
                'response_code' => $response->getStatusCode(),
                'reason' => $action->response->reason,
                'action' => json_encode($action)
            ]);

            unset($client);
        }
    }
}
