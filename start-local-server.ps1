$root = Split-Path -Parent $MyInvocation.MyCommand.Path

$desiredPorts = @(8000, 8080, 8081, 8082)
$activePorts = @()
$listener = New-Object System.Net.HttpListener

foreach ($port in $desiredPorts) {
    try {
        $testListener = New-Object System.Net.HttpListener
        $prefix = "http://localhost:$port/"
        $testListener.Prefixes.Add($prefix)
        $testListener.Start()
        $testListener.Stop()
        $testListener.Close()
        $listener.Prefixes.Add($prefix)
        $activePorts += $port
    } catch {
        # Port is already bound or unavailable, skip
    }
}

if ($activePorts.Count -eq 0) {
    Write-Error "Could not bind to any local port (tried $($desiredPorts -join ', '))."
    exit 1
}

$listener.Start()

$primaryUrl = "http://localhost:$($activePorts[0])/"
$urls = $activePorts | ForEach-Object { "http://localhost:$_/" }

Write-Host "==================================================================" -ForegroundColor Green
Write-Host " City College of Education Server is running!" -ForegroundColor Green
Write-Host " Active URLs: $($urls -join ' | ')" -ForegroundColor Cyan
Write-Host " Frontend: $primaryUrl" -ForegroundColor Yellow
Write-Host " WP-Admin: ${primaryUrl}wp-admin" -ForegroundColor Yellow
Write-Host " Press [Ctrl+C] to stop the server." -ForegroundColor Gray
Write-Host "==================================================================" -ForegroundColor Green

try { Start-Process $primaryUrl } catch {}

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

function Get-HttpRequestBody($req) {
    if ($req.ContentLength64 -gt 0) {
        $buffer = New-Object byte[] $req.ContentLength64
        $total = 0
        while ($total -lt $req.ContentLength64) {
            $read = $req.InputStream.Read($buffer, $total, ($req.ContentLength64 - $total))
            if ($read -le 0) { break }
            $total += $read
        }
        return [System.Text.Encoding]::UTF8.GetString($buffer, 0, $total)
    }
    return ""
}

function Flatten-List($raw) {
    $result = [System.Collections.Generic.List[object]]::new()
    $walk = {
        param($node)
        if ($node -is [System.Collections.IEnumerable] -and $node -isnot [string] -and $node -isnot [System.Collections.IDictionary]) {
            foreach ($el in $node) { & $walk $el }
        } elseif ($null -ne $node -and $node.PSObject -and $node.PSObject.Properties['value']) {
            & $walk $node.value
        } elseif ($null -ne $node) {
            $result.Add($node)
        }
    }
    & $walk $raw
    return $result
}

try {
    while ($listener.IsListening) {
        $context = $null
        try {
            $context = $listener.GetContext()
        } catch {
            break
        }
        $request  = $context.Request
        $response = $context.Response

        try {
            # Set CORS headers on all responses
            $response.AddHeader("Access-Control-Allow-Origin", "*")
            $response.AddHeader("Access-Control-Allow-Methods", "GET, POST, OPTIONS, PUT, DELETE")
            $response.AddHeader("Access-Control-Allow-Headers", "Content-Type, Authorization, X-Requested-With")

            if ($request.HttpMethod -eq "OPTIONS") {
                $response.StatusCode = 204
                continue
            }

            $rawPath = $request.Url.LocalPath.TrimStart('/')

            # =========================================================================
            # BACKEND REST API: ADMISSIONS APPLICATIONS PERSISTENCE
            # =========================================================================
            if ($rawPath -eq "api/applications" -or $rawPath -eq "api/applications/") {
                $appsFile = Join-Path $root "preview\data\applications.json"
                if (-not (Test-Path $appsFile)) {
                    $dataDir = Join-Path $root "preview\data"
                    if (-not (Test-Path $dataDir)) { New-Item -ItemType Directory -Path $dataDir -Force | Out-Null }
                    [System.IO.File]::WriteAllText($appsFile, "[]", [System.Text.Encoding]::UTF8)
                }

                if ($request.HttpMethod -eq "GET") {
                    $jsonContent = [System.IO.File]::ReadAllText($appsFile, [System.Text.Encoding]::UTF8)
                    $raw = ConvertFrom-Json -InputObject $jsonContent
                    $flat = Flatten-List $raw
                    $outJson = ConvertTo-Json -InputObject $flat -Depth 10
                    $bytes = [System.Text.Encoding]::UTF8.GetBytes($outJson)
                    $response.ContentType = "application/json; charset=utf-8"
                    $response.ContentLength64 = $bytes.Length
                    $response.StatusCode = 200
                    if ($request.HttpMethod -ne "HEAD") {
                        $response.OutputStream.Write($bytes, 0, $bytes.Length)
                    }
                    continue
                } elseif ($request.HttpMethod -eq "POST") {
                    $body = Get-HttpRequestBody $request
                    $newApp = ConvertFrom-Json -InputObject $body

                    $existingJson = [System.IO.File]::ReadAllText($appsFile, [System.Text.Encoding]::UTF8)
                    $existingRaw = ConvertFrom-Json -InputObject $existingJson
                    $flat = Flatten-List $existingRaw

                    $updated = [System.Collections.Generic.List[object]]::new()
                    $updated.Add($newApp)
                    foreach ($it in $flat) {
                        if ($it.ref -ne $newApp.ref) {
                            $updated.Add($it)
                        }
                    }

                    $updatedJson = ConvertTo-Json -InputObject $updated -Depth 10
                    [System.IO.File]::WriteAllText($appsFile, $updatedJson, [System.Text.Encoding]::UTF8)

                    $respJson = '{"success":true,"message":"Application submitted successfully","ref":"' + $newApp.ref + '"}'
                    $bytes = [System.Text.Encoding]::UTF8.GetBytes($respJson)
                    $response.ContentType = "application/json; charset=utf-8"
                    $response.ContentLength64 = $bytes.Length
                    $response.StatusCode = 201
                    $response.OutputStream.Write($bytes, 0, $bytes.Length)
                    continue
                }
            } elseif ($rawPath -eq "api/applications/save-all") {
                if ($request.HttpMethod -eq "POST") {
                    $body = Get-HttpRequestBody $request

                    $appsFile = Join-Path $root "preview\data\applications.json"
                    $flat = Flatten-List (ConvertFrom-Json -InputObject $body)
                    $updatedJson = ConvertTo-Json -InputObject $flat -Depth 10
                    [System.IO.File]::WriteAllText($appsFile, $updatedJson, [System.Text.Encoding]::UTF8)

                    $respJson = '{"success":true,"message":"All applications updated"}'
                    $bytes = [System.Text.Encoding]::UTF8.GetBytes($respJson)
                    $response.ContentType = "application/json; charset=utf-8"
                    $response.ContentLength64 = $bytes.Length
                    $response.StatusCode = 200
                    $response.OutputStream.Write($bytes, 0, $bytes.Length)
                    continue
                }
            } elseif ($rawPath -eq "api/applications/status") {
                if ($request.HttpMethod -eq "POST") {
                    $body = Get-HttpRequestBody $request

                    $statusUpdate = ConvertFrom-Json -InputObject $body
                    $appsFile = Join-Path $root "preview\data\applications.json"
                    if (Test-Path $appsFile) {
                        $existingJson = [System.IO.File]::ReadAllText($appsFile, [System.Text.Encoding]::UTF8)
                        $flat = Flatten-List (ConvertFrom-Json -InputObject $existingJson)
                        foreach ($item in $flat) {
                            if ($item.ref -eq $statusUpdate.ref) {
                                if ($statusUpdate.status) {
                                    $item | Add-Member -NotePropertyName "status" -NotePropertyValue $statusUpdate.status -Force
                                }
                                if ($statusUpdate.admin_notes -ne $null) {
                                    $item | Add-Member -NotePropertyName "admin_notes" -NotePropertyValue $statusUpdate.admin_notes -Force
                                }
                            }
                        }
                        $updatedJson = ConvertTo-Json -InputObject $flat -Depth 10
                        [System.IO.File]::WriteAllText($appsFile, $updatedJson, [System.Text.Encoding]::UTF8)
                    }

                    $respJson = '{"success":true}'
                    $bytes = [System.Text.Encoding]::UTF8.GetBytes($respJson)
                    $response.ContentType = "application/json; charset=utf-8"
                    $response.ContentLength64 = $bytes.Length
                    $response.StatusCode = 200
                    $response.OutputStream.Write($bytes, 0, $bytes.Length)
                    continue
                }
            }

            # =========================================================================
            # BACKEND REST API: PROGRAMMES (Add, Edit, Delete, List)
            # =========================================================================
            if ($rawPath -eq "api/programmes" -or $rawPath -eq "api/programmes/") {
                $progsFile = Join-Path $root "preview\data\programmes.json"
                if (-not (Test-Path $progsFile)) {
                    $dataDir = Join-Path $root "preview\data"
                    if (-not (Test-Path $dataDir)) { New-Item -ItemType Directory -Path $dataDir -Force | Out-Null }
                    [System.IO.File]::WriteAllText($progsFile, "[]", [System.Text.Encoding]::UTF8)
                }

                if ($request.HttpMethod -eq "GET") {
                    $jsonContent = [System.IO.File]::ReadAllText($progsFile, [System.Text.Encoding]::UTF8)
                    $raw = ConvertFrom-Json -InputObject $jsonContent
                    $flat = Flatten-List $raw
                    $outJson = ConvertTo-Json -InputObject $flat -Depth 10
                    $bytes = [System.Text.Encoding]::UTF8.GetBytes($outJson)
                    $response.ContentType = "application/json; charset=utf-8"
                    $response.ContentLength64 = $bytes.Length
                    $response.StatusCode = 200
                    if ($request.HttpMethod -ne "HEAD") {
                        $response.OutputStream.Write($bytes, 0, $bytes.Length)
                    }
                    continue
                } elseif ($request.HttpMethod -eq "POST") {
                    $body = Get-HttpRequestBody $request
                    $newProg = ConvertFrom-Json -InputObject $body
                    if (-not $newProg.id) {
                        $newProg | Add-Member -NotePropertyName "id" -NotePropertyValue ("prog-" + [DateTimeOffset]::UtcNow.ToUnixTimeMilliseconds()) -Force
                    }

                    $existingJson = [System.IO.File]::ReadAllText($progsFile, [System.Text.Encoding]::UTF8)
                    $existingRaw = ConvertFrom-Json -InputObject $existingJson
                    $flat = Flatten-List $existingRaw

                    $updated = [System.Collections.Generic.List[object]]::new()
                    $updated.Add($newProg)
                    foreach ($it in $flat) {
                        if ($it.id -ne $newProg.id) {
                            $updated.Add($it)
                        }
                    }

                    $updatedJson = ConvertTo-Json -InputObject $updated -Depth 10
                    [System.IO.File]::WriteAllText($progsFile, $updatedJson, [System.Text.Encoding]::UTF8)

                    $respJson = '{"success":true,"message":"Programme saved successfully","id":"' + $newProg.id + '"}'
                    $bytes = [System.Text.Encoding]::UTF8.GetBytes($respJson)
                    $response.ContentType = "application/json; charset=utf-8"
                    $response.ContentLength64 = $bytes.Length
                    $response.StatusCode = 201
                    $response.OutputStream.Write($bytes, 0, $bytes.Length)
                    continue
                }
            } elseif ($rawPath -eq "api/programmes/update") {
                if ($request.HttpMethod -eq "POST") {
                    $body = Get-HttpRequestBody $request
                    $updatedProg = ConvertFrom-Json -InputObject $body
                    $progsFile = Join-Path $root "preview\data\programmes.json"
                    if (Test-Path $progsFile) {
                        $existingJson = [System.IO.File]::ReadAllText($progsFile, [System.Text.Encoding]::UTF8)
                        $flat = Flatten-List (ConvertFrom-Json -InputObject $existingJson)
                        foreach ($item in $flat) {
                            if ($item.id -eq $updatedProg.id) {
                                if ($updatedProg.title) { $item | Add-Member -NotePropertyName "title" -NotePropertyValue $updatedProg.title -Force }
                                if ($updatedProg.level) { $item | Add-Member -NotePropertyName "level" -NotePropertyValue $updatedProg.level -Force }
                                if ($updatedProg.affiliation) { $item | Add-Member -NotePropertyName "affiliation" -NotePropertyValue $updatedProg.affiliation -Force }
                                if ($updatedProg.duration) { $item | Add-Member -NotePropertyName "duration" -NotePropertyValue $updatedProg.duration -Force }
                                if ($updatedProg.description -ne $null) { $item | Add-Member -NotePropertyName "description" -NotePropertyValue $updatedProg.description -Force }
                            }
                        }
                        $updatedJson = ConvertTo-Json -InputObject $flat -Depth 10
                        [System.IO.File]::WriteAllText($progsFile, $updatedJson, [System.Text.Encoding]::UTF8)
                    }
                    $respJson = '{"success":true,"message":"Programme updated successfully"}'
                    $bytes = [System.Text.Encoding]::UTF8.GetBytes($respJson)
                    $response.ContentType = "application/json; charset=utf-8"
                    $response.ContentLength64 = $bytes.Length
                    $response.StatusCode = 200
                    $response.OutputStream.Write($bytes, 0, $bytes.Length)
                    continue
                }
            } elseif ($rawPath -eq "api/programmes/delete") {
                if ($request.HttpMethod -eq "POST") {
                    $body = Get-HttpRequestBody $request
                    $delReq = ConvertFrom-Json -InputObject $body
                    $progsFile = Join-Path $root "preview\data\programmes.json"
                    if (Test-Path $progsFile) {
                        $existingJson = [System.IO.File]::ReadAllText($progsFile, [System.Text.Encoding]::UTF8)
                        $flat = Flatten-List (ConvertFrom-Json -InputObject $existingJson)
                        $updated = [System.Collections.Generic.List[object]]::new()
                        foreach ($item in $flat) {
                            if ($item.id -ne $delReq.id) {
                                $updated.Add($item)
                            }
                        }
                        $updatedJson = ConvertTo-Json -InputObject $updated -Depth 10
                        [System.IO.File]::WriteAllText($progsFile, $updatedJson, [System.Text.Encoding]::UTF8)
                    }
                    $respJson = '{"success":true,"message":"Programme deleted"}'
                    $bytes = [System.Text.Encoding]::UTF8.GetBytes($respJson)
                    $response.ContentType = "application/json; charset=utf-8"
                    $response.ContentLength64 = $bytes.Length
                    $response.StatusCode = 200
                    $response.OutputStream.Write($bytes, 0, $bytes.Length)
                    continue
                }
            } elseif ($rawPath -eq "api/programmes/save-all") {
                if ($request.HttpMethod -eq "POST") {
                    $body = Get-HttpRequestBody $request
                    $progsFile = Join-Path $root "preview\data\programmes.json"
                    $flat = Flatten-List (ConvertFrom-Json -InputObject $body)
                    $updatedJson = ConvertTo-Json -InputObject $flat -Depth 10
                    [System.IO.File]::WriteAllText($progsFile, $updatedJson, [System.Text.Encoding]::UTF8)

                    $respJson = '{"success":true,"message":"All programmes saved"}'
                    $bytes = [System.Text.Encoding]::UTF8.GetBytes($respJson)
                    $response.ContentType = "application/json; charset=utf-8"
                    $response.ContentLength64 = $bytes.Length
                    $response.StatusCode = 200
                    $response.OutputStream.Write($bytes, 0, $bytes.Length)
                    continue
                }
            }

            # =========================================================================
            # BACKEND REST API: COURSE CATALOG PERSISTENCE
            # =========================================================================
            if ($rawPath -eq "api/courses" -or $rawPath -eq "api/courses/") {
                $coursesFile = Join-Path $root "preview\data\courses.json"
                if (-not (Test-Path $coursesFile)) {
                    $dataDir = Join-Path $root "preview\data"
                    if (-not (Test-Path $dataDir)) { New-Item -ItemType Directory -Path $dataDir -Force | Out-Null }
                    [System.IO.File]::WriteAllText($coursesFile, "[]", [System.Text.Encoding]::UTF8)
                }

                if ($request.HttpMethod -eq "GET") {
                    $raw = ConvertFrom-Json -InputObject ([System.IO.File]::ReadAllText($coursesFile, [System.Text.Encoding]::UTF8))
                    $flat = Flatten-List $raw
                    $bytes = [System.Text.Encoding]::UTF8.GetBytes((ConvertTo-Json -InputObject $flat -Depth 10))
                    $response.ContentType = "application/json; charset=utf-8"
                    $response.ContentLength64 = $bytes.Length
                    $response.StatusCode = 200
                    $response.OutputStream.Write($bytes, 0, $bytes.Length)
                    continue
                } elseif ($request.HttpMethod -eq "POST") {
                    $body = Get-HttpRequestBody $request
                    if (-not $body.TrimStart().StartsWith("[")) {
                        throw "Course updates must be a JSON array."
                    }
                    $flat = Flatten-List (ConvertFrom-Json -InputObject $body)
                    $updatedJson = ConvertTo-Json -InputObject $flat -Depth 10
                    [System.IO.File]::WriteAllText($coursesFile, $updatedJson, [System.Text.Encoding]::UTF8)
                    $bytes = [System.Text.Encoding]::UTF8.GetBytes('{"success":true,"message":"Courses saved"}')
                    $response.ContentType = "application/json; charset=utf-8"
                    $response.ContentLength64 = $bytes.Length
                    $response.StatusCode = 200
                    $response.OutputStream.Write($bytes, 0, $bytes.Length)
                    continue
                }
            }

            # =========================================================================
            # STATIC FILES RESOLUTION
            # =========================================================================
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
                if ($request.HttpMethod -ne "HEAD") {
                    $response.OutputStream.Write($bytes, 0, $bytes.Length)
                }
            } else {
                $response.StatusCode = 404
                $msg = [System.Text.Encoding]::UTF8.GetBytes("404 Not Found: $localPath")
                $response.ContentLength64 = $msg.Length
                if ($request.HttpMethod -ne "HEAD") {
                    $response.OutputStream.Write($msg, 0, $msg.Length)
                }
            }
        } catch {
            Write-Host "Error handling request: $_"
            try {
                $response.StatusCode = 500
                $errBytes = [System.Text.Encoding]::UTF8.GetBytes("500 Server Error: $_")
                $response.ContentLength64 = $errBytes.Length
                $response.OutputStream.Write($errBytes, 0, $errBytes.Length)
            } catch {}
        } finally {
            try { $response.OutputStream.Close() } catch {}
        }
    }
} finally {
    $listener.Stop()
}
