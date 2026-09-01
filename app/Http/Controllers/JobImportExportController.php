<?php

namespace App\Http\Controllers;

use App\Exports\JobsExport;
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

        $importer = new JobsImport();
        Excel::import($importer, $request->file('file'));

        $msg = sprintf(
            'Import complete — %d new jobs imported, %d duplicates skipped.',
            $importer->imported,
            $importer->skipped
        );

        return redirect()->route('admin.jobs.index')->with('success', $msg);
    }

    public function export(Request $request)
    {
        set_time_limit(300);

        $type = $request->get('type', 'xlsx');
        $fileName = 'jobs_'.now()->format('Ymd_His').'.'.($type === 'csv' ? 'csv' : 'xlsx');

        return Excel::download(new JobsExport(), $fileName);
    }
}
