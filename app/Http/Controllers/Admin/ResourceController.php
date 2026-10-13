<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * Base for CRUD controllers: wires the model policy to the standard resource actions,
 * so individual controllers stay free of authorization boilerplate.
 */
abstract class ResourceController extends Controller implements HasMiddleware
{
    /** Fully-qualified model class the policy is resolved for. */
    protected static string $model;

    /** Route parameter name holding the bound model (e.g. "service"). */
    protected static string $parameter;

    public static function middleware(): array
    {
        $model = static::$model;
        $param = static::$parameter;

        return [
            new Middleware("can:viewAny,{$model}", only: ['index']),
            new Middleware("can:view,{$param}", only: ['show']),
            new Middleware("can:create,{$model}", only: ['create', 'store']),
            new Middleware("can:update,{$param}", only: ['edit', 'update']),
            new Middleware("can:delete,{$param}", only: ['destroy']),
        ];
    }
}
