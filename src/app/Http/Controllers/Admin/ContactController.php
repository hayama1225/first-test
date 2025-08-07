<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $q = $this->buildQuery($request)
            ->with('category') // ★ 追加（buildQuery内で既に eager load していても無害）
            ->latest();

        $contacts = $q->paginate(7)->appends($request->query());

        $categories = Category::orderBy('id')->get();

        return view('admin.contacts.index', compact('contacts', 'categories'));
    }

    /**
     * CSV エクスポート（検索条件を引き継ぎ）
     */
    public function export(Request $request): StreamedResponse
    {
        // 1) 検索条件を反映したクエリを取得
        $q = $this->buildQuery($request)
            ->with('category')
            ->latest();

        $contacts = $q->get();

        // 2) CSVレスポンス生成
        $response = new StreamedResponse(function () use ($contacts) {
            $handle = fopen('php://output', 'w');

            // BOM (Excelで文字化け防止)
            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // ヘッダー行
            fputcsv($handle, [
                'ID',
                '姓',
                '名',
                '性別',
                'メール',
                '電話',
                '住所',
                '建物名',
                'お問い合わせ種類',
                '内容',
                '作成日'
            ]);

            // データ行
            foreach ($contacts as $c) {
                $genderMap = [1 => '男性', 2 => '女性', 3 => 'その他'];
                fputcsv($handle, [
                    $c->id,
                    $c->last_name,
                    $c->first_name,
                    $genderMap[$c->gender] ?? $c->gender,
                    $c->email,
                    $c->tel,
                    $c->address,
                    $c->building,
                    $c->category?->content,
                    $c->detail,
                    $c->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        });

        // 3) ヘッダー設定
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="contacts_export.csv"');

        return $response;
    }

    /**
     * 検索条件の共通化（index/export 両方で利用）
     */
    private function buildQuery(Request $request)
    {
        $q = Contact::query()->with('category');

        // フリーワード（氏名/メール/電話/住所/内容）
        if ($request->filled('keyword')) {
            $kw = trim($request->keyword);
            $q->where(function ($qq) use ($kw) {
                $qq->where('last_name', 'like', "%{$kw}%")
                    ->orWhere('first_name', 'like', "%{$kw}%")
                    ->orWhereRaw("CONCAT(last_name,' ',first_name) like ?", ["%{$kw}%"])
                    ->orWhere('email', 'like', "%{$kw}%")
                    ->orWhere('tel', 'like', "%{$kw}%")
                    ->orWhere('address', 'like', "%{$kw}%")
                    ->orWhere('detail', 'like', "%{$kw}%");
            });
        }

        // 性別（1,2,3）
        if ($request->filled('gender')) {
            $q->where('gender', (int) $request->gender);
        }

        // 種類（カテゴリ）
        if ($request->filled('category_id')) {
            $q->where('category_id', (int) $request->category_id);
        }

        // 期間（作成日）
        if ($request->filled('from')) {
            $q->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->whereDate('created_at', '<=', $request->to);
        }

        return $q;
    }

    public function show(\App\Models\Contact $contact)
    {
        $contact->load('category');
        return view('admin.contacts.show', compact('contact'));
    }

    public function showJson(\App\Models\Contact $contact)
    {
        $contact->load('category');
        return response()->json($contact);
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        // 単純削除（ソフトデリート不要ならこのまま）
        $contact->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('status', 'お問い合わせを削除しました。');
    }
}
