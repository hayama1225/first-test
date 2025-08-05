<p>login</p>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ログイン</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
</head>

<body>
    <header class="header">
        <div class="header__inner"><a class="header__logo" href="/">Contact Form</a></div>
    </header>

    <main>
        <div class="contact-form__content">
            <div class="contact-form__heading">
                <h2>ログイン</h2>
            </div>

            <form class="form" action="{{ route('login') }}" method="POST" novalidate>
                @csrf
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">メールアドレス</span>
                        <span class="form__label--required">必須</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="test@example.com" autofocus />
                        </div>
                        <div class="form__error">@error('email') {{ $message }} @enderror</div>
                    </div>
                </div>

                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">パスワード</span>
                        <span class="form__label--required">必須</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="password" name="password" placeholder="********" />
                        </div>
                        <div class="form__error">@error('password') {{ $message }} @enderror</div>
                    </div>
                </div>

                <div class="form__group">
                    <div class="form__group-title"><span class="form__label--item">ログイン状態を保持</span></div>
                    <div class="form__group-content" style="text-align:left;">
                        <label><input type="checkbox" name="remember"> Remember me</label>
                    </div>
                </div>

                <div class="form__button"><button class="form__button-submit" type="submit">ログイン</button></div>
            </form>
        </div>
    </main>
</body>

</html>