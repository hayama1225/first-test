<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Confirm</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
</head>

<body>
    <header class="header">
        <div class="header__inner"><a class="header__logo" href="/">Contact Form</a></div>
    </header>

    <main>
        <div class="contact-form__content">
            <div class="contact-form__heading">
                <h2>確認画面</h2>
            </div>

            <div class="form" style="text-align:left;">
                <dl>
                    <dt>お名前</dt>
                    <dd>{{ $data['last_name'] }} {{ $data['first_name'] }}</dd>
                    <dt>性別</dt>
                    <dd>@php $g=[1=>'男性',2=>'女性',3=>'その他']; @endphp {{ $g[$data['gender']] ?? $data['gender'] }}</dd>
                    <dt>メールアドレス</dt>
                    <dd>{{ $data['email'] }}</dd>
                    <dt>電話番号</dt>
                    <dd>{{ $data['tel'] }}</dd>
                    <dt>住所</dt>
                    <dd>{{ $data['address'] }} {{ $data['building'] ?? '' }}</dd>
                    <dt>お問い合わせの種類</dt>
                    <dd>{{ $category }}</dd>
                    <dt>お問い合わせ内容</dt>
                    <dd>{{ $data['detail'] }}</dd>
                </dl>

                <form action="{{ route('contacts.store') }}" method="POST" style="margin-top:20px;">
                    @csrf
                    @foreach($data as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <div class="form__button" style="display:flex; gap:12px;">
                        <button class="form__button-submit" type="submit">送信</button>
                        <button class="form__button-submit" type="button" onclick="history.back()">修正</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>

</html>