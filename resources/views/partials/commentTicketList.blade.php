@forelse ($comments as $key => $comment)
    <div class="row mb-3">
        @if (auth()->id() == $comment->user->id)
            <div class="col"></div>
        @endif
        <div class="col-auto shadow-sm border py-3 px-5 @if (auth()->id() == $comment->user->id) text-right comment-right @else comment-left @endif">
            <p class="font-weight-bold">
                <a href="mailto:{{ $comment->author_email }}">{{ $comment->author_name }}</a> ({{ $comment->created_at }})
            </p>
            <p>{{ $comment->comment_text }}</p>
            <div class="row @if (auth()->id() == $comment->user->id) justify-content-end @endif">
                <div id="att-comment-{{$key}}" class="row mx-0">
                    @foreach($comment->attachments as $attachment)
                        @php
                            $file_ext = pathinfo($attachment->geturl(), PATHINFO_EXTENSION);
                        @endphp
                        @if (in_array($file_ext, FunctionHelper::IMAGES_EXT))
                        <div class="col-auto mb-3 text-center" data-src="{{ $attachment->geturl() }}" data-sub-html="{{ $attachment->file_name }}">
                            <a href="" target="_blank" title="{{ $attachment->file_name }}">
                                <img src="{{ $attachment->geturl('thumb') }}" alt="thumbnail" width="50" height="50">
                                <small class="d-block">{{ FunctionHelper::substrMiddle($attachment->file_name) }}</small>
                            </a>
                        </div>
                        @endif
                    @endforeach
                </div>

                <div class="row mx-0">
                @foreach($comment->attachments as $attachment)
                    @php
                        $file_ext = pathinfo($attachment->geturl(), PATHINFO_EXTENSION);
                    @endphp
                    @if (!in_array($file_ext, FunctionHelper::IMAGES_EXT))
                    <div class="col-auto mb-3 text-center">
                        @if (in_array($file_ext, FunctionHelper::WORDS_EXT))
                            <a href="{{ $attachment->geturl() }}" title="{{ $attachment->file_name }}" download>
                                <img src="{{ asset('images/word.png') }}" alt="thumbnail" width="50">
                                <small class="d-block">{{ FunctionHelper::substrMiddle($attachment->file_name) }}</small>
                            </a>
                        @elseif (in_array($file_ext, FunctionHelper::EXCELS_EXT))
                            <a href="{{ $attachment->geturl() }}" title="{{ $attachment->file_name }}" download>
                                <img src="{{ asset('images/excel.png') }}" alt="thumbnail" width="50">
                                <small class="d-block">{{ FunctionHelper::substrMiddle($attachment->file_name) }}</small>
                            </a>
                        @elseif (in_array($file_ext, FunctionHelper::PDF_EXT))
                            <a href="{{ $attachment->geturl() }}" title="{{ $attachment->file_name }}" download>
                                <img src="{{ asset('images/pdf.png') }}" alt="thumbnail" width="50">
                                <small class="d-block">{{ FunctionHelper::substrMiddle($attachment->file_name) }}</small>
                            </a>
                        @elseif (in_array($file_ext, FunctionHelper::COMPRESSES_EXT))
                            <a href="{{ $attachment->geturl() }}" title="{{ $attachment->file_name }}" download>
                                <img src="{{ asset('images/zip.png') }}" alt="thumbnail" width="50">
                                <small class="d-block">{{ FunctionHelper::substrMiddle($attachment->file_name) }}</small>
                            </a>
                        @else
                            <a href="{{ $attachment->geturl() }}" target="_blank" title="{{ $attachment->file_name }}">
                                <img src="{{ asset('images/paper.png') }}" alt="thumbnail" width="50">
                                <small class="d-block">{{ FunctionHelper::substrMiddle($attachment->file_name) }}</small>
                            </a>
                        @endif
                    </div>
                    @endif
                @endforeach
                </div>
            </div>
        </div>
        @if (auth()->id() != $comment->user->id)
            <div class="col"></div>
        @endif
    </div>
@empty
    <p>Tidak ada balasan</p>
@endforelse
