<!DOCTYPE html>
<html>
<head>
    <title>Side-by-Side Version Diff Inspector - {{ $post->title }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 30px; margin: 0; color: #2c3e50; }
        .container { max-width: 1200px; margin: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; background: white; padding: 20px 25px; border-radius: 10px; box-shadow: 0 3px 12px rgba(0,0,0,.08); margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 8px 14px; text-decoration: none; border-radius: 6px; border: none; cursor: pointer; font-size: 14px; font-weight: 600; }
        .btn-secondary { background: #7f8c8d; color: white; }
        .btn-primary { background: #3498db; color: white; }
        .btn-success { background: #2ecc71; color: white; }
        .selector-box { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 3px 12px rgba(0,0,0,.08); margin-bottom: 25px; }
        .selector-form { display: flex; gap: 20px; align-items: center; }
        .select-group { flex: 1; }
        .select-group label { display: block; font-weight: bold; margin-bottom: 6px; font-size: 14px; color: #555; }
        .select-group select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; }
        .diff-card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 3px 12px rgba(0,0,0,.08); margin-bottom: 25px; }
        .diff-card h3 { margin-top: 0; color: #34495e; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px; }
        .diff-box { background: #fafafa; border: 1px solid #e0e0e0; border-radius: 6px; padding: 15px; font-family: monospace; font-size: 14px; line-height: 1.6; white-space: pre-wrap; word-break: break-word; }
        .comparison-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .version-panel { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 3px 12px rgba(0,0,0,.08); }
        .version-panel.v1 { border-top: 5px solid #e74c3c; }
        .version-panel.v2 { border-top: 5px solid #2ecc71; }
        .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .panel-title { font-weight: bold; font-size: 16px; }
        .meta { color: #7f8c8d; font-size: 13px; }
        .field-label { font-weight: bold; color: #7f8c8d; font-size: 12px; text-uppercase: true; margin-top: 15px; margin-bottom: 5px; }
        .field-value { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px; padding: 12px; font-size: 14px; line-height: 1.5; min-height: 40px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <h1>⚡ Side-by-Side Visual Diff Inspector</h1>
            <div class="meta" style="margin-top: 4px;">Comparing version differences for Post #{{ $post->id }}: <strong>{{ $post->title }}</strong></div>
        </div>
        <div>
            <a href="{{ route('posts.versions', $post) }}" class="btn btn-secondary">← Back to Version History</a>
        </div>
    </div>

    <!-- Version Selector Bar -->
    <div class="selector-box">
        <form method="GET" action="{{ route('posts.versions.compare', $post) }}" class="selector-form">
            <div class="select-group">
                <label for="v1">Base Version (Old / Source):</label>
                <select name="v1" id="v1" onchange="this.form.submit()">
                    <option value="current" {{ $v1Id === 'current' ? 'selected' : '' }}>🔴 Current Live Post (Latest)</option>
                    @foreach($versions as $ver)
                        <option value="{{ $ver->id }}" {{ $v1Id == $ver->id ? 'selected' : '' }}>
                            Version #{{ $ver->id }} {{ $ver->label ? "({$ver->label})" : '' }} — {{ $ver->created_at->format('d M Y, h:i A') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="font-size: 24px; font-weight: bold; color: #7f8c8d; margin-top: 20px;">➔</div>

            <div class="select-group">
                <label for="v2">Target Version (New / Comparison):</label>
                <select name="v2" id="v2" onchange="this.form.submit()">
                    <option value="current" {{ $v2Id === 'current' ? 'selected' : '' }}>🟢 Current Live Post (Latest)</option>
                    @foreach($versions as $ver)
                        <option value="{{ $ver->id }}" {{ $v2Id == $ver->id ? 'selected' : '' }}>
                            Version #{{ $ver->id }} {{ $ver->label ? "({$ver->label})" : '' }} — {{ $ver->created_at->format('d M Y, h:i A') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <noscript>
                <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Compare</button>
            </noscript>
        </form>
    </div>

    <!-- Color-Coded Inline Diff Highlighting -->
    <div class="diff-card">
        <h3>🔍 Inline Word-by-Word Change Highlights</h3>
        <p style="margin-bottom: 15px; font-size: 13px; color: #666;">
            Legend: <ins style="background-color: #d4edda; color: #155724; padding: 2px 6px; border-radius: 4px; font-weight: bold; text-decoration: none;">Green = Added Text</ins> &nbsp; 
            <del style="background-color: #f8d7da; color: #721c24; padding: 2px 6px; border-radius: 4px; text-decoration: line-through;">Red = Removed Text</del>
        </p>

        <div style="margin-bottom: 15px;">
            <div class="field-label">TITLE DIFF:</div>
            <div class="diff-box">{!! $titleDiff !!}</div>
        </div>

        <div>
            <div class="field-label">CONTENT DIFF:</div>
            <div class="diff-box">{!! $contentDiff !!}</div>
        </div>
    </div>

    <!-- Side-by-Side Comparison Grid -->
    <div class="comparison-grid">
        <!-- Panel 1: Base Version -->
        <div class="version-panel v1">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Base: {{ $v1Data['label'] }}</div>
                    <div class="meta">{{ optional($v1Data['created_at'])->format('d M Y, h:i A') }}</div>
                </div>
                @if($v1Model)
                    <span class="btn btn-secondary" style="font-size: 12px; padding: 4px 8px;">Version #{{ $v1Model->id }}</span>
                @else
                    <span class="btn btn-primary" style="font-size: 12px; padding: 4px 8px;">Live</span>
                @endif
            </div>

            <div class="field-label">TITLE:</div>
            <div class="field-value">{{ $v1Data['title'] }}</div>

            <div class="field-label">CONTENT:</div>
            <div class="field-value" style="min-height: 180px;">{{ $v1Data['content'] }}</div>
        </div>

        <!-- Panel 2: Target Version -->
        <div class="version-panel v2">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Target: {{ $v2Data['label'] }}</div>
                    <div class="meta">{{ optional($v2Data['created_at'])->format('d M Y, h:i A') }}</div>
                </div>
                @if($v2Model)
                    <span class="btn btn-secondary" style="font-size: 12px; padding: 4px 8px;">Version #{{ $v2Model->id }}</span>
                @else
                    <span class="btn btn-primary" style="font-size: 12px; padding: 4px 8px;">Live</span>
                @endif
            </div>

            <div class="field-label">TITLE:</div>
            <div class="field-value">{{ $v2Data['title'] }}</div>

            <div class="field-label">CONTENT:</div>
            <div class="field-value" style="min-height: 180px;">{{ $v2Data['content'] }}</div>
        </div>
    </div>
</div>
</body>
</html>
