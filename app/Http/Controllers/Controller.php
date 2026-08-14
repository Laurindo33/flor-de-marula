<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;

abstract class Controller
{
    /**
     * Run a save/update action, turning unexpected failures (DB errors, file
     * storage errors, etc.) into a friendly redirect instead of a raw error
     * page. Validation failures still behave as usual.
     */
    protected function safely(\Closure $action, string $errorMessage = 'Ocorreu um erro ao guardar. Tente novamente.'): mixed
    {
        try {
            return $action();
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('admin_error', $errorMessage);
        }
    }
}
