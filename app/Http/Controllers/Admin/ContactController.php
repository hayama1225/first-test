<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        // 検索条件を適用して一覧
        $q = $this->buildQuery($request)->latest();

        // ページネーション（検索条件を維持）
        $contacts = $q->paginate(7);
        $contacts->appends($request->query()); // ← withQueryString() と同じ効果

        $categories = Category::orderBy('id')->get();

        return view('admin.contacts.index', [
            'contacts'   => $contacts,
            'categories' => $categories,
        ]);
    }

    /**
     * CSV エクスポート（検索条件を引き継ぎ）
     */
    public function export(Request $request): StreamedResponse
    {
        $q = $this->buildQuery($request)->orderBy('id'); // CSVは安定の昇順

        $filename = 'contacts_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($q) {
            $out = fopen('php://output', 'w');

            // Excel対策: UTF-8 BOM を付与（Shift_JISが必要なら後述の変換に差し替え）
            fwrite($out, "\xEF\xBB\xBF");

            // ヘッダ行
            fputcsv($out, ['ID', '姓', '名', '性別', 'メール', '電話', '住所', '建物名', '種類', '内容', '作成日']);

            $mapGender = [1 => '男性', 2 => '女性', 3 => 'その他'];

            // 大量データでも安全に chunk で出力
            $q->chunk(500, function ($rows) use ($out, $mapGender) {
                foreach ($rows as $r) {
                    fputcsv($out, [
                        $r->id,
                        $r->last_name,
                        $r->first_name,
                        $mapGender[$r->gender] ?? $r->gender,
                        $r->email,
                        $r->tel,
                        $r->address,
                        $r->building,
                        optional($r->category)->content,
                        $r->detail,
                        optional($r->created_at)?->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($out);
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
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

    public function destroy(\App\Models\Contact $contact, \Illuminate\Http\Request $request)
    {
        $contact->delete();

        // 検索条件を保ったまま一覧へ戻す
        $q = $request->query(); // ?keyword=... など
        return redirect()->route('admin.contacts.index', $q)
            ->with('status', "ID {$contact->id} を削除しました。");
    }
}
