<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\WordServices\DatabaseGenerateWord;
use App\Library\WordServices\WikipediaGenerateWord;
use App\Library\ServiceGenerateWord;

class GenerateWord extends ServiceProvider {

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register() {


        if (env('WORD_PROVIDER') && env('WORD_PROVIDER') != '') {
            $setting = env('WORD_PROVIDER');
        } else {
            $setting = 'Database';
        }

        $this->app->bind('App\Library\ServiceGenerateWord', 'App\Library\WordServices\\' . $setting . 'GenerateWord');
    }

}
