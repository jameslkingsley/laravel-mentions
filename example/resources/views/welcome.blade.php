<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel Mentions</title>

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.1/css/bulma.min.css">
        <link rel="stylesheet" type="text/css" href="{{ mix('/css/app.css') }}">
    </head>

    <body>
        <div class="container">
            <h1 class="title has-text-centered">Laravel Mentions Example</h1>
            <div class="columns">
                <div class="column is-half is-offset-one-quarter">
                    <form method="post" action="{{ route('comments.store') }}">
                        <!-- This field is required, it stores the mention data -->
                        <input type="hidden" name="mentions" id="mentions">

                        <div class="field">
                            <div class="control">
                                <textarea class="hide" name="text" id="text"></textarea>
                                <div class="textarea has-mentions" contenteditable="true" for="#text"></div>
                            </div>
                        </div>

                        <button class="button is-primary is-pulled-right" type="submit">
                            Post Comment
                        </button>

                        <!-- CSRF field for Laravel -->
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>

            <div class="columns" style="margin-top: 4rem">
                <div class="column is-half is-offset-one-quarter">
                    <h2 class="subtitle has-text-centered">Users</h2>
                    <ul>
                        @foreach ($users as $user)
                            <li class="has-text-centered is-pulled-left" style="width:50%">{{ $user->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="columns" style="margin-top: 4rem">
                <div class="column is-half is-offset-one-quarter">
                    <h2 class="subtitle has-text-centered">Comments</h2>
                    <ul>
                        @foreach ($comments as $comment)
                            <li>
                                {!! $comment->text !!}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            @if ($comment)
                <div class="columns" style="margin-top: 4rem">
                    <div class="column is-half is-offset-one-quarter">
                        <h2 class="subtitle has-text-centered">Edit Comment</h2>
                        <form method="post" action="{{ route('comments.update', $comment) }}">
                            <input type="hidden" name="mentions" id="mentions_update" value="{{ $comment->mentions()->encoded() }}">

                            <div class="field">
                                <div class="control">
                                    <textarea class="hide" name="text" id="text_update">{!! $comment->text !!}</textarea>
                                    <div class="textarea has-mentions-update" contenteditable="true" for="#text_update">{!! $comment->text !!}</div>
                                </div>
                            </div>

                            <button class="button is-primary is-pulled-right" type="submit">
                                Update Comment
                            </button>

                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
