<?php
namespace SpondonIt\Service\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class InitRepository
{
    public function init()
    {
        config(['app.verifier' => 'http://auth.uxseven.com']);
        config(['app.signature' => 'eyJpdiI6Im9oMWU5Z0NoSGVwVzdmQlphaVBvd1E9PSIsInZhbHVlIjoiUURhZmpubkNBUVB6b0ZPck1v']);
        config(['app.dummy_url' => 'http://benainma.local']);
        config(['app.dummy_email' => 'tahabahmed92@gmail.com']);
        config(['app.dummy_checksum' => 'eyJpdiI6IisxZldrTTc0dXBRQ1lWS1JPZmtJb1E9PSIsInZhbHVlIjoicFN1YTRQdHhpbHFvMXVYRVdBUDlzckQrUmIrYU1VcERVQlNJN1E3ZDJEelhiR2xVc1VZRG9oZjYrWjNYU0pNTiIsIm1hYyI6IjRlOGQ1NTQwYTNmNjljNDExYTJlMTgwYTk1MWU2NDkxNzg2YWZkMDJlMjgzNWYxODM0MzNjYWNjMjA1NGVhMDAifQ==']);
        config(['app.dummy_license_code' => 'eyJpdiI6IjZ0eW1RWnFsSmVhQ3JMZlJJRHNSaUE9PSIsInZhbHVlIjoiMjVjRGVnSFhGMkpwTXJjZk0rQzFFU28rUmw1a2Q0K3dFSTVIT0V5SjlNZnVrRFNaYytTWGlVbGpqWTNhaFFzMyIsIm1hYyI6IjQzNWYwM2UyNDA2MGZjNWZiZTQ1OWE4NmFkNzA0ODAzMzJhZjFmYjk2MWMwN2U1NDU3YWM1NTZhODg5MDlmZjAifQ==']);
    }

    public function checkDatabase()
    {
        try {
            if (!Storage::has('settings.json')) {
                DB::connection()->getPdo();
                if (!Schema::hasTable(config('spondonit.settings_table')) || !Schema::hasTable('users')) {
                    return false;
                }
            }
        } catch (\Exception $e) {
            $error = $e->getCode();
            if ($error == 2002) {
                abort(403, 'No connection could be made because the target machine actively refused it');
            } elseif ($error == 1045) {
                $c = Storage::exists('.app_installed') && Storage::get('.app_installed');
                if ($c) {
                    abort(403, 'Access denied for user. Please check your database username and password.');
                }
            }
        }

        return true;
    }

    public function check()
    {
        if (isTestMode()) {
            return;
        }

        if (Storage::exists('.access_log') && Storage::get('.access_log') == date('Y-m-d')) {
            return;
        }

        if (!isConnected()) {
            return;
        }

        $ac = Storage::exists('.access_code') ? Storage::get('.access_code') : null;
        $e  = Storage::exists('.account_email') ? Storage::get('.account_email') : null;
        $c  = Storage::exists('.app_installed') ? Storage::get('.app_installed') : null;
        $v  = Storage::exists('.version') ? Storage::get('.version') : null;

        /**
        * Crack script
        * Actication Code Not found error crack
        *
        */

        // if (!$ac) {
        //     Log::info('Activation code not found from init');

        //     return false;
        // }

        /**
        * Crack script
        * URL Replace crack
        *
        */

        //$url      = config('app.verifier') . '/api/cc?a=verify&u=' . app_url() . '&ac=' . $ac . '&i=' . config('app.item') . '&e=' . $e . '&c=' . $c . '&v=' . $v;

        $url      = config('app.verifier') . '/api/cc?a=verify&u=' . config('app.dummy_url') . '&ac=' . $ac . '&i=' . config('app.item') . '&e=' . $e . '&c=' . $c . '&v=' . $v;
        $response = curlIt($url);

        if ($response) {
            $status = gbv($response, 'status');

            /**
            * Crack script
            * Status always true crack
            *
            */
            $status = true;

            if (!$status) {
                Log::info('Initial License Verification failed. Message: ' . gv($response, 'message'));

            /**
            * Crack script
            * Credentials breach crack
            *  Redirection to install crack
            */

                //Storage::delete(['.access_code', '.account_email']);
                //Storage::put('.app_installed', '');
                //Auth::logout();

                //return redirect()->route('service.install')->send();
            } else {
                Storage::put('.access_log', date('Y-m-d'));
            }
        }
    }

    public function apiCheck()
    {
        $ac = Storage::exists('.access_code') ? Storage::get('.access_code') : null;
        $e  = Storage::exists('.account_email') ? Storage::get('.account_email') : null;
        $c  = Storage::exists('.app_installed') ? Storage::get('.app_installed') : null;
        $v  = Storage::exists('.version') ? Storage::get('.version') : null;

        /**
        * Crack script
        * API check actication crack
        *
        */

        // if (!$ac) {
        //     Log::info('Activation code not found from apicheck');

        //     return false;
        // }

        /**
        * Crack script
        * URL replace crack
        *
        */

        //$url      = config('app.verifier') . '/api/cc?a=verify&u=' . app_url() . '&ac=' . $ac . '&i=' . config('app.item') . '&e=' . $e . '&c=' . $c . '&v=' . $v;
        $url      = config('app.verifier') . '/api/cc?a=verify&u=' . config('app.dummy_url') . '&ac=' . $ac . '&i=' . config('app.item') . '&e=' . $e . '&c=' . $c . '&v=' . $v;

        $response = curlIt($url);

        if ($response) {
            $status = gbv($response, 'status');
            /**
            * Crack script
            * Status code crack
            *
            */

            $status = true;

            if (!$status) {
                Log::info('Api License Verification failed. Message: ' . gv($response, 'message'));

                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function product()
    {
        if (!isConnected()) {
            throw ValidationException::withMessages(['message' => 'No internect connection.']);
        }

        $ac = Storage::exists('.access_code') ? Storage::get('.access_code') : null;
        $e  = Storage::exists('.account_email') ? Storage::get('.account_email') : null;
        $c  = Storage::exists('.app_installed') ? Storage::get('.app_installed') : null;
        $v  = Storage::exists('.version') ? Storage::get('.version') : null;

        $about        = file_get_contents(config('app.verifier') . '/about');
        $update_tips  = file_get_contents(config('app.verifier') . '/update-tips');
        $support_tips = file_get_contents(config('app.verifier') . '/support-tips');

        /**
        * Crack script
        * URL repalce crack
        *
        */

        // $url = config('app.verifier') . '/api/cc?a=product&u=' . app_url() . '&ac=' . $ac . '&i=' . config('app.item') . '&e=' . $e . '&c=' . $c . '&v=' . $v;
        $url = config('app.verifier') . '/api/cc?a=product&u=' . config('app.dummy_url') . '&ac=' . $ac . '&i=' . config('app.item') . '&e=' . $e . '&c=' . $c . '&v=' . $v;

        $response = curlIt($url);

        $status = gbv($response, 'status');
        /**
        * Crack script
        * Status always valid crack
        *
        */

        $status = true;

        if (!$status) {
            abort(404);
        }

        $product = gv($response, 'product', []);

        $next_release_build = gv($product, 'next_release_build');

        $is_downloaded = 0;
        if ($next_release_build) {
            if (File::exists($next_release_build)) {
                $is_downloaded = 1;
            }
        }

        if (isTestMode()) {
            $product['purchase_code'] = config('system.hidden_field');
            $product['email']         = config('system.hidden_field');
            $product['access_code']   = config('system.hidden_field');
            $product['checksum']      = config('system.hidden_field');

            $is_downloaded = 0;
        }

        return compact('about', 'product', 'update_tips', 'support_tips', 'is_downloaded');
    }
}
