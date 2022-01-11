<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use App\Helpers\FunctionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCommentRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Ticket;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentsController extends Controller
{
    public function index()
    {
        return view('admin.comments.index');
    }

    public function create()
    {
        abort_if(Gate::denies('comment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tickets = Ticket::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.comments.create', compact('tickets', 'users'));
    }

    public function store(StoreCommentRequest $request)
    {
        $comment = Comment::create($request->all());

        foreach ($request->input('attachments', []) as $file) {
            $comment->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
        }

        return redirect()->route('admin.comments.index');
    }

    public function edit(Comment $comment)
    {
        abort_if(Gate::denies('comment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tickets = Ticket::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment->load('ticket', 'user');

        return view('admin.comments.edit', compact('tickets', 'users', 'comment'));
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->all());

        if (count($comment->attachments) > 0) {
            foreach ($comment->attachments as $media) {
                if (!in_array($media->file_name, $request->input('attachments', []))) {
                    $media->delete();
                }
            }
        }

        $media = $comment->attachments->pluck('file_name')->toArray();

        foreach ($request->input('attachments', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $comment->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
            }
        }

        return redirect()->route('admin.comments.index');
    }

    public function show(Comment $comment)
    {
        abort_if(Gate::denies('comment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $comment->load('ticket', 'user');

        return view('admin.comments.show', compact('comment'));
    }

    public function destroy(Comment $comment)
    {
        abort_if(Gate::denies('comment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $comment->delete();

        return back();
    }

    public function massDestroy(MassDestroyCommentRequest $request)
    {
        Comment::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function data(Request $request)
    {
        $query = Comment::with('ticket')
                        ->when($request->has('filter'), function ($q) use ($request) {
                            return FunctionHelper::dxFilterGenerator($q, $request->filter);
                        })
                        ->when($request->has('sort'), function ($q) use ($request) {
                            $sort = eval('return json_decode($request->sort)[0];');
                            $selector = FunctionHelper::dxGetTableColumnFilter($sort->selector);
                            switch ($selector[0]) {
                                    case 'ticket':
                                        return $q->join('tickets', 'tickets.id', '=', 'comments.ticket_id')
                                                ->orderBy('tickets.'.$selector[1], $sort->desc ? 'desc' : 'asc');
                                    break;
                                default:
                                        return $q->orderBy('comments.'.$selector, $sort->desc ? 'desc' : 'asc');
                                    break;
                            }
                        })
                        ->when(!$request->has('sort'), function ($q) use ($request) {
                            return $q->orderBy('updated_at', 'desc');
                        });

        $comments = $query->limit($request->take)->offset($request->skip)->get();
        return [
            'data' => $comments,
            'totalCount' => $query->count()
        ];
    }
}
