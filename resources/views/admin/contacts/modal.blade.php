@push('css')
<link rel="stylesheet" href="{{ asset('css/contact.css') }}"> {{-- 例：入力画面 --}}
@endpush

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
</head>

<form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}"
    onsubmit="return confirm('本当に削除しますか？');" class="mt-3">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger w-100">削除</button>
</form>