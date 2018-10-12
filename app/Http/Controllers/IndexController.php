<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * Class IndexController.
 *
 * @author  Killian Hascoët <killianh@live.fr>
 * @author  Paul Thébaud <paul.thebaud29@gmail.com>
 */
class IndexController extends AbstractController
{
    /**
     * Get the index view.
     *
     * @return View The index view.
     */
    public function __invoke(): View
    {
        return view('welcome');
    }
}
