$root = Split-Path -Parent $MyInvocation.MyCommand.Path

$listener = New-Object System.Net.HttpListener
$listener.Prefixes.Add("http://localhost:8000/")
$listener.Prefixes.Add("http://localhost:8080/")
$listener.Start()

Write-Output "City College of Education local server started on http://localhost:8000/ and http://localhost:8080/"
try { Start-Process "http://localhost:8000/" } catch {}

$mimeTypes = @{
    ".html" = "text/html; charset=utf-8"
    ".css"  = "text/css; charset=utf-8"
    ".js"   = "application/javascript; charset=utf-8"
    ".json" = "application/json; charset=utf-8"
    ".png"  = "image/png"
    ".jpg"  = "image/jpeg"
    ".jpeg" = "image/jpeg"
    ".svg"  = "image/svg+xml"
    ".ico"  = "image/x-icon"
}

try {
    while ($listener.IsListening) {
        $context  = $listener.GetContext()
        $request  = $context.Request
        $response = $context.Response

        $localPath = $request.Url.LocalPath.TrimStart('/')
        if ([string]::IsNullOrEmpty($localPath) -or $localPath -eq "/" -or $localPath -eq "index.html") {
            $localPath = "preview/index.html"
        } elseif ($localPath -eq "wp-admin" -or $localPath -eq "wp-admin/") {
            $localPath = "preview/wp-admin.html"
        } elseif (-not (Test-Path (Join-Path $root ($localPath -replace '/', '\')) -PathType Leaf)) {
            # Resolve relative paths requested from root when preview/index.html is served at /
            $altPath = Join-Path $root ($localPath -replace '^\.\./', '' -replace '/', '\')
            if (Test-Path $altPath -PathType Leaf) {
                $localPath = ($localPath -replace '^\.\./', '')
            }
        }

        $filePath = Join-Path $root ($localPath -replace '/', '\')
        if (Test-Path $filePath -PathType Leaf) {
            $ext = [System.IO.Path]::GetExtension($filePath).ToLower()
            $response.ContentType = if ($mimeTypes.ContainsKey($ext)) { $mimeTypes[$ext] } else { "application/octet-stream" }
            $bytes = [System.IO.File]::ReadAllBytes($filePath)
            $response.ContentLength64 = $bytes.Length
            $response.StatusCode = 200
            $response.OutputStream.Write($bytes, 0, $bytes.Length)
        } else {
            $response.StatusCode = 404
            $msg = [System.Text.Encoding]::UTF8.GetBytes("404 Not Found: $localPath")
            $response.OutputStream.Write($msg, 0, $msg.Length)
        }
        $response.OutputStream.Close()
    }
} finally {
    $listener.Stop()
}
