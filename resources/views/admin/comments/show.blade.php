@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.comment.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.comment.fields.id') }}
                        </th>
                        <td>
                            {{ $comment->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.comment.fields.ticket') }}
                        </th>
                        <td>
                            {{ $comment->ticket->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.comment.fields.author_name') }}
                        </th>
                        <td>
                            {{ $comment->author_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.comment.fields.author_email') }}
                        </th>
                        <td id="author-email">
                            {{ $comment->author_email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.comment.fields.user') }}
                        </th>
                        <td>
                            {{ $comment->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.comment.fields.comment_text') }}
                        </th>
                        <td id="comment-text">
                            {!! $comment->comment_text !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Attachments
                        </th>
                        <td>
                            <div id="commentAttachments" class="col-12 row">

                                {{-- ul id is important for JS (bottom of file) --}}
                                <ul id="commentImageAttachments" class="list-unstyled row mx-0">

                                    {{-- Loop for ticket image attachment --}}
                                    {{-- FunctionHelper::getImagesAttachment to get only image attachments, with additional width and height attributes --}}
                                    @foreach(FunctionHelper::getImagesAttachment($comment->attachments) as $attachment)

                                        {{-- data-* attribute is important for JS (bottom of file) --}}
                                        <li class="col-auto att-comment" data-name="{{ $attachment->file_name }}" data-src="{{ $attachment->getUrl() }}" data-w="{{ $attachment->custom_properties['width'] }}" data-h="{{ $attachment->custom_properties['height'] }}">

                                            {{-- showTicketImageAttachments is function for show image modal --}}
                                            <a onclick="showCommentImageAttachments({{ $loop->index }})" href="#&gid=1&pid={{ $attachment->file_name }}">
                                                <img class="img-responsive" src="{{ $attachment->getUrl('thumb') }}" height="70" width="70">
                                                <small class="d-block">{{ FunctionHelper::substrMiddle($attachment->file_name) }}</small>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                <ul class="list-unstyled row mx-0">

                                    {{-- Loop for comment (exclude image) attachment --}}
                                    @foreach(FunctionHelper::getImagesAttachment($comment->attachments, true) as $attachment)
                                        <li class="col-auto">
                                            <a href="{{ $attachment->getUrl() }}" download>
                                            @if (in_array($attachment->custom_properties['extension'], FunctionHelper::WORDS_EXT))
                                                <img class="img-responsive" src="{{ asset('images/word.png') }}" height="70" width="70">
                                            @elseif (in_array($attachment->custom_properties['extension'], FunctionHelper::EXCELS_EXT))
                                                <img class="img-responsive" src="{{ asset('images/excel.png') }}" height="70" width="70">
                                            @elseif (in_array($attachment->custom_properties['extension'], FunctionHelper::PDF_EXT))
                                                <img class="img-responsive" src="{{ asset('images/pdf.png') }}" height="70" width="70">
                                            @elseif (in_array($attachment->custom_properties['extension'], FunctionHelper::COMPRESSES_EXT))
                                                <img class="img-responsive" src="{{ asset('images/zip.png') }}" height="70" width="70">
                                            @else
                                                <img class="img-responsive" src="{{ asset('images/paper.png') }}" height="70" width="70">
                                            @endif
                                                <small class="d-block">{{ FunctionHelper::substrMiddle($attachment->file_name) }}</small>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>


    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/linkify.js') }}"></script>
    <script src="{{ asset('js/linkify-html.js') }}"></script>
    <script>
        $(document).ready(() => {
            linkifyInit();
        });

        function linkifyInit() {
            const authorEmail = document.getElementById('author-email');
            const commentText = document.getElementById('comment-text');
            const options = {
                rel: 'nofollow noreferrer noopener'
            }
            authorEmail.innerHTML = linkifyHtml(authorEmail.innerHTML, options);
            commentText.innerHTML = linkifyHtml(commentText.innerHTML, options);
        }

        /**
         * Show comment image attachment when click image
         */
         function showCommentImageAttachments(index){
            let items = $('#commentAttachments .att-comment').map((i, image) => {
                image = $(image).data();
                return {src: image.src, w: image.w, h: image.h, pid: image.name};
            });

            let options = {
                history: false,
                focus: false,
                showAnimationDuration: 0,
                hideAnimationDuration: 0,
                index: index,
                galleryUID: 2,
                galleryPIDs: true,
                getDoubleTapZoom: function(isMouseClick, item) {
                    if(isMouseClick) {
                        return 1;
                    } else {
                        return item.initialZoomLevel < 0.7 ? 1 : 1.5;
                    }
                },
            };

            pswpGalery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
            pswpGalery.init();
        }
    </script>
@endsection
