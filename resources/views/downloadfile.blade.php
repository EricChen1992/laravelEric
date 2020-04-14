<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
</head>
<body>
    <h1>{{ $head }}</h1>
    <p style="font-size: xx-small;">{{ $content }}</p>
    <div>

        <div class="field">
            <div class="ui left icon input">
                <i class="user icon"></i>
                <input type="text" id="month"
                       style="
                            position: relative;
                            font-weight: 400;
                            font-style: normal;
                            display: -webkit-inline-box;
                            display: -ms-inline-flexbox;
                            display: inline-flex;
                            color: rgba(0,0,0,.87);
                            border-radius: 5px;
                            border: line;
                            border-style: solid;
                            padding: 5px 10px;
                            font-size: 1em;
                            letter-spacing: 1px;" 
                        name="yearandmounth" placeholder="輸入年月(YYYY-MM)">
            </div>
        </div>

        <a class="ui submit button" id="submit-button"
        style="cursor: pointer;
            display: inline-block;
            min-height: 1em;
            outline: 0;
            border: none;
            vertical-align: baseline;
            background: #e0e1e2 none;
            color: rgba(0,0,0,.6);
            font-family: 'Microsoft JhengHei','Montserrat', 'sans-serif';
            padding: .78571429em 1.5em .78571429em;
            text-transform: none;
            text-shadow: none;
            font-weight: 700;
            line-height: 1em;
            font-style: normal;
            text-align: center;
            text-decoration: none;
            border-radius: .28571429rem;
            margin-top: 10px;" 
        
        href="#">
            {{$button_name}}
        </a>			
    </div>
</body>

<script>
    let button = document.getElementById('submit-button'),//利用 id 取得Button物件
        month = document.getElementById('month'),//利用 id 取得input物件
        route = "{{ route('downloadexcel', ['value' => '__value__']) }}";//Value 帶進去得陣列裡 key 一定要跟原先設定api那帶的變數名稱一樣，value則前後雙底線原因是怕有其他名稱一樣

    month.addEventListener('change', (event) => {
        button.href = route.replace(new RegExp('__value__', 'g'), month.value);//利用正規式把直更換
    });
</script>
</html>