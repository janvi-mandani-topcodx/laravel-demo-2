<?php
namespace App\Repositories;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
abstract class BaseRepository
{
    protected $model;
    protected $app;
    public function __construct(Application $app)
    {
        $this->app = $app;
//        $this->makeModel();
    }
}





