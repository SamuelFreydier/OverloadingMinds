<?php
namespace Controller;
use App\Src\App;
abstract class ControllerBase
{

    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

}