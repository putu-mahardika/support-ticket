<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Project;
use App\Ticket;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'messages' => $validator->errors(),
                'data' => []
            ]);
        }


        $project = $request->user()->projects->first();
        $code = $this->getCode($project);
        $assign_pm = Project::find($project->id)->users()->where('is_pm',true)->first()->pivot->user_id ?? 0;
        $ticket = Ticket::create([
            'title' => $request->title,
            'code' => $code,
            'content' => $request->content,
            'author_name' => $request->user()->name,
            'author_email' => $request->user()->email,
            'status_id' => $request->status_id ?? 1,
            'priority_id' => $request->priority_id ?? 1,
            'category_id' => $request->category_id ?? 1,
            'assigned_to_user_id' => $request->assigned_to_user_id ?? $assign_pm,
            'project_id' => $project->id
        ]);
        $ticket->project()->associate($project);
        $ticket->save();

        foreach ($request->input('attachments', []) as $file) {
            $ticket->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
        }

        return response()->json([
            'success' => true,
            'message' => 'Tiket anda telah berhasil ditambahkan. Silahkan tunggu hingga mendapatkan balasan melalui email dari kami.',
            'data' => [
                'ticket' => $ticket
            ]
        ]);
    }

    /**
     * Generate New Ticket Code
     *
     * @param \App\Project $project Project
     * @return string
     **/
    private function getCode($project)
    {
        $lastCode = $project->tickets()
                            ->whereYear('created_at', now()->year)
                            ->latest()
                            ->first();
        $newNum = empty($lastCode) ? 1 : intval(explode('.', $lastCode->code)[2]) + 1;
        return $project->code . '.' . now()->format('my') . '.' . Str::padLeft($newNum, 4, '0');
    }
}
