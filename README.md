# Buster

NGINX cache busting server web app and API.

## Why is this useful?

Some organizations utilize more than one layer of caching to provide a speedy end user experience. Purging multiple proxy caches with possible script caching upstream can pose a serious headache. This is where Buster comes in. 

Buster can send purge requests to an upstream app server and multiple reverse proxy servers. Resulting in an environment wide cache bust. If configured correctly, Buster will only purge targeted pages from each cache. 

## Does Buster work with other web servers?

Short answer: Probably, but at this time I'm targeting this project in the direction of NGINX.

I've not tested this with other web servers like Apache or HAProxy, because I use NGINX in my environment. Buster assumes, your NGINX servers will be using [this module](https://github.com/nginx-modules/ngx_cache_purge) to purge your caches. That being said, if there is a way to purge caches on those web servers via a GET request or a custom PURGE request, I suppose it is possible.

## Todo

* Documentation

## Related Projects

Some cool projects that this software relies on.

* [Laravel](https://laravel.com/)
* [nginx-modules/ngx_cache_purge](https://github.com/nginx-modules/ngx_cache_purge)
* [guzzle/guzzle](https://github.com/guzzle/guzzle)
* [chrisbjr/api-guard](https://github.com/chrisbjr/api-guard)
* [jeremykenedy/laravel-users](https://github.com/jeremykenedy/laravel-users)
* [MelonSmasher/Buster-Client](https://github.com/MelonSmasher/Buster-Client)
* [MelonSmasher/Buster-WP](https://github.com/MelonSmasher/Buster-WP)
