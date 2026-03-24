<?php

namespace App\View\Composers;

use App\Models\Service;
use Illuminate\View\View;

class LeadFormComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Only target the leads resource
        $resource = $view->offsetGet('resource');
        
        if ($resource === 'leads') {
            $options = $view->offsetGet('options');
            
            // Override the service_id options with unique service names
            if (isset($options['service_id'])) {
                $options['service_id'] = Service::all()->unique('name');
                $view->with('options', $options);
            }
        }
    }
}
