<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\WordServices\DatabaseGenerateWord;
use App\Library\WordServices\WikipediaGenerateWord;
use App\Library\ServiceGenerateWord;

class GenerateWord extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
   
        $this->app->bind('App\Library\ServiceGenerateWord', 'App\Library\WordServices\\'.env('WORD_PROVIDER').'GenerateWord');
    
    }
}
