<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterTemplatePernyataan;
use App\Models\TemplatePernyataanDetail;
use Illuminate\Support\Facades\DB;

class MasterTemplatePernyataanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = MasterTemplatePernyataan::query();

        if ($search) {
            $query->where('kode_dokumen', 'like', "%{$search}%")
                  ->orWhere('judul_formulir', 'like', "%{$search}%")
                  ->orWhere('jenis_formulir', 'like', "%{$search}%");
        }

        $templates = $query->paginate(10);

        return view('master_template.index', compact('templates', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_dokumen' => 'required|string|max:15|unique:master_template_pernyataan,kode_dokumen',
            'judul_formulir' => 'required|string|max:50',
            'jenis_formulir' => 'required|string|max:20',
            'is_active' => 'boolean'
        ]);

        MasterTemplatePernyataan::create([
            'kode_dokumen' => $request->kode_dokumen,
            'judul_formulir' => $request->judul_formulir,
            'jenis_formulir' => $request->jenis_formulir,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('master-template.index')->with('success', 'Master Template berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_formulir' => 'required|string|max:50',
            'jenis_formulir' => 'required|string|max:20',
            'is_active' => 'boolean'
        ]);

        $template = MasterTemplatePernyataan::findOrFail($id);
        $template->update([
            'judul_formulir' => $request->judul_formulir,
            'jenis_formulir' => $request->jenis_formulir,
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()->route('master-template.index')->with('success', 'Master Template berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $template = MasterTemplatePernyataan::findOrFail($id);
        // Cascading delete is handled manually or by DB FK constraint
        $template->details()->delete();
        $template->delete();

        return redirect()->route('master-template.index')->with('success', 'Master Template berhasil dihapus.');
    }

    // --- Details Management ---

    public function showDetails($kode_dokumen)
    {
        $master = MasterTemplatePernyataan::with('details')->findOrFail($kode_dokumen);
        return view('master_template.detail', compact('master'));
    }

    public function storeDetail(Request $request, $kode_dokumen)
    {
        $request->validate([
            'urutan' => 'nullable|integer',
            'jenis_informasi' => 'required|string|max:50',
            'isi_informasi' => 'nullable|string',
            'is_editable' => 'boolean',
        ]);

        $master = MasterTemplatePernyataan::findOrFail($kode_dokumen);
        
        $urutan = $request->input('urutan');
        if (is_null($urutan)) {
            $urutan = ($master->details()->max('urutan') ?? 0) + 1;
        } else {
            TemplatePernyataanDetail::where('kode_dokumen', $kode_dokumen)
                ->where('urutan', '>=', $urutan)
                ->increment('urutan');
        }

        $master->details()->create([
            'urutan' => $urutan,
            'jenis_informasi' => $request->jenis_informasi,
            'isi_informasi' => $request->isi_informasi,
            'is_editable' => $request->boolean('is_editable', false),
        ]);

        return redirect()->route('master-template.details', $kode_dokumen)->with('success', 'Detail informasi berhasil ditambahkan.');
    }

    public function updateDetail(Request $request, $kode_dokumen, $id)
    {
        $request->validate([
            'urutan' => 'required|integer',
            'jenis_informasi' => 'required|string|max:50',
            'isi_informasi' => 'nullable|string',
            'is_editable' => 'boolean',
        ]);

        $detail = TemplatePernyataanDetail::where('kode_dokumen', $kode_dokumen)->findOrFail($id);
        
        $old_urutan = $detail->urutan;
        $new_urutan = $request->urutan;

        if ($new_urutan != $old_urutan) {
            if ($new_urutan < $old_urutan) {
                TemplatePernyataanDetail::where('kode_dokumen', $kode_dokumen)
                    ->whereBetween('urutan', [$new_urutan, $old_urutan - 1])
                    ->increment('urutan');
            } else {
                TemplatePernyataanDetail::where('kode_dokumen', $kode_dokumen)
                    ->whereBetween('urutan', [$old_urutan + 1, $new_urutan])
                    ->decrement('urutan');
            }
        }

        $detail->update([
            'urutan' => $new_urutan,
            'jenis_informasi' => $request->jenis_informasi,
            'isi_informasi' => $request->isi_informasi,
            'is_editable' => $request->boolean('is_editable', false),
        ]);

        return redirect()->route('master-template.details', $kode_dokumen)->with('success', 'Detail informasi berhasil diperbarui.');
    }

    public function destroyDetail($kode_dokumen, $id)
    {
        $detail = TemplatePernyataanDetail::where('kode_dokumen', $kode_dokumen)->findOrFail($id);
        $urutan = $detail->urutan;
        $detail->delete();

        TemplatePernyataanDetail::where('kode_dokumen', $kode_dokumen)
            ->where('urutan', '>', $urutan)
            ->decrement('urutan');

        return redirect()->route('master-template.details', $kode_dokumen)->with('success', 'Detail informasi berhasil dihapus.');
    }
    
    public function reorderDetail(Request $request, $kode_dokumen)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|integer',
            'orders.*.urutan' => 'required|integer'
        ]);

        DB::transaction(function() use ($request, $kode_dokumen) {
            foreach($request->orders as $order) {
                TemplatePernyataanDetail::where('kode_dokumen', $kode_dokumen)
                    ->where('id', $order['id'])
                    ->update(['urutan' => $order['urutan']]);
            }
        });

        return response()->json(['success' => true]);
    }
}
