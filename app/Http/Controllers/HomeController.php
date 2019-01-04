<?php

namespace App\Http\Controllers;

use App\Models\Proxy;
use App\Models\Purge;
use App\Models\Scheme;
use App\Models\Server;
use App\Models\Uri;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

/**
 * Class HomeController
 * @package App\Http\Controllers
 * @todo Use Laravel validation for all input
 * @todo Add user rolls
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function root()
    {
        return redirect(route('home'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('home')->with(['purges' => Purge::orderBy('created_at', 'desc')->paginate(15)]);
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        return view('profile')->with(['apiKeys' => $user->apiKeys]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function servers()
    {
        return view('servers')->with(['servers' => Server::paginate(15)]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function proxies()
    {
        return view('proxies')->with(['proxies' => Proxy::paginate(15)]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uris()
    {
        return view('uris')->with(['uris' => Uri::paginate(15)]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function schemes()
    {
        $paginated = Scheme::paginate(15);
        $schemes = $paginated->map(function ($item, $key) {
            return $item->formScheme();
        });
        // Return the view
        return view('schemes')->with(['schemes' => $schemes, 'paginated' => $paginated]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function scheme($id)
    {
        return view('scheme')->with(['scheme' => Scheme::findOrFail($id)->formScheme()]);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function bust(Request $request)
    {
        $data = $request->only(['path', 'method', 'scheme_id']);

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

        if ($request->path() === 'bust') {
            return redirect(route('home'));
        } else {
            return json_encode($result);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return string
     */
    public function history(Request $request, $id)
    {
        return json_encode(Purge::where('scheme_id', $id)->orderBy('created_at', 'desc')->paginate(15));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newServer()
    {
        return view('new.server');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newProxy()
    {
        return view('new.proxy');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newUri()
    {
        return view('new.uri');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newScheme()
    {
        $data = [
            'servers' => Server::all(),
            'uris' => Uri::all(),
            'proxies' => Proxy::all()
        ];

        return view('new.scheme')->with($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createServer(Request $request)
    {
        $data = $request->all();
        $data['uses_https'] = ($data['https'] === 'on') ? true : false;

        Server::create($data);
        return redirect(route('servers'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createProxy(Request $request)
    {
        Proxy::create($request->all());
        return redirect(route('proxies'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createUri(Request $request)
    {
        Uri::create($request->input());
        return redirect(route('uris'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function createScheme(Request $request)
    {
        $data = $request->all();

        foreach ($data['proxy_ids'] as $k => $v) {
            if ($data['proxy_ids'][$k] === 0) unset($data['proxy_ids'][$k]);
        }

        // Join the array into a string or null it
        if (!empty($data['proxy_ids'])) {
            $data['proxy_ids'] = implode(',', $data['proxy_ids']);
        } else {
            $data['proxy_ids'] = null;
        }

        if ($data['server_uri_id'] === 0) $data['server_uri_id'] = null;
        if ($data['proxy_uri_id'] === 0) $data['proxy_uri_id'] = null;

        Scheme::create($data);

        return redirect(route('schemes'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteServer(Request $request, $id)
    {
        Server::findOrFail($id)->delete();
        return redirect(route('servers'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteProxy(Request $request, $id)
    {
        Proxy::findOrFail($id)->delete();
        return redirect(route('proxies'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteUri(Request $request, $id)
    {
        Uri::findOrFail($id)->delete();
        return redirect(route('uris'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteScheme(Request $request, $id)
    {
        Scheme::findOrFail($id)->delete();
        return redirect(route('schemes'));
    }

}
