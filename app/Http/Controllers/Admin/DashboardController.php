<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Support\LaunchChecklist;
use App\Models\BlogPost;
use App\Models\Inquiry;
use App\Models\Project;
use App\Models\Service;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(LaunchChecklist $checklist): View
    {
        $stats = [
            ['label' => 'Published services', 'value' => Service::published()->count(), 'route' => 'admin.services.index'],
            ['label' => 'Published articles', 'value' => BlogPost::live()->count(), 'route' => 'admin.blog-posts.index'],
            ['label' => 'Portfolio projects', 'value' => Project::count(), 'route' => 'admin.projects.index'],
            ['label' => 'New leads', 'value' => Inquiry::where('status', 'new')->count(), 'route' => 'admin.inquiries.index', 'query' => ['status' => 'new']],
            ['label' => 'Consultations pending', 'value' => Inquiry::where('type', 'consultation')->whereIn('status', ['new', 'contacted'])->count(),
                'route' => 'admin.inquiries.index', 'query' => ['type' => 'consultation']],
        ];

        $leads = Inquiry::with('service:id,name')->latest()->limit(8)->get();
        $checks = $checklist->items();

        return view('admin.dashboard', compact('stats', 'leads', 'checks'));
    }
}
