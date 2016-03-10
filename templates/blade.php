<html>
<head>
    <title>Healthz</title>

    <style type="text/css">
        html, body {
            margin: 0;
            padding: 0;
            font-family: Helvetica, Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin-left:10%;
            margin-top: 30px;
        }
        h2 { font-size: 1.5em; }
        .health-checks {
            padding-left: 0;
            margin-top: 30px;
        }
        .health-checks .result {
            display: block;
            margin: 20px 0;
        }
        .health-checks .title {
            display:block;
            background: #eaeaea;
            padding: 10px;
            font-weight: bold;
            color: #fff;
        }
        .health-checks .title small {
            font-style: italic;
            font-weight: normal;
            font-size: .8em;
        }
        .health-checks .status {
            margin: 0;
            padding: 10px;
            font-size: .9em;
            color: #333;
        }
        .health-checks .result.success .title { background-color: #2ca02c; }
        .health-checks .result.warning .title { background-color: #d58512; }
        .health-checks .result.failure .title { background-color: #d43f3a; }
    </style>
</head>

<body>

    <div class="container">
        <h2>Health Check</h2>

        <ul class="health-checks">
            @foreach($results->all() as $result)
                <li class="result @if($result->passed()) success @elseif($result->warned()) warning @else failure @endif">
                    <span class="title">
                        {{ $result->title() }} <small>{{ $result->description() }}</small>
                    </span>

                    <p class="status">{{ $result->status() }}</p>
                </li>
            @endforeach
        </ul>
    </div>
</body>
</html>
