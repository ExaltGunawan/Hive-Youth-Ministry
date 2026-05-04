<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $note->title }}</title>
    <style>
        @page {
            margin: 1cm;
            size: auto;
        }
        @media print {
            .no-print { display: none !important; }
            body { 
                padding: 0; 
                margin: 0;
                background: white;
            }
            /* Try to hide browser headers/footers */
            @page { margin: 1cm; }
        }
        body {
            font-family: 'Helvetica', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #FFB300;
            margin-bottom: 30px;
            padding-bottom: 20px;
        }
        .logo-text {
            color: #FFB300;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .title {
            font-size: 28px;
            font-weight: bold;
            margin: 20px 0;
            color: #000;
        }
        .meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #FFB300;
            border-bottom: 1px solid #eee;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
        .content {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }
        .attendance-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .badge {
            background: #eee;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 13px;
        }
        .no-print-btn {
            background: #FFB300;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right;">
        <button onclick="window.print()" class="no-print-btn">Click here to Save as PDF / Print</button>
    </div>

    <div class="header">
        <div class="logo-text">HIVE YOUTH MINISTRY</div>
        <div class="meta">Notulensi Rapat - {{ $note->created_at->format('d F Y H:i') }}</div>
    </div>

    <div class="section">
        <div class="title">{{ $note->title ?? '(Tanpa Judul)' }}</div>
    </div>

    @if(count($attendanceNames) > 0)
    <div class="section">
        <div class="section-title">Daftar Hadir</div>
        <div class="attendance-list">
            @foreach($attendanceNames as $name)
                <span class="badge">{{ $name }}</span>
            @endforeach
        </div>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Isi Notulensi</div>
        <div class="content">
            {!! $note->value !!}
        </div>
    </div>

    @if($note->conclusion)
    <div class="section">
        <div class="section-title">Kesimpulan / Action Plan</div>
        <div class="content" style="background: #fff8e1; border-left: 4px solid #FFB300;">
            {{ $note->conclusion }}
        </div>
    </div>
    @endif

    <script>
        // Auto trigger print dialog then close tab
        window.onload = function() {
            window.print();
            // Optional: Close tab after printing (works in some browsers after user interaction)
            // window.onafterprint = function() {
            //     window.close();
            // };
        }
    </script>
</body>
</html>
