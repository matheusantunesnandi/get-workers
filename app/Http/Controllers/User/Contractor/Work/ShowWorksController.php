<?php

namespace App\Http\Controllers\User\Contractor\Work;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\Request;
use Inertia\Response;

class ShowWorksController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function __invoke(string $workUuid, Request $request): Response
    {
        $work = Work::with([
                'specialties',
                'unity',
                'worker.user',
                'specialties',
        ])
            ->where('uuid', $workUuid)
            ->firstOrFail();

        return inertia('User/Contractor/Works/Show', compact('work'));
    }
}
