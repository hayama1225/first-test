<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>お問い合わせ詳細</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a class="header__logo" href="/">Contact Form</a>
        </div>
    </header>

    <main>
        <div class="contact-form__content">
            <div class="contact-form__heading">
                <h2>お問い合わせ詳細</h2>
            </div>

            <div class="form" style="text-align:left;">
                @php $g=[1=>'男性',2=>'女性',3=>'その他']; @endphp
                <p><strong>ID：</strong>{{ $contact->id }}</p>
                <p><strong>氏名：</strong>{{ $contact->last_name }} {{ $contact->first_name }}</p>
                <p><strong>性別：</strong>{{ $g[$contact->gender] ?? $contact->gender }}</p>
                <p><strong>メール：</strong>{{ $contact->email }}</p>
                <p><strong>電話：</strong>{{ $contact->tel }}</p>
                <p><strong>住所：</strong>{{ $contact->address }} {{ $contact->building }}</p>
                <p><strong>種類：</strong>{{ $contact->category?->content }}</p>
                <p><strong>内容：</strong>{{ $contact->detail }}</p>
                <p><strong>作成日：</strong>{{ $contact->created_at->format('Y-m-d H:i') }}</p>

                <div class="form__button" style="margin-top:20px;">
                    <a class="form__button-submit" href="{{ route('admin.contacts.index', request()->query()) }}">一覧に戻る</a>
                </div>
            </div>
        </div>
    </main>
</body>

</html>