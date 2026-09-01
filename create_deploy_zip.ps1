# Run this from project root in PowerShell to build a clean deploy.zip
# Usage: powershell -ExecutionPolicy Bypass -File .\create_deploy_zip.ps1

$source = $PSScriptRoot
$output = Join-Path $source 'deploy.zip'

# Delete old zip if exists
if (Test-Path $output) {
    Remove-Item $output -Force
    Write-Host "Removed old deploy.zip"
}

# Paths to exclude (relative to project root)
$exclude = @(
    'vendor',
    'node_modules',
    '.git',
    '.vscode',
    '.idea',
    '.claude',
    '.fleet',
    '.zed',
    'storage\logs',
    'storage\framework\cache',
    'storage\framework\sessions',
    'storage\framework\views',
    'storage\framework\testing',
    'storage\pail',
    'storage\app\jobg8',
    'storage\app\public\imports',
    'public\storage',
    'public\user\images\_archive_raw_uploads',
    'tests',
    'deploy.zip',
    'create_deploy_zip.ps1'
)

# Files to exclude
$excludeFiles = @(
    '.env',
    '.env.backup',
    '.env.production',
    '.phpunit.result.cache',
    'ExcelImport.zip',
    'auth.json',
    'find_email_args.json',
    'mcp_call.cjs',
    'mcp_call_file.cjs',
    'mcp_probe.cjs',
    'result.txt',
    'seed-blogs.php',
    'Homestead.json',
    'Homestead.yaml',
    'CLAUDE.md',
    'boost.json'
)

Write-Host "Collecting files (excluding heavy/sensitive)..."

$items = Get-ChildItem -Path $source -Recurse -Force | Where-Object {
    $rel = $_.FullName.Substring($source.Length + 1)
    $skip = $false
    foreach ($e in $exclude) {
        if ($rel -eq $e -or $rel.StartsWith($e + '\') -or $rel.StartsWith($e + '/')) {
            $skip = $true
            break
        }
    }
    if (-not $skip) {
        foreach ($f in $excludeFiles) {
            if ($_.Name -eq $f -and $_.Directory.FullName -eq $source) {
                $skip = $true
                break
            }
        }
    }
    # Skip *.log files anywhere
    if (-not $skip -and $_.Extension -eq '.log') { $skip = $true }
    -not $skip -and -not $_.PSIsContainer
}

Write-Host ("Files to add: {0}" -f $items.Count)

# Build zip using .NET (faster than Compress-Archive for many files)
Add-Type -AssemblyName 'System.IO.Compression.FileSystem'
$zip = [System.IO.Compression.ZipFile]::Open($output, 'Create')

$i = 0
foreach ($f in $items) {
    $i++
    $rel = $f.FullName.Substring($source.Length + 1).Replace('\', '/')
    try {
        [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($zip, $f.FullName, $rel, 'Optimal') | Out-Null
    } catch {
        Write-Warning "Skipped: $rel ($_)"
    }
    if ($i % 500 -eq 0) { Write-Host "  $i files added..." }
}
$zip.Dispose()

$size = (Get-Item $output).Length / 1MB
Write-Host ""
Write-Host ("DONE: deploy.zip created ({0:N1} MB, {1} files)" -f $size, $items.Count) -ForegroundColor Green
Write-Host "Location: $output" -ForegroundColor Green
