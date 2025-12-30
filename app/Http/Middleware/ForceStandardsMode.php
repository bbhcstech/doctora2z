<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceStandardsMode
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Only fix HTML responses
        if (str_contains($response->headers->get('Content-Type'), 'text/html')) {
            $content = $response->getContent();
            
            // Check if doctype exists
            if (!preg_match('/^<!DOCTYPE html>/i', ltrim($content))) {
                // Add doctype at the beginning
                $content = '<!DOCTYPE html>' . PHP_EOL . $content;
                
                // Also add X-UA-Compatible header
                $response->headers->set('X-UA-Compatible', 'IE=edge');
                
                return $response->setContent($content);
            }
        }
        
        return $response;
    }
}