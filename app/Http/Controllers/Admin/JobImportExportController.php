<?php

namespace App\Http\Controllers\Admin;

use App\Exports\JobsExport;
use App\Http\Controllers\Controller;
use App\Imports\JobsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class JobImportExportController extends Controller
{
    public function showImportForm()
    {
        return view('admin.jobs.import');
    }

    public function import(Request $request)
    {
        // Increase execution time for large file imports
        set_time_limit(1800); // 30 minutes

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:202400',
        ]);

        $importer = new JobsImport;
        Excel::import($importer, $request->file('file'));

        $imported = $importer->imported;
        $skipped = $importer->skipped;
        $msg = "Import complete — {$imported} new jobs imported, {$skipped} duplicates skipped.";

        // AJAX requests get JSON so the client can render its own progress UI
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'imported' => $imported,
                'skipped' => $skipped,
                'message' => $msg,
                'redirect_url' => route('admin.jobs.index'),
            ]);
        }

        return redirect()->route('admin.jobs.index')->with('success', $msg);
    }

    public function export(Request $request)
    {
        set_time_limit(300);

        $type = $request->get('type', 'xlsx');
        $fileName = 'jobs_'.now()->format('Ymd_His').'.'.($type === 'csv' ? 'csv' : 'xlsx');

        return Excel::download(new JobsExport, $fileName);
    }
}
