<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        return response()
            ->view('sitemap', [
                'posts' => Post::published()->latest('updated_at')->get(),
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
