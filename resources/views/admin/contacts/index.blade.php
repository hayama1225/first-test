<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>お問い合わせ一覧</title>
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
        <h2>お問い合わせ一覧</h2>
      </div>

      <div class="form">
        <table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse; width:100%;">
          <thead>
            <tr>
              <th>ID</th>
              <th>氏名</th>
              <th>性別</th>
              <th>メール</th>
              <th>電話</th>
              <th>住所</th>
              <th>種類</th>
              <th>内容</th>
              <th>作成日</th>
            </tr>
          </thead>
          <tbody>
            @forelse($contacts as $c)
            <tr>
              <td>{{ $c->id }}</td>
              <td>{{ $c->last_name }} {{ $c->first_name }}</td>
              <td>@php $g=[1=>'男性',2=>'女性',3=>'その他']; @endphp {{ $g[$c->gender] ?? $c->gender }}</td>
              <td>{{ $c->email }}</td>
              <td>{{ $c->tel }}</td>
              <td>{{ $c->address }} {{ $c->building }}</td>
              <td>{{ $c->category?->content }}</td>
              <td>{{ Str::limit($c->detail, 30) }}</td>
              <td>{{ $c->created_at->format('Y-m-d') }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="9">データがありません</td>
            </tr>
            @endforelse
          </tbody>
        </table>

        <div style="margin-top:12px;">
          {{ $contacts->withQueryString()->links() }}
        </div>
      </div>
    </div>
  </main>
</body>

</html>
BLADE