<!DOCTYPE html>
<html lang="ja">

@php use Illuminate\Support\Str; @endphp

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

  @if (session('status'))
  <div class="form" style="margin-bottom:12px; color:#0a0;">
    {{ session('status') }}
  </div>
  @endif

  <main>
    <div class="contact-form__content">
      <div class="contact-form__heading">
        <h2>お問い合わせ一覧</h2>
      </div>

      {{-- 検索フォーム（教材のクラス命名に合わせて/ GET 送信） --}}
      <form method="GET" action="{{ route('admin.contacts.index') }}" class="form" style="margin-bottom:20px;">
        {{-- フリーワード --}}
        <div class="form__group">
          <div class="form__group-title">
            <span class="form__label--item">キーワード</span>
          </div>
          <div class="form__group-content">
            <div class="form__input--text">
              <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="氏名・メール・電話・住所・内容で検索" />
            </div>
          </div>
        </div>

        {{-- 性別 --}}
        <div class="form__group">
          <div class="form__group-title">
            <span class="form__label--item">性別</span>
          </div>
          <div class="form__group-content" style="text-align:left;">
            @php $g=request('gender'); @endphp
            <label><input type="radio" name="gender" value="" {{ $g===''||$g===null ? 'checked' : '' }}> すべて</label>
            <label style="margin-left:10px;"><input type="radio" name="gender" value="1" {{ $g==='1' ? 'checked' : '' }}> 男性</label>
            <label style="margin-left:10px;"><input type="radio" name="gender" value="2" {{ $g==='2' ? 'checked' : '' }}> 女性</label>
            <label style="margin-left:10px;"><input type="radio" name="gender" value="3" {{ $g==='3' ? 'checked' : '' }}> その他</label>
          </div>
        </div>

        {{-- 種類（カテゴリ） --}}
        <div class="form__group">
          <div class="form__group-title">
            <span class="form__label--item">お問い合わせの種類</span>
          </div>
          <div class="form__group-content">
            <div class="form__input--text">
              <select name="category_id">
                <option value="">すべて</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ (string)request('category_id')===(string)$cat->id ? 'selected':'' }}>
                  {{ $cat->content }}
                </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        {{-- 期間 From - To --}}
        <div class="form__group">
          <div class="form__group-title">
            <span class="form__label--item">期間</span>
          </div>
          <div class="form__group-content" style="text-align:left;">
            <input type="date" name="from" value="{{ request('from') }}" />
            <span style="margin:0 8px;">〜</span>
            <input type="date" name="to" value="{{ request('to') }}" />
          </div>
        </div>

        <div class="form__button" style="display:flex; gap:12px; justify-content:center;">
          <button class="form__button-submit" type="submit">検索</button>
          <a class="form__button-submit" href="{{ route('admin.contacts.index') }}" style="display:inline-block;text-align:center;line-height:30px;">リセット</a>
          <a class="form__button-submit" href="{{ route('admin.contacts.export', request()->query()) }}">CSVダウンロード</a>
        </div>
      </form>

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
              <th>詳細</th>
              <th>削除</th>
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
              <td>
                <a href="#" class="detail-btn" data-id="{{ $c->id }}">詳細</a>
              </td>
              <td>
                <form action="{{ route('admin.contacts.destroy', $c) }}" method="POST"
                  onsubmit="return confirm('ID {{ $c->id }} を削除します。よろしいですか？');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="form__button-submit" style="height:auto; padding:6px 10px;">
                    削除
                  </button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="11">データがありません</td>
            </tr>
            @endforelse
          </tbody>
        </table>

        <!-- ▼モーダル本体（最初は非表示） -->
        <div id="detailModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4);">
          <div style="background:#fff; padding:20px; width:60%; margin:50px auto; border-radius:5px; position:relative;">
            <h3>お問い合わせ詳細</h3>
            <div id="modalContent">読み込み中...</div>
            <button id="closeModal" style="margin-top:15px; padding:8px 12px;">閉じる</button>
          </div>
        </div>

        <script>
          document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.detail-btn').forEach(function(btn) {
              btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;

                fetch(`/admin/contacts/${id}/json`)
                  .then(res => res.json())
                  .then(data => {
                    let gMap = {
                      1: '男性',
                      2: '女性',
                      3: 'その他'
                    };
                    let html = `
            <p><strong>ID:</strong> ${data.id}</p>
            <p><strong>氏名:</strong> ${data.last_name} ${data.first_name}</p>
            <p><strong>性別:</strong> ${gMap[data.gender] ?? data.gender}</p>
            <p><strong>メール:</strong> ${data.email}</p>
            <p><strong>電話:</strong> ${data.tel}</p>
            <p><strong>住所:</strong> ${data.address} ${data.building ?? ''}</p>
            <p><strong>種類:</strong> ${data.category?.content ?? ''}</p>
            <p><strong>内容:</strong> ${data.detail}</p>
            <p><strong>作成日:</strong> ${data.created_at}</p>
          `;
                    document.getElementById('modalContent').innerHTML = html;
                    document.getElementById('detailModal').style.display = 'block';
                  });
              });
            });

            document.getElementById('closeModal').addEventListener('click', function() {
              document.getElementById('detailModal').style.display = 'none';
            });
          });
        </script>


        <div style="margin-top:12px;">
          {{ $contacts->withQueryString()->links() }}
        </div>
      </div>
    </div>
  </main>
</body>

</html>