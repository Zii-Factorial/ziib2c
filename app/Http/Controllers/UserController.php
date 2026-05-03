<?php

namespace App\Http\Controllers;

use App\Enums\Module;
use App\Repositories\UserRepository;

class UserController extends AbstractController
{
    public function __construct(
        protected readonly UserRepository $repository,
        protected Module $module = Module::USER,
    ) {
        parent::__construct();
    }
}
