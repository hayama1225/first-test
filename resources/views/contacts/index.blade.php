<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Form</title>
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
                <h2>お問い合わせ</h2>
            </div>

            <form class="form" action="{{ route('contacts.confirm') }}" method="POST" novalidate>
                @csrf

                {{-- お名前（姓・名） --}}
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">お名前</span>
                        <span class="form__label--required">必須</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text" style="display:flex; gap:8px;">
                            <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="山田" />
                            <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="太郎" />
                        </div>
                        <div class="form__error">
                            @error('last_name') {{ $message }} @enderror
                            @error('first_name') {{ $message }} @enderror
                        </div>
                    </div>
                </div>

                {{-- 性別 --}}
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">性別</span>
                        <span class="form__label--required">必須</span>
                    </div>
                    <div class="form__group-content">
                        @php $g = old('gender', 1); @endphp
                        <label><input type="radio" name="gender" value="1" {{ $g==1?'checked':'' }}> 男性</label>
                        <label style="margin-left:10px;"><input type="radio" name="gender" value="2" {{ $g==2?'checked':'' }}> 女性</label>
                        <label style="margin-left:10px;"><input type="radio" name="gender" value="3" {{ $g==3?'checked':'' }}> その他</label>
                        <div class="form__error">@error('gender') {{ $message }} @enderror</div>
                    </div>
                </div>

                {{-- メールアドレス --}}
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">メールアドレス</span>
                        <span class="form__label--required">必須</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="test@example.com" />
                        </div>
                        <div class="form__error">@error('email') {{ $message }} @enderror</div>
                    </div>
                </div>

                {{-- 電話番号 --}}
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">電話番号</span>
                        <span class="form__label--required">必須</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="tel" name="tel" value="{{ old('tel') }}" placeholder="09012345678" />
                        </div>
                        <div class="form__error">@error('tel') {{ $message }} @enderror</div>
                    </div>
                </div>

                {{-- 住所 --}}
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">住所</span>
                        <span class="form__label--required">必須</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="text" name="address" value="{{ old('address') }}" placeholder="東京都千代田区..." />
                        </div>
                        <div class="form__error">@error('address') {{ $message }} @enderror</div>
                    </div>
                </div>

                {{-- 建物名（任意） --}}
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">建物名</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <input type="text" name="building" value="{{ old('building') }}" placeholder="○○ビル101" />
                        </div>
                        <div class="form__error">@error('building') {{ $message }} @enderror</div>
                    </div>
                </div>

                {{-- お問い合わせの種類 --}}
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">お問い合わせの種類</span>
                        <span class="form__label--required">必須</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--text">
                            <select name="category_id">
                                <option value="">選択してください</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (string)old('category_id')===(string)$cat->id ? 'selected':'' }}>
                                    {{ $cat->content }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form__error">@error('category_id') {{ $message }} @enderror</div>
                    </div>
                </div>

                {{-- お問い合わせ内容（120字以内） --}}
                <div class="form__group">
                    <div class="form__group-title">
                        <span class="form__label--item">お問い合わせ内容</span>
                        <span class="form__label--required">必須</span>
                    </div>
                    <div class="form__group-content">
                        <div class="form__input--textarea">
                            <textarea name="detail" maxlength="120" placeholder="資料をいただきたいです">{{ old('detail') }}</textarea>
                        </div>
                        <div class="form__error">@error('detail') {{ $message }} @enderror</div>
                    </div>
                </div>

                <div class="form__button">
                    <button class="form__button-submit" type="submit">確認画面</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>