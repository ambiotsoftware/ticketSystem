<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon; // Importar

class AppServiceProvider extends ServiceProvider
{
  
    public function register(): void
    {
        //
    }

    
    public function boot(): void
    {
       
        Carbon::setToStringFormat('d/m/Y'); 
    }
}
